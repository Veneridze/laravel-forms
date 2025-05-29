<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class TimeSelect extends Input implements Element
{
    public string $type = 'time';
    public function __construct(
        public string $label,
        public string $key,
        public ?int $step = null,
        public ?int $started = null,
        public ?int $ended = null,
        public bool $disabled = false,
        public array $visibleif = [],
        public array $displayifset = [],
        // public ?string $prefix = null,
        // public ?string $mask = null,
        // public ?int $maxlength = null,
        public ?string $placeholder = null,
        // public ?string $icon = null
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
            'label' => $this->label,
            'key' => $this->key,
            'step' => $this->step ? "{$hours}:{$minutes}" : "00:01",
            'disabled' => $this->disabled,
            'type' => 'time',
            // 'mask' => $this->mask,
            // 'icon' => $this->icon,
            'placeholder' => $this->placeholder,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset
        ];
    }

    public function getRawValue($label)
    {
        return trim($label);
    }
}