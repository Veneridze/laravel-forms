<?php

namespace Veneridze\LaravelForms\Elements;

use Illuminate\Support\Str;
use Veneridze\LaravelForms\Form;
use Veneridze\LaravelForms\Prototype\SingleSelectFromList;

final class Radio extends SingleSelectFromList
{
    public string $type = 'radio';
    public function __construct(
        public string $key,
        public ?string $label = null,
        public ?string $style = null,
        public bool $disabled = false,
        public bool $vertical = false,
        public array $options = [],
        public array $visibleif = [],
        public array $displayifset = [],
        public ?string $placeholder = null,
        public bool $required = false,
        public array $macros = [],
        public array $actions = [],
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
            'vertical' => $this->vertical,
            'options' => $this->options,
            'key' => $this->key,
            'displayifset' => $this->displayifset,
            'macros' => $this->macros,
            'actions' => $this->actions,
            'visibleif' => $this->visibleif
        ];
    }
    public function getRawValue($label)
    {
        $opt = collect($this->options)->filter(fn(Option $op) => Form::compareString(Str::lower($op->label)) == Form::compareString(trim(Str::lower($label))))->first();
        return $opt ? $opt->value : null;
    }

    public function validate($value): bool
    {
        return in_array($value, collect($this->options)->map(fn(Option $option) => $option->value)->all());
    }
    public function getFormatValue(string|int $value)
    {
        $opt = collect($this->options)->filter(fn(Option $op) => $op->value == $value)->first();
        return optional($opt)->label;
    }
}
