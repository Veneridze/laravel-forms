<?php

namespace Veneridze\LaravelForms\Elements;

use Veneridze\LaravelForms\Interfaces\Element;
use Carbon\Carbon;
use Veneridze\LaravelForms\Prototype\Input;

final class DateRange extends Input implements Element
{
    public function __construct(
        public string $label,
        public string $startKey,
        public string $endKey,
        public ?bool $disabled = false,
        public ?string $placeholder = null,
        public ?array $visibleif = [],
        public array $displayifset = [],
        public ?bool $holidays = true,
        public ?Carbon $mindate = null,
        public ?Carbon $maxdate = null,
        // public ?string $default = null,
    ) {
    }
    public function toArray(): array
    {
        return [
            'type' => 'daterange',
            'disabled' => $this->disabled,
            'label' => $this->label,
            'startKey' => $this->startKey,
            'endKey' => $this->endKey,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset,
            'holidays' => $this->holidays ?? true,
            'mindate' => $this->mindate ? $this->mindate->getTimestamp() : null,
            'maxdate' => $this->maxdate ? $this->maxdate->getTimestamp() : null,
            // 'default' => $this->default ?? null
        ];
    }
}
