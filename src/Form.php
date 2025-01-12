<?php

namespace Veneridze\LaravelForms;


use Exception;
use Illuminate\Support\Arr;
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
                Arr::set($result, $field->key, $field->label);
            }
        }
        return Arr::get($result, $key, null);
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
