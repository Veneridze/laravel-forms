<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class Number extends Input implements Element
{
    public function __construct(
        public string $label,
        public string $key,
        public bool $disabled = false,
        public string $type = 'number',
        public array $visibleif = [],
        public array $displayifset = [],
        public ?string $prefix = null,
        public ?string $postfix = null,
        public ?int $max = null,
        public ?int $min = null,
        public ?string $placeholder = null,
        public ?string $icon = null
    ) {
    }
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'mask' => $this->mask,
            'maxlength' => $this->maxlength,
            'placeholder' => $this->placeholder ?? match (true) {
                ($this->max && $this->min) => "от {$min} до {$max}",
                $this->min => "> {$min}",
                $this->max => "< {$max}",
                default => null
            },
            'label' => $this->label,
            'icon' => $this->icon,
            'disabled' => $this->disabled,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset
        ];
    }
}