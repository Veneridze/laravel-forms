<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class TimeSelect extends Input implements Element
{
    public function __construct(
        public string $label,
        public string $key,
        public int $step = 0,
        public bool $disabled = false,
        public array $visibleif = [],
        public array $displayifset = [],
        // public ?string $prefix = null,
        // public ?string $mask = null,
        // public ?int $maxlength = null,
        // public ?string $placeholder = null,
        // public ?string $icon = null
    ) {
    }
    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'key' => $this->key,
            'step' => $step,
            'disabled' => $this->disabled,
            'type' => 'time',
            // 'mask' => $this->mask,
            // 'icon' => $this->icon,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset
        ];
    }
}