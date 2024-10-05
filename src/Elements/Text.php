<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;

class Text implements Element {
    public function __construct(
        public string $label,
        public string $key,
        public string $type = 'text',
        public array $visibleif = [],
        public string $icon = "",
        public null|string $mask = null,
    ) {}
    public function toData($value): array {
        return [
            $this->label => $value
        ];
    }
    public function toArray(): array {
        return [
            'type' => $this->type,
            'mask' => $this->mask,
            'label' => $this->label,
            'icon' => $this->icon,
            'key' => $this->key,
            'visibleif' => $this->visibleif
        ];
    }
}