<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Prototype\MultipleSelectFromList;

final class MultipleSelect extends MultipleSelectFromList {
    public function __construct(
        public string $label,
        public string $key,
        public array $options = [],
        public array $visibleif = [],
        public ?string $icon = null
    ) {}

    public function toArray(): array {
        return [
            'type' => 'select',
            'multiple' => true,
            'label' => $this->label,
            'icon' => $this->icon,
            'options' => $this->options,
            'key' => $this->key,
            'visibleif' => $this->visibleif
        ];
    }
}