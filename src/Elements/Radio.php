<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\SingleSelectFromList;

final class Radio extends SingleSelectFromList {
    public function __construct(
        public string $label,
        public string $style,
        public string $key,
        public array $options = [],
        public array $visibleif = [],
        public ?string $icon = null
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