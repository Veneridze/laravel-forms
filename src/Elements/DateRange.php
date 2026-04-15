<?php

namespace Veneridze\LaravelForms\Elements;

use Illuminate\Support\Str;
use Veneridze\LaravelForms\Interfaces\Element;
use Carbon\Carbon;
use Veneridze\LaravelForms\Prototype\Input;

final class DateRange extends Input implements Element
{
    public string $type = 'daterange';
    public function __construct(
        public string $startKey,
        public string $endKey,
        public ?string $label = null,
        public ?bool $disabled = false,
        public ?string $placeholder = null,
        public ?array $visibleif = [],
        public array $displayifset = [],
        public ?bool $holidays = true,
        public ?Carbon $mindate = null,
        public ?Carbon $maxdate = null,
        public array $macros = [],
        public array $actions = [],
        public bool $required = false
    ) {}
    public function toArray(): array
    {
        return [
            'type' => 'daterange',
            'required' => $this->required,
            'disabled' => $this->disabled,
            'label' => $this->label,
            'startKey' => $this->startKey,
            'endKey' => $this->endKey,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset,
            'holidays' => $this->holidays ?? true,
            'mindate' => $this->mindate ? $this->mindate->getTimestamp() : null,
            'macros' => $this->macros,
            'actions' => $this->actions,
            'maxdate' => $this->maxdate ? $this->maxdate->getTimestamp() : null,
            // 'default' => $this->default ?? null
        ];
    }


    public function validate($value): bool
    {

        if ($this->mindate || $this->maxdate) {

            $date =  Carbon::parse($value);
            if ($this->mindate && !$date->greaterThanOrEqualTo($this->mindate)) {
                return false;
            }

            if ($this->maxdate && !$date->lessThanOrEqualTo($this->maxdate)) {
                return false;
            }
            return true;
        }

        return true;
    }

    public function getRawValue($label)
    {
        return Str::lower($label);
    }
}
