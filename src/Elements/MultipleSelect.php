<?php
namespace Veneridze\LaravelForms\Elements;
use Illuminate\Support\Str;
use Veneridze\LaravelForms\Form;
use Veneridze\LaravelForms\Prototype\MultipleSelectFromList;

final class MultipleSelect extends MultipleSelectFromList
{
    public string $type = 'select';
    public bool $multiple = true;
    public function __construct(
        public string $key,
        public ?string $label = null,
        public bool $checkboxes = false,
        public bool $disabled = false,
        public array $options = [],
        public array $displayifset = [],
        public array $visibleif = [],
        public ?string $placeholder = null,
        public bool $required = false,
        public array $macros = [],
        public array $actions = [],
        public ?string $icon = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => 'select',
            'multiple' => true,
            'disabled' => $this->disabled,
            'required' => $this->required,
            'checkboxes' => $this->checkboxes,
            'label' => $this->label,
            'icon' => $this->icon,
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
        $values = explode(';', $label);
        $values = array_map('trim', $values);
        $values = array_map('mb_strtolower', $values);
        $values = array_map(fn($value) => Form::compareString($value), $values);
        return collect($this->options)
            ->filter(fn(Option $op) => in_array(Form::compareString(Str::lower($op->label)), $values))
            ->values()
            ->map(fn(Option $op) => $op->value)
            ->all();
    }



    public function validate($value): bool
    {

        if(is_string($value)) {
            $value = [$value];
        }

        $options = collect($this->options)->map(fn(Option $option) => $option->value)->all();

        foreach($value as $val) {
            if(!in_array($val, $options)) {
                return false;
            }
        }

        return true;
    }

    public function getFormatValue(array $values)
    {
        return collect($this->options)
            ->filter(fn(Option $op) => in_array($op->value, $values))
            ->values()
            ->map(fn(Option $op) => $op->label)
            ->implode(', ');
    }
}