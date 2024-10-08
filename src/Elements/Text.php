<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class Text extends Input implements Element {
    public function __construct(
        public string $label,
        public string $key,
        public bool $disabled = false,
        public string $type = 'text',
        public array $visibleif = [],
        public null|string $mask = null,
        public ?int $maxlength = null,
        public ?string $placeholder = null,
        public ?string $icon = null
    ) {}
    public function toArray(): array {
        return [
            'type' => $this->type,
            'mask' => $this->mask,
            'label' => $this->label,
            'icon' => $this->icon,
            'disabled' => $this->disabled,
            'key' => $this->key,
            'visibleif' => $this->visibleif
        ];
    }
}