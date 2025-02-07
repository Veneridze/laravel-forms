<?php
namespace Veneridze\LaravelForms\Elements;

final class Option
{
    public function __construct(
        public ?string $label,
        public mixed $value,
        public bool $disabled = false,
        public array $availableif = []
    ) {
    }

    public function __serialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'label' => $this->label ?? $this->value,
            'disabled' => $this->disabled,
            'value' => is_numeric($this->value) ? (int) $this->value : $this->value,
            'availableif' => $this->availableif
        ];
    }
}