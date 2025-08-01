<?php
namespace Veneridze\LaravelForms\Elements;
use Illuminate\Support\Str;
use Veneridze\LaravelForms\Form;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\SingleSelectFromList;

final class Radio extends SingleSelectFromList
{
    public string $type = 'radio';
    public function __construct(
        public string $label,
        public string $key,
        public ?string $style = null,
        public array $columns = [],
        public bool $disabled = false,
        public array $options = [],
        public array $visibleif = [],
        public array $displayifset = [],
        public ?string $placeholder = null,
        public bool $required = false,
        public ?string $icon = null
    ) {
    }

    public function toData($value): array
    {
        $opt = array_filter($this->options, fn(Option $option): bool => $option->value == $value);
        return [
            $this->label => count($opt) == 1 ? $opt[0]->value : $value
        ];
    }

    public function toArray(): array
    {
        return [
            'type' => 'radio',
            'disabled' => $this->disabled,
            'required' => $this->required,
            'label' => $this->label,
            'style' => $this->style,
            'icon' => $this->icon,
            'options' => $this->options,
            'key' => $this->key,
            'displayifset' => $this->displayifset,
            'visibleif' => $this->visibleif
        ];
    }
    public function getRawValue($label)
    {
        $opt = collect($this->options)->filter(fn(Option $op) => Form::compareString(Str::lower($op->label)) == Form::compareString(trim(Str::lower($label))))->first();
        return $opt ? $opt->value : null;
    }
}