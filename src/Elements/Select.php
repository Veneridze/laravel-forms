<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\SingleSelectFromList;

final class Select extends SingleSelectFromList {
    public function __construct(
        public string $label,
        public array $options,
        public string $key,
        public array $visibleif = [],
        public ?string $icon = null
    ) {}

    public function toArray(): array {
        return [
            'type' => 'select',
            'label' => $this->label,
            'icon' => $this->icon,
            'options' => $this->options,
            'key' => $this->key,
            'visibleif' => $this->visibleif
        ];
    }
}