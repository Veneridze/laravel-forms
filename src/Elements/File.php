<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class File extends Input implements Element
{
    public function __construct(
        public string $label,
        public string $key,
        public ?string $accept = null,
        public bool $disabled = false,
        public array $displayifset = [],
        public array $visibleif = [],
    ) {
    }
    public function toArray(): array
    {
        return [
            'type' => 'file',
            'label' => $this->label,
            'accept' => $this->accept,
            'disabled' => $this->disabled,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset
        ];
    }
}