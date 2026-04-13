<?php

namespace Veneridze\LaravelForms\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class PropertyComment extends Model
{
    use SoftDeletes;
    protected $casts = [
        'clean_on_update' => 'bool'
    ];

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if(!Schema::hasColumn($model->model->getTable(), $model->property)) {
                throw new Exception("Поле {$model->property} отсутствует у модели");
            }
        });
    }


    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function model(): MorphTo {
        return $this->morphTo('model');
    }
}
