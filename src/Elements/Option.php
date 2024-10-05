<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;

class Option {
    public function __construct(
        public string $label,
        public string $value,
        public array $availableif = []
    ) {}

    public function toArray(): array {
        return [
            'label' => $this->label,
            'value' => $this->value,
            'availableif' => $this->availableif
        ];
    }
}