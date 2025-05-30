<?php

namespace Veneridze\LaravelForms;


use Exception;
use Veneridze\LaravelForms\Elements\BulletList;
use Veneridze\LaravelForms\Elements\Checkbox;
use Veneridze\LaravelForms\Elements\Date;
use Veneridze\LaravelForms\Elements\DateRange;
use Veneridze\LaravelForms\Elements\MultipleSelect;
use Veneridze\LaravelForms\Elements\Number;
use Veneridze\LaravelForms\Elements\Option;
use Veneridze\LaravelForms\Elements\Radio;
use Veneridze\LaravelForms\Elements\SearchSelect;
use Veneridze\LaravelForms\Elements\Select;
use Veneridze\LaravelForms\Elements\Text;
use Veneridze\LaravelForms\Elements\Textarea;
use Veneridze\LaravelForms\Elements\TimeRange;
use Veneridze\LaravelForms\Elements\TimeSelect;
use Veneridze\LaravelForms\Models\Draft;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use ReflectionClass;
use Veneridze\LaravelForms\Attributes\Name;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelPermission\Permission;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\LaravelData\Data;
use Spatie\ModelInfo\ModelInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use function GuzzleHttp\json_encode;

class Form extends Data
{
    /**
     * Summary of fields
     * @param string $type
     * @return array<array<Element>>
     */
    public static string $model;
    public static function fields(string $type): array
    {
        return [];
    }

    public static function filterFields(string $type = null, array $rows): array
    {
        $space = app(Permission::class)->getClassName(static::$model);
        if ($type) {
            return collect($rows)
                ->map(fn(array $row) => collect($row)
                    ->filter(fn($field) => app(Permission::class)->can(Auth::user(), "{$space}.{$type}.{$field->key}")))
                ->toArray();
        } else {
            return $rows;
        }
    }

    public static function toTableValidation(): array
    {
        $result = [];
        foreach (static::fields() as $row) {
            foreach ($row as $field) {
                if ($field instanceof Radio) {
                    $result[$field->label] = [
                        "type" => "radio",
                        "required" => $field->required,
                        "values" => collect($field->options)->map(fn(Option $option) => $option->label)->all()
                    ];
                } elseif ($field instanceof Date || $field instanceof DateRange) {
                    $result[$field->label] = [
                        "type" => "date",
                        "required" => $field->required,
                        "before" => $field->maxdate,
                        "after" => $field->mindate,
                    ];
                } elseif ($field instanceof Number) {
                    $result[$field->label] = [
                        "type" => "number",
                        "required" => $field->required,
                        "min" => $field->min,
                        "max" => $field->max,
                    ];
                } elseif ($field instanceof TimeSelect) {
                    $result[$field->label] = [
                        "type" => "time",
                        "required" => $field->required,
                    ];
                } elseif ($field instanceof BulletList) {
                    $result[$field->label] = [
                        "type" => "multiple",
                        "required" => $field->required,
                    ];
                } elseif ($field instanceof MultipleSelect) {
                    $result[$field->label] = [
                        "type" => "multiple",
                        "required" => $field->required,
                        "options" => $field->options
                    ];
                } elseif ($field instanceof SearchSelect) {
                    $result[$field->label] = [
                        "type" => "text",
                        "required" => $field->required
                    ];
                } elseif (($field instanceof Textarea || $field instanceof Text) && $field->maxlength != null) {
                    $result[$field->label] = [
                        "type" => "text",
                        "required" => $field->required,
                        "maxlength" => $field->maxlength
                    ];
                } elseif ($field instanceof Select) {
                    $result[$field->label] = [
                        "type" => "select",
                        "required" => $field->required,
                        "values" => $field->toTableData()->all()
                    ];
                } elseif ($field instanceof Checkbox) {
                    $result[$field->label] = [
                        "type" => "select",
                        "required" => $field->required,
                        "values" => [
                            __('Yes'),
                            __('No')
                        ]
                    ];
                }
            }
        }
        return $result;
    }

    public static function getKeyName(string $form, string $key)
    {
        $result = [];
        $reflect = new ReflectionClass($form);
        foreach ($reflect->getProperties() as $property) {
            foreach ($property->getAttributes(Name::class) as $attribute) {
                $propertyName = $property->getName();
                Arr::set($result, $propertyName, $attribute->getArguments()[0]);
            }
        }
        foreach ($form::fields('view') as $row) {
            foreach ($row as $field) {
                Arr::set($result, $field->key ?? $field->startKey, $field->label);
            }
        }
        return Arr::get($result, $key, null);
    }

