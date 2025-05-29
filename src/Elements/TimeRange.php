<?php

namespace Veneridze\LaravelForms\Elements;

use Illuminate\Support\Str;
use Veneridze\LaravelForms\Interfaces\Element;
// use Carbon\Carbon;
use Veneridze\LaravelForms\Prototype\Input;

final class TimeRange extends Input implements Element
{
    public string $type = 'timerange';
    public function __construct(
        public string $label,
        public string $startKey,
        public string $endKey,
        public ?bool $disabled = false,
        public bool $required = false,
        public ?int $step = null,
        public ?string $placeholder = null,
        public ?array $visibleif = [],
        public array $displayifset = [],
    ) {
    }
    public function toArray(): array
    {
        if ($this->step) {
            $hours = floor($this->step / 60);
            $minutes = $this->step % 60;
        }
        return [
            'type' => 'timerange',
            'disabled' => $this->disabled,
            'required' => $this->required,
            'startKey' => $this->startKey,
            'label' => $this->label,
            'endKey' => $this->endKey,
            'visibleif' => $this->visibleif,
            'step' => $this->step ? "{$hours}:{$minutes}" : "00:01",
            'displayifset' => $this->displayifset,
        ];
    }

    public function getRawValue($label)
    {
        return trim(Str::lower($label));
    }
}
