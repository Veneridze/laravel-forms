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
    public static Model $model;
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

    public static function getKeyName(string $form, string $key) {
        $result = [];
        $reflect = new ReflectionClass($form);
        foreach ($reflect->getProperties() as $property) {
            foreach ($property->getAttributes(Name::class) as $attribute) {
                //$key = ;
                $result[$property->getName()] = $attribute->getArguments()[0]; ///strtolower($property->getName());
            }
        }
        foreach ($form::fields('view') as $row) {
            foreach ($row as $field) {
                $result[$field->key] = $field->label;
            }
        }
        return Arr::get($result, $key, null); //array_key_exists($key, $result) ? $result[$key] : null;
    }

    public static function toData(Form $form): array
    {
        $result = [];
        $reflect = new ReflectionClass($form);
        foreach ($reflect->getProperties() as $property) {
            foreach ($property->getAttributes(Name::class) as $attribute) {
                $key = $property->getName();
                $result[$attribute->value] = $form->$key; ///strtolower($property->getName());
            }
        }
        return [
            ...$result,
            ...array_map(
                function ($row) use ($form) {
                    return array_map(
                        function (Element $field) use ($form) {
                            $key = $field->key;
                            return $field->toData($form->$key);
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
        if(method_exists($this, 'fillByRelatedModel')) {
            $this->fillByRelatedModel($role->relationModel());
        }
        $other = array_filter(array_change_key_case($this->all()), fn($v, $k) => $v !== null, ARRAY_FILTER_USE_BOTH);
        $allows = array_change_key_case(DB::getSchemaBuilder()->getColumnListing(app(static::$model)->getTable()));
        $data = collect($other)->only($allows)->toArray();
        $obj = static::$model::create($data);
        $this->updateRelationShips($other);
        if (property_exists(static::$model, 'observer')) {

            static::$model::$observer::created1($obj);
        }
        return $obj;
    }

    public function patch()
    {
        //static::$model = static::class;
        //$role = Auth::user();
        if(method_exists($this, 'fillByRelatedModel')) {
            $this->fillByRelatedModel(static::$model->relationModel());
        }
        $other = array_filter(array_change_key_case($this->all()), fn($v, $k) => $v !== null, ARRAY_FILTER_USE_BOTH);
        $allows = array_change_key_case(DB::getSchemaBuilder()->getColumnListing(app(static::$model)->getTable()));
        $data = collect($other)->only($allows)->toArray();
        static::$model->update($data);
        $this->updateRelationShips($other, static::$model);
        static::$model->refresh();
        if (property_exists(static::$model, 'observer')) {
            static::$model::$observer::updated1(static::$model);
        }
    }

    public static function getWithRelations()
    {
        //static::$model = static::class;
        $basic = self::From(static::$model)->toArray();
        $info = ModelInfo::forModel(static::$model);
        foreach ($info->relations as $relation) {
            $name = $relation->name;
            $basic[$name] = match ($relation->type) {
                BelongsToMany::class => RelationData::collect(static::$model->$name)->toArray(),
                HasMany::class => RelationData::collect(static::$model->$name)->toArray(),
                HasManyThrough::class => RelationData::collect(static::$model->$name)->toArray(),
                BelongsTo::class => RelationData::from(static::$model->$name)->toArray(),
                default => null
            };
        }
        return $basic;
    }

    private function updateRelationShips($data)
    {
        //static::$model = static::class;
        $info = ModelInfo::forModel(static::$model);
        foreach ($info->relations as $relation) {
            $name = $relation->name;
            if (array_key_exists($name, $data)) {
                if ($relation->type != BelongsTo::class) {
                    static::$model->$name()->sync($data[$name]);
                }
            }
        }
    }
}
