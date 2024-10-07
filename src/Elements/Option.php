<?php
namespace Veneridze\LaravelForms\Elements;

final class Option {
    public function __construct(
        public ?string $label,
        public mixed $value,
        public array $availableif = []
    ) {}

    public function toArray(): array {
        return [
            'label' => $this->label ?? $this->value,
            'value' => is_numeric($this->value) ? (int)$this->value : $this->value,
            'availableif' => $this->availableif
        ];
    }
}