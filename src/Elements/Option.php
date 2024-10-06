<?php
namespace Veneridze\LaravelForms\Elements;

final class Option {
    public function __construct(
        public ?string $label,
        public ?string $value,
        public array $availableif = []
    ) {}

    public function toArray(): array {
        return [
            'label' => $this->label ?? $this->value,
            'value' => $this->value,
            'availableif' => $this->availableif
        ];
    }
}