<?php
namespace Veneridze\LaravelForms\Elements;
use Illuminate\Support\Str;
use Veneridze\LaravelForms\Form;
use Veneridze\LaravelForms\Prototype\SingleSelectFromList;

final class RepeatableForm extends SingleSelectFromList
{
    public string $type = 'select';
    public function __construct(
        public string $key,
        public ?string $label = null,
        public bool $disabled = false,
        public ?int $max = null,
        public array $form = [],
        public array $visibleif = [],
        public bool $required = false,
        public array $actions = [],
        public ?string $icon = null
    ) {
    }
    
    public function toArray(): array
    {
        return [
            'type' => 'RepeatableForm',
            'disabled' => $this->disabled,
            'label' => $this->label,
            'icon' => $this->icon,
            'options' => $this->options,
            'required' => $this->required,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'max' => $this->max,
            'actions' => $this->actions,
            'form' => $this->form
        ];
    }

    public function getRawValue($label)
    {
        $opt = collect($this->options)->filter(fn(Option $op) => Form::compareString(Str::lower($op->label)) == Form::compareString(trim(Str::lower($label))))->first();
        return $opt ? $opt->value : null;
    }

    public function validate($value): bool {
        return in_array($value ,collect($this->options)->map(fn(Option $option) => $option->value)->all());
    }

    public function getFormatValue(string|int $value)
    {
        $opt = collect($this->options)->filter(fn(Option $op) => $op->value == $value)->first();
        return optional($opt)->label;
    }
}