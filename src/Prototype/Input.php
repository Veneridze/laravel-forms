<?php
namespace Veneridze\LaravelForms\Prototype;
use Illuminate\Contracts\Support\Arrayable;
use Veneridze\LaravelForms\Elements\Option;
use Veneridze\LaravelForms\Interfaces\Element;

class Input implements Element {

    public string $label;
    /**
     * Summary of options
     * @var array<Option>
     */
    
    public function visibleif(array $conditions): static {
        $this->visibleif = $conditions;
        return $this;
    }

    public function label(string $label): static {
        $this->label = $label;
        return $this;
    }

    public function key(string $key): static {
        $this->key = $key;
        return $this;
    }
    public function type(string $type): static {
        $this->type = $type;
        return $this;
    }

    /**
     * Summary of toData
     * @param array<int> $values
     * @return array
     */
    public function toData($value): array {
        return [
            $this->label => $value
        ];
    }

    public function toArray(): array {
        return [
            'type' => 'text',
            'label' => $this->label
        ];
    }
}