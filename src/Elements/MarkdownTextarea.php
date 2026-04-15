<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class MarkdownTextarea extends Input implements Element
{
    public string $type = 'markdown-textarea';
    public function __construct(
        public string $key,
        public ?string $label = null,
        public array $visibleif = [],
        public bool $required = false,
        public array $macros = [],
        public array $actions = [],
        public array $displayifset = [],
    ) {
    }
    public function toArray(): array
    {
        return [
            'type' => 'markdown-textarea',
            'label' => $this->label,
            'required' => $this->required,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'macros' => $this->macros,
            'actions' => $this->actions,
            'displayifset' => $this->displayifset
        ];
    }

    public function getFormatValue(string|int $value)
    {
        return $value;
    }
}