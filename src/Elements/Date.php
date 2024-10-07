<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

final class Date extends Input implements Element {
    public function toData($value): array {
        return [
            $this->label => $value ? 'Да' : 'Нет'
        ];
    }

    public function __construct(
        public string $label,
        public string $key,
        public string $type = 'date',
        public array $visibleif = [],
    ) {}
    public function toArray(): array {
        return [
            'type' => $this->type,
            'label' => $this->label,
            'key' => $this->key,
            'visibleif' => $this->visibleif
        ];
    }
}