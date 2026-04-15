<?php
namespace Veneridze\LaravelForms\Elements;
use Illuminate\Support\Str;
use Veneridze\LaravelForms\Form;
use Veneridze\LaravelForms\Prototype\SingleSelectFromList;

final class Select extends SingleSelectFromList
{
    public string $type = 'select';
    public function __construct(
        public string $key,
        public ?string $label = null,
        public bool $disabled = false,
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
    public function toTableData()
    {
        return collect($this->options)->pluck('label');
    }
    public function toArray(): array
    {
        return [
            'type' => 'select',
            'disabled' => $this->disabled,
            'label' => $this->label,
            'icon' => $this->icon,
            'options' => $this->options,
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