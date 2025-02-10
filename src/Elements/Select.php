<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\SingleSelectFromList;

final class Select extends SingleSelectFromList
{
    public string $type = 'select';
    public function __construct(
        public string $label,
        public string $key,
        public bool $disabled = false,
        public array $options = [],
        public array $visibleif = [],
        public array $displayifset = [],
        public ?string $placeholder = null,
        public ?string $icon = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => 'select',
            'disabled' => $this->disabled,
            'label' => $this->label,
            'icon' => $this->icon,
            'options' => $this->options,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset
        ];
    }
}