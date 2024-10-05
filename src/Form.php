<?php
namespace Veneridze\LaravelForms;


use Exception;
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
    public static function fields(string $type): array
    {
        return [];
    }

    public static function filterFields(string $type = null, string $model, array $rows): array
    {
        $space = app(Permission::class)->getClassName($model);
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
                $key = $property->getName();
                $result[$key] = $attribute->getArguments()[0]; ///strtolower($property->getName());
            }
        }
        foreach ($form::fields('view') as $row) {
            foreach ($row as $field) {
                $result[$field['key']] = $field['label'];
            }
        }
        return array_key_exists($key, $result) ? $result[$key] : null;
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

    public function post(string $class): Model
    {
        $other = array_filter(array_change_key_case($this->all()));
        $allows = array_change_key_case(DB::getSchemaBuilder()->getColumnListing(app($class)->getTable()));
        $data = collect($other)->only($allows)->toArray();
        $model = $class::create($data);
        $this->updateRelationShips($other, $model);
        if (property_exists($model, 'observer')) {

            $model::$observer::created1($model);
        }
        return $model;
    }

    public function patch(object $model)
    {
        $other = array_filter(array_change_key_case($this->all()));
        $allows = array_change_key_case(DB::getSchemaBuilder()->getColumnListing(app($model::class)->getTable()));
        $data = collect($other)->only($allows)->toArray();
        $model->update($data);
        $this->updateRelationShips($other, $model);
        $model->refresh();
        if (property_exists($model::class, 'observer')) {
            $model::$observer::updated1($model);
        }
    }

    public static function getWithRelations(Model $model)
    {
        $basic = self::From($model)->toArray();
        $info = ModelInfo::forModel($model::class);
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

    private function updateRelationShips($data, Model $model)
    {
        $info = ModelInfo::forModel($model::class);
        foreach ($info->relations as $relation) {
            $name = $relation->name;
            if (array_key_exists($name, $data)) {
                if ($relation->type != BelongsTo::class && $relation->type != BelongsToMany::class) {
                    $model->$name()->sync($data[$name]);
                }
            }
        }
    }
}
