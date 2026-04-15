<?php
namespace Veneridze\LaravelForms\Elements;
use Illuminate\Support\Str;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class Text extends Input implements Element
{
    public function __construct(
        public string $key,
        public ?string $label = null,
        public bool $disabled = false,
        public bool $required = false,
        public string $type = 'text',
        public array $visibleif = [],
        public array $displayifset = [],
        public ?string $prefix = null,
        public ?string $mask = null,
        public ?string $postfix = null,
        public ?int $maxlength = null,
        public ?string $placeholder = null,
        public ?string $icon = null,
        public ?string $autocomplete = null,
        public ?array $macros = [],
        public ?array $actions = [],
    ) {
    }
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'mask' => $this->mask,
            'maxlength' => $this->maxlength,
            'placeholder' => $this->placeholder,
            'label' => $this->label,
            'icon' => $this->icon,
            'disabled' => $this->disabled,
            'required' => $this->required,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'prefix' => $this->prefix,
            'postfix' => $this->postfix,
            'displayifset' => $this->displayifset,
            'macros' => $this->macros,
            'actions' => $this->actions,
            'autocomplete' => $this->autocomplete
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