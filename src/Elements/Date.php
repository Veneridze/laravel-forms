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
        public ?string $type = 'date',
        public ?bool $disabled = false,
        public ?string $placeholder = null,
        public ?array $visibleif = [],
        public ?bool $holidays = true,
        public ?int $mindate = null,
        public ?string $default = null,
    ) {}
    public function toArray(): array {
        return [
            'type' => $this->type,
            'disabled' => $this->disabled,
            'label' => $this->label,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'holidays' => $this->holidays ?? true,
            'mindate' => $this->mindate ?? null,
            'default' => $this->default ?? null
        ];
    }
}