<?php
namespace Veneridze\LaravelForms\Elements;
use Illuminate\Support\Str;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class Text extends Input implements Element
{
    public function __construct(
        public string $label,
        public string $key,
        public bool $disabled = false,
        public string $type = 'text',
        public array $visibleif = [],
        public array $displayifset = [],
        public ?string $prefix = null,
        public ?string $mask = null,
        public ?int $maxlength = null,
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
            'placeholder' => $this->placeholder,
            'label' => $this->label,
            'icon' => $this->icon,
            'disabled' => $this->disabled,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset
        ];
    }

    public function getRawValue($label)
    {
        return trim(Str::lower($label));
    }
}