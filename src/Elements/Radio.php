<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\SingleSelectFromList;

class Radio extends SingleSelectFromList {
    public function __construct(
        public string $label,
        public string $style,
        public string $icon,
        public array $options,
        public string $key,
        public array $visibleif = []
    ) {}

    public function toData($value): array {
        $opt = array_filter($this->options,fn(Option $option): bool => $option->value == $value);
        return [
            $this->label => count($opt) == 1 ? $opt[0]->value : $value
        ];
    }
    
    public function toArray(): array {
        return [
            'type' => 'radio',
            'label' => $this->label,
            'style' => $this->style,
            'icon' => $this->icon,
            'options' => $this->options,
            'key' => $this->key,
            'visibleif' => $this->visibleif
        ];
    }
}