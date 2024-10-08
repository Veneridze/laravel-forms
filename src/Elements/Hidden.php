<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
final class Hidden implements Element {
    public function __construct(
        public string $label,
        public string $key,
        public string $value,
        public bool $disabled = false,
    ) {}

    public function toData($value): array {
        return [
            $this->label => $value
        ];
    }
    
    public function toArray(): array {
        return [
            'type' => 'hidden',
            'disabled' => $this->disabled,
            'key' => $this->key,
            'value' => $this->value
        ];
    }
}
?>