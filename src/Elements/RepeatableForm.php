<?php
namespace Veneridze\LaravelForms\Elements;
use Illuminate\Support\Str;
use Veneridze\LaravelForms\Form;
use Veneridze\LaravelForms\Prototype\Input;

final class RepeatableForm extends Input
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
    }

    public function validate($value): bool {
        return is_array($value);
    }

    public function getFormatValue(string|int $value)
    {
    }
}