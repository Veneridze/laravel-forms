<?php
namespace Veneridze\LaravelForms\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;
use Veneridze\EloquentAuthorable\AuthorableTrait;
class Draft extends Model
{
    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];
    use SortableTrait;
    use AuthorableTrait;
    protected $table = 'form_drafts';
    protected $guarded = [];
    protected $casts = [
        'public' => 'bool',
        'data' => 'array'
    ];

    public function scopePublic(Builder $query): void
    {
        $query->where('public', 1);
    }
}
