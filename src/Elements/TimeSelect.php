<?php
namespace Veneridze\LaravelForms\Elements;
use Illuminate\Support\Str;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class TimeSelect extends Input implements Element
{
    public string $type = 'time';
    public function __construct(
        public string $label,
        public string $key,
        public $step = null,
        public ?int $started = null,
        public ?int $ended = null,
        public bool $disabled = false,
        public array $visibleif = [],
        public array $displayifset = [],
        public bool $required = false,
        public ?string $placeholder = null
    ) {
    }
    public function toArray(): array
    {
        if ($this->step && is_numeric($this->step)) {
            $hours = $this->step / 60;
            $minutes = $this->step % 60;
        }
        return [
            'type' => 'time',
            'label' => $this->label,
            'key' => $this->key,
            'step' => $this->step ? "{$hours}:{$minutes}" : "00:01",
            'disabled' => $this->disabled,
            'required' => $this->required,
            'placeholder' => $this->placeholder,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset
        ];
    }

    public function getRawValue($label)
    {
        return trim(Str::lower($label));
    }
}