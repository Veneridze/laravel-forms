<?php
namespace Veneridze\LaravelForms\Elements;
use Illuminate\Support\Str;
use Veneridze\LaravelForms\Prototype\Input;

final class Table extends Input
{
    public string $type = 'table';
    public function __construct(
        public string $label,
        public string $key,
        public bool $disabled = false,
        public array $columns = [],
        public array $visibleif = [],
        public array $displayifset = [],
        public bool $changeRows = true,
        public bool $required = false,
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => 'table',
            'label' => $this->label,
            'key' => $this->key,
            'disabled' => $this->disabled,
            'required' => $this->required,
            'columns' => $this->columns,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset,
            'changeRows' => $this->changeRows,
        ];
    }

    public function getRawValue($label)
    {
        return trim(Str::lower($label));
    }
}