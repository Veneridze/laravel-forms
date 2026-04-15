<?php
namespace Veneridze\LaravelForms\Elements;
use Illuminate\Support\Str;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class Textarea extends Input implements Element
{
    public string $type = 'textarea';
    public function __construct(
        public string $key,
        public ?string $label = null,
        public bool $disabled = false,
        public bool $required = false,
        public array $visibleif = [],
        public array $displayifset = [],
        public ?string $prefix = null,
        public ?string $mask = null,
        public ?int $maxlength = null,
        public ?string $placeholder = null,
        public ?string $icon = null,
        public ?array $macros = [],
        public ?array $actions = [],
    ) {
    }
    public function toArray(): array
    {
        return [
            'type' => 'textarea',
            'mask' => $this->mask,
            'label' => $this->label,
            'icon' => $this->icon,
            'maxlength' => $this->maxlength,
            'required' => $this->required,
            'disabled' => $this->disabled,
            'placeholder' => $this->placeholder,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'macros' => $this->macros,
            'actions' => $this->actions,
            'displayifset' => $this->displayifset
        ];
    }


    public function validate($value): bool {
        if($this->maxlength) {
            return mb_strlen($value) <= $this->maxlength; 
        }
        return true;
    }
    public function getRawValue($label)
    {
        return trim(Str::lower($label));
    }

    public function getFormatValue(string|int $value)
    {
        return $value;
    }
}