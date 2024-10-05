<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;

class Text implements Element {
    public function __construct(
        public string $type = 'text',
        public string $label,
        public string $icon,
        public string $key,
        public array $visibleif = []
    ) {}
    public function toData($value): array {
        return [
            $this->label => $value
        ];
    }
    public function toArray(): array {
        return [
            'type' => $this->type,
            'label' => $this->label,
            'icon' => $this->icon,
            'key' => $this->key,
            'visibleif' => $this->visibleif
        ];
    }
}