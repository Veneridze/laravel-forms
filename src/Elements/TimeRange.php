<?php

namespace Veneridze\LaravelForms\Elements;

use Veneridze\LaravelForms\Interfaces\Element;
// use Carbon\Carbon;
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
        public ?int $step = null,
        public ?string $placeholder = null,
        public ?array $visibleif = [],
        public array $displayifset = [],
    ) {
    }
    public function toArray(): array
    {
        if ($this->step) {
            // $hours = substr("00" . ($this->step / 60), 0, 2);
            // $minutes = substr("00" . ($this->step % 60), 0, 2);
            $hours = $this->step / 60;
            $minutes = $this->step % 60;
        }
        return [
            'type' => 'timerange',
            'disabled' => $this->disabled,
            'label' => $this->label,
            'startKey' => $this->startKey,
            'endKey' => $this->endKey,
            'visibleif' => $this->visibleif,
            'step' => $this->step ? "{$hours}:{$minutes}" : "00:01",
            'displayifset' => $this->displayifset,
        ];
    }
}
