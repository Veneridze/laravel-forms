<?php

namespace Veneridze\LaravelForms;

use Spatie\LaravelData\Data;

class RelationData extends Data
{
    public function __construct(
        public mixed $id,
        public ?string $name
    ) {}
}
