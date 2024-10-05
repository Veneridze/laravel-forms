<?php
namespace Veneridze\LaravelForms\Prototype;
use Veneridze\LaravelForms\Elements\Option;
use Veneridze\LaravelForms\Interfaces\Element;

class SingleSelectFromList implements Element {

    public string $label;
    /**
     * Summary of options
     * @var array<Option>
     */
    public array $options;

    public function toData($value): array {
        $opt = array_filter($this->options,fn(Option $option): bool => $option->value == $value);
        return [
            $this->label => count($opt) == 1 ? $opt[0]->value : $value
        ];
    }

    public function options(array $data): static {
        $this->options = $data;
        return $this;
    }

    
    public function toArray(): array {
        return [
            'type' => 'select',
            'label' => $this->label,
            'options' => $this->options,
        ];
    }
}