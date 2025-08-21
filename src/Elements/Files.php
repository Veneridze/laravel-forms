<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class Files extends Input implements Element
{
    public string $type = 'files';
    public function __construct(
        public string $label,
        public string $key,
        public ?string $accept = null,
        public int $limit = null,
        public bool $disabled = false,
        public array $displayifset = [],
        public array $visibleif = [],
        public bool $required = false
    ) {
    }
    public function toArray(): array
    {
        return [
            'type' => 'files',
            'required' => $this->required,
            'label' => $this->label,
            'accept' => $this->accept,
            'disabled' => $this->disabled,
            'key' => $this->key,
            'limit' => $this->limit,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset
        ];
    }
}