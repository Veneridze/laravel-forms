<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class BulletList extends Input implements Element
{
    public string $type = 'bulletlist';
    public function __construct(
        public string $label,
        public string $key,
        public bool $disabled = false,
        public array $visibleif = [],
        public array $displayifset = [],
        public ?string $prefix = null,
        public ?string $mask = null,
        public ?int $maxlength = null,
        public ?int $max = null,
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
            'max' => $this->max,
            'placeholder' => $this->placeholder,
            'label' => $this->label,
            'icon' => $this->icon,
            'disabled' => $this->disabled,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset
        ];
    }
}