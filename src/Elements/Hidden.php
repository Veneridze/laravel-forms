<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
class Hidden implements Element {
    public function __construct(
        public string $label,
        public string $key,
        public string $value
    ) {}

    public function toData($value): array {
        return [
            $this->label => $this->value
        ];
    }
    
    public function toArray(): array {
        return [
            'type' => 'hidden',
            'key' => $this->key,
            'value' => $this->value
        ];
    }
}
?>