<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Prototype\Input;

final class Table extends Input
{
    public function __construct(
        public string $label,
        public string $key,
        public bool $disabled = false,
        public array $columns = [],
        public array $visibleif = [],
        public array $displayifset = [],
        public bool $changeRows = true,
        //public array $options = [],
        //public ?string $placeholder = null,
        //public ?string $icon = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => 'table',
            'label' => $this->label,
            'key' => $this->key,
            'disabled' => $this->disabled,
            'columns' => $this->columns,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset,
            'changeRows' => $this->changeRows,
            //'icon' => $this->icon,
            //'options' => $this->options,
        ];
    }
}