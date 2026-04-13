<?php
namespace Veneridze\LaravelForms\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Veneridze\LaravelForms\Models\PropertyComment;

trait HasPropertyComments
{
    public function propertyComments(): MorphMany {
        return $this->morphMany(PropertyComment::class, 'model');
    }

    public function propertyComment(string $attribute) {
        return $this->propertyComments()->where('property', $attribute)->get();
    }
}
