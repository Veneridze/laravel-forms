<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Veneridze\EloquentAuthorable\AuthorableTrait;
class Draft extends Model
{
    use AuthorableTrait;
    protected $table = 'form_drafts';
    protected $casts = [
        'public' => 'bool'
    ];

    public function scopePublic(Builder $query): void
    {
        $query->where('public', 1);
    }
}
