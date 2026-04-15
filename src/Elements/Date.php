<?php

namespace Veneridze\LaravelForms\Elements;

use Illuminate\Support\Str;
use Veneridze\LaravelForms\Interfaces\Element;
use Carbon\Carbon;
use Veneridze\LaravelForms\Prototype\Input;

final class Date extends Input implements Element
{
    public function toData($value): array
    {
        return [
            $this->label => $value ? 'Да' : 'Нет'
        ];
    }

    public function __construct(
        public string $key,
        public ?string $label = null,
        public ?string $type = 'date',
        public ?bool $disabled = false,
        public ?string $placeholder = null,
        public ?array $visibleif = [],
        public array $displayifset = [],
        public ?bool $holidays = true,
        public ?Carbon $mindate = null,
        public ?Carbon $maxdate = null,
        public ?string $default = null,
        public array $macros = [],
        public array $actions = [],
        public bool $required = false
    ) {
    }
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'required' => $this->required,
            'disabled' => $this->disabled,
            'label' => $this->label,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset,
            'holidays' => $this->holidays ?? true,
            'mindate' => $this->mindate ? $this->mindate->getTimestamp() : null,
            'maxdate' => $this->maxdate ? $this->maxdate->getTimestamp() : null,
            'macros' => $this->macros,
            'actions' => $this->actions,
            'default' => $this->default ?? null
        ];
    }
    public function getRawValue($label)
    {
        return Str::lower($label);
    }

    public function getFormatValue(string|int $value)
    {
        return Carbon::parse($value)->format('d.m.y');
    }
}
