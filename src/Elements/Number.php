<?php
namespace Veneridze\LaravelForms\Elements;
use Illuminate\Support\Str;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class Number extends Input implements Element
{
    public function __construct(
        public string $key,
        public ?string $label = null,
        public bool $disabled = false,
        public string $type = 'number',
        public array $visibleif = [],
        public array $displayifset = [],
        public ?string $prefix = null,
        public ?string $postfix = null,
        public bool $required = false,
        public ?int $max = null,
        public ?int $min = null,
        public ?string $placeholder = null,
        public array $macros = [],
        public array $actions = [],
        public ?string $icon = null
    ) {
    }
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'required' => $this->required,
            'max' => $this->max,
            'min' => $this->min,
            'placeholder' => $this->placeholder ?? match (true) {
                ($this->max && $this->min) => "от {$this->min} до {$this->max}",
                $this->min => "> {$this->min}",
                $this->max => "< {$this->max}",
                default => null
            },
            'label' => $this->label,
            'icon' => $this->icon,
            'disabled' => $this->disabled,
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


    public function validate($value): bool
    {

            if ($this->min && $value < $this->min) {
                return false;
            }

            if ($this->max && $value > $this->max) {
                return false;
            }
            return true;
    }


    public function getFormatValue(string|int $value)
    {
        return $value;
    }
}