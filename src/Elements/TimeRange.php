<?php

namespace Veneridze\LaravelForms\Elements;

use Veneridze\LaravelForms\Interfaces\Element;
use Carbon\Carbon;
use Veneridze\LaravelForms\Prototype\Input;

final class TimeRange extends Input implements Element
{
    // public function toData($value): array
    // {
    //     return [
    //         $this->label => $value ? 'Да' : 'Нет'
    //     ];
    // }

    public function __construct(
        public string $label,
        public string $startKey,
        public string $endKey,
        public ?bool $disabled = false,
        public ?string $placeholder = null,
        public ?array $visibleif = [],
        public array $displayifset = [],
        // public ?Carbon $mindate = null,
        // public ?Carbon $maxdate = null,
        // public ?string $default = null,
    ) {
    }
    public function toArray(): array
    {
        return [
            'type' => 'timerange',
            'disabled' => $this->disabled,
            'label' => $this->label,
            'startKey' => $this->startKey,
            'endKey' => $this->endKey,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset,
            // 'mindate' => $this->mindate ? $this->mindate->getTimestamp() : null,
            // 'maxdate' => $this->maxdate ? $this->maxdate->getTimestamp() : null,
            // 'default' => $this->default ?? null
        ];
    }
}