    public static function formatByKey(string $form, string $key, $value)
    {
        $result = [];
        foreach ($form::fields('view') as $row) {
            foreach ($row as $field) {
                $result[$field->key ?? $field->startKey] = $field;
            }
        }
        $fieldObj = $result[$key];
        // throw new Exception(json_encode($result[$key], JSON_UNESCAPED_UNICODE));
        return $fieldObj && method_exists($fieldObj, 'getRawValue') ? $fieldObj->getRawValue($value) : $value;
    }

    public static function getKeyByLabel(string $form, string $label)
    {
        $result = [];
        // $reflect = new ReflectionClass($form);
        // foreach ($reflect->getProperties() as $property) {
        //     foreach ($property->getAttributes(Name::class) as $attribute) {
        //         $propertyName = $property->getName();
        //         Arr::set($result, $propertyName, $attribute->getArguments()[0]);
        //     }
        // }
        foreach ($form::fields('view') as $row) {
            foreach ($row as $field) {
                Arr::set($result, $field->label, $field->key ?? $field->startKey);
            }
        }
        return Arr::get($result, $label, null);
    }

    public static function toData(Form $form): array
    {
        $result = [];
        $reflect = new ReflectionClass($form);
        foreach ($reflect->getProperties() as $property) {
            foreach ($property->getAttributes(Name::class) as $attribute) {
                $propertyName = $property->getName();
                Arr::set($result, $attribute->getArguments()[0], $form->$propertyName);
            }
        }
        return [
            ...$result,
            ...array_map(
                function ($row) use ($form) {
                    return array_map(
                        function (Element $field) use ($form) {
                            $key = $field->key;
                            return $field->toData(Arr::get($form, $key));
                        },
                        $row
                    );
                },
                $form::fields('view')
            )
        ];
    }

    public function drafts(): Collection
    {
        return Draft::
            where('form', static::class)
            ->where(function (Builder $query) {
                $query->where('created_by', Auth::id())
                    ->orWhere('public', 1);
            })
            ->get()
            ->map(fn(Draft $draft) => [
                'id' => $draft->id,
                'name' => $draft->name
            ]);
    }
    public function post(): Model
    {
        $role = Auth::user();
        if (method_exists($this, 'fillByRelatedModel')) {
            $rel = $role->relationModel();
            if ($rel) {
                $this->fillByRelatedModel($rel);
            }
        }
        $other = $this->all();
        //$other = array_filter(array_change_key_case($this->all()), fn($v, $k) => $v !== null, ARRAY_FILTER_USE_BOTH);
        $allows = array_change_key_case(DB::getSchemaBuilder()->getColumnListing(app(static::$model)->getTable()));
        $allows = array_values(array_filter($allows, fn($k) => $k != 'id'));
        $data = collect($other)->only($allows)->toArray();
        $obj = static::$model::create($data);

        if (!method_exists($obj, 'hasManyDeep')) {
            $this->updateRelationShips($obj, $other);
        }
        if (property_exists(static::$model, 'observer')) {

            static::$model::$observer::created1($obj);
        }
        return $obj;
    }

    public function patch(Model $model)
    {
        //static::$model = static::class;
        //$role = Auth::user();
        if (method_exists($this, 'fillByRelatedModel') && method_exists($this, 'relationModel')) {
            $rel = $model->relationModel();
            if ($rel) {
                $this->fillByRelatedModel($rel);
            }
        }
        $other = $this->all();
        //$other = array_filter(array_change_key_case($this->all()), fn($v, $k) => $v !== null, ARRAY_FILTER_USE_BOTH);
        $allows = array_change_key_case(DB::getSchemaBuilder()->getColumnListing(app(static::$model)->getTable()));
        $data = collect($other)->only($allows)->toArray();
        $model->update($data);
        if (!method_exists($model, 'hasManyDeep')) {
            $this->updateRelationShips($model, $other);
        }
        $model->refresh();
        if (property_exists($model, 'observer')) {
            $model::$observer::updated1($model);
        }
    }

    public static function getWithRelations(Model $model)
    {
        $basic = self::from($model)->toArray();
        $info = ModelInfo::forModel($model);
        foreach ($info->relations as $relation) {
            $name = $relation->name;
            $basic[$name] = match ($relation->type) {
                BelongsToMany::class => RelationData::collect($model->$name)->toArray(),
                HasMany::class => RelationData::collect($model->$name)->toArray(),
                HasManyThrough::class => RelationData::collect($model->$name)->toArray(),
                BelongsTo::class => RelationData::from($model->$name)->toArray(),
                default => null
            };
        }
        return $basic;
    }

    private function updateRelationShips(Model $model, $data)
    {
        //static::$model = static::class;
        $info = ModelInfo::forModel($model);
        foreach ($info->relations as $relation) {
            $name = $relation->name;
            if (array_key_exists($name, $data)) {
                if ($relation->type != BelongsTo::class) {
                    $model->$name()->sync($data[$name]);
                }
            }
        }
    }
}
