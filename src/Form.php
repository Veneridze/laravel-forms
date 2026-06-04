<?php

namespace Veneridze\LaravelForms;


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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\ModelInfo\Relations\RelationFinder;

abstract class Form extends Data
{
    public static string $model;
    /**
     * Summary of fields
     * @param array $args
     * @return array<array<mixed>>
     */
    public static function fields(...$args): array
    {
        return [];
    }


    public static function filterFields(?string $type = null, array $rows): array
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

    public static function toTableValidation(...$args): array
    {
        $result = [];
        foreach (static::fields(...$args) as $row) {
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
        foreach (static::fields(...array_values(\Illuminate\Support\Facades\Route::current()->parameters())) as $row) {
            foreach ($row as $field) {
                if (($field->key ?? $field->startKey) == $key) {
                    return $field->label;
                }
            }
        }
        return null;
    }

    public static function formatByKey(string $key, $value)
    {
        $result = [];
        foreach (static::fields(...array_values(\Illuminate\Support\Facades\Route::current()->parameters())) as $row) {
            foreach ($row as $field) {
                $result[$field->key ?? $field->startKey] = $field;
            }
        }
        $fieldObj = $result[$key];
        // throw new Exception(json_encode($result[$key], JSON_UNESCAPED_UNICODE));
        return $fieldObj && method_exists($fieldObj, 'getRawValue') ? $fieldObj->getRawValue($value) : $value;
    }

    public static function addRules(): array
    {
        return [];
    }

    public static function updateRules(): array
    {
        return [];
    }
    public static function validate($context = null): array
    {
        $validations = [];

        foreach (static::fields(...array_values(\Illuminate\Support\Facades\Route::current()->parameters())) as $row) {
            foreach ($row as $field) {
                if (is_object($field) || is_string($field)) {
                    if (method_exists($field, 'validate')) {
                        $validations[$field->key ?? $field->startKey] = [
                            function (string $attribute, mixed $value, \Closure $fail) use ($field) {
                                if (!$field->validate($value)) {
                                    $fail("Указано недопустимое значение");
                                }
                            }
                        ];
                    }
                }
            }
        }

        if(request()->method() == "POST") {
            return (collect($validations)->mergeRecursive(method_exists(static::class, 'addRules') ? static::addRules() : [])->all());
        } else {
            return (collect($validations)->mergeRecursive(method_exists(static::class, 'updateRules') ? static::addRules() : [])->all());
        }
    }

    public static function getKeyByLabel(array $fields, string $label)
    {
        foreach ($fields as $row) {
            foreach ($row as $field) {
                if ($field->label == $label) {
                    return $field->key ?? $field->startKey;
                }
            }
        }
        return null;
    }

    public static function toData(...$args): array
    {
        $result = [];
        $reflect = new ReflectionClass(static::class);
        foreach ($reflect->getProperties() as $property) {
            foreach ($property->getAttributes(Name::class) as $attribute) {
                $propertyName = $property->getName();
                Arr::set($result, $attribute->getArguments()[0], static::$propertyName);
            }
        }
        return [
            ...$result,
            ...array_map(
                function ($row) {
                    return array_map(
                        function (Element $field) {
                            $key = $field->key;
                            return $field->toData(Arr::get(static::class, $key));
                        },
                        $row
                    );
                },
                static::fields(...array_values(\Illuminate\Support\Facades\Route::current()->parameters()))
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
        $this->validate();
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
        if (count($allows) > 0) {
            $allows = array_values(array_filter($allows, fn($k) => $k != 'id'));
            $data = collect($other)->only($allows);
        } else {
            $data = collect($other);
        }
        $data = $data->mapWithKeys(function ($item, $key) {
            if ($item === "true") {
                return [$key => true];
            } elseif ($item === "false") {
                return [$key => false];
            }
            return [$key => $item];
        })
            ->toArray();
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
        $this->validate();
        if (method_exists($this, 'fillByRelatedModel') && method_exists($this, 'relationModel')) {
            $rel = $model->relationModel();
            if ($rel) {
                $this->fillByRelatedModel($rel);
            }
        }
        $other = $this->all();
        //$other = array_filter(array_change_key_case($this->all()), fn($v, $k) => $v !== null, ARRAY_FILTER_USE_BOTH);
        $allows = array_change_key_case(DB::getSchemaBuilder()->getColumnListing(app(static::$model)->getTable()));
        if (count($allows) > 0) {
            $allows = array_values(array_filter($allows, fn($k) => $k != 'id'));
            $data = collect($other)->only($allows);
        } else {
            $data = collect($other);
        }
        $data = $data->mapWithKeys(function ($item, $key) {
            if ($item === "true") {
                return [$key => true];
            } elseif ($item === "false") {
                return [$key => false];
            }
            return [$key => $item];
        })
            ->toArray();
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
        $basic = static::from($model)->toArray();
        $relations = RelationFinder::forModel($model);
        foreach ($relations as $relation) {
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

    public static function compareString(string $str)
    {
        return str_replace(
            ['ё', 'Ё', 'й', 'Й'],
            ['е', 'Е', 'и', 'И'],
            $str
        );
    }
    private function updateRelationShips(Model $model, $data)
    {
        //static::$model = static::class;
        $relations = RelationFinder::forModel($model);
        foreach ($relations as $relation) {
            $name = $relation->name;
            if (array_key_exists($name, $data)) {
                if ($relation->type != BelongsTo::class) {
                    $model->$name()->sync($data[$name]);
                }
            }
        }
    }
}
