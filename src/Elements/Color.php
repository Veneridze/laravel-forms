<?php
namespace Veneridze\LaravelForms\Elements;
use Illuminate\Support\Str;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class Color extends Input implements Element
{
    public string $type = 'color';
    public function __construct(
        public ?string $label,
        public string $key,
        public string $format = 'hsl',
        public bool $alpha = false,
        public bool $disabled = false,
        public bool $required = false,
        public array $visibleif = [],
        public array $macros = [],
        public array $actions = [],
        public array $displayifset = [],

    ) {
    }
    public function toArray(): array
    {
        return [
            'type' => 'color',
            'label' => $this->label,
            'format' => $this->format,
            'alpha' => $this->alpha,
            'disabled' => $this->disabled,
            'required' => $this->required,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'macros' => $this->macros,
            'actions' => $this->actions,
            'displayifset' => $this->displayifset
        ];
    }

    public function getRawValue($label)
    {
        return trim(Str::lower($label));
    }

    public function getFormatValue($value)
    {
        return $value;
    }
}