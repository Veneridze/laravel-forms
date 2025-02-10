<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

final class Checkbox extends Input implements Element
{
    public string $type = 'checkbox';
    public function toData($value): array
    {
        return [
            $this->label => $value ? 'Да' : 'Нет'
        ];
    }

    public function __construct(
        public string $label,
        public string $key,
        public bool $disabled = false,
        public ?string $placeholder = null,
        public array $visibleif = [],
        public array $displayifset = [],
    ) {
    }
    public function toArray(): array
    {
        return [
            'type' => 'checkbox',
            'disabled' => $this->disabled,
            'label' => $this->label,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset
        ];
    }
}