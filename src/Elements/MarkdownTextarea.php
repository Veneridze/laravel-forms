<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class MarkdownTextarea extends Input implements Element
{
    public string $type = 'markdown-textarea';
    public function __construct(
        public string $label,
        public string $key,
        // public bool $disabled = false,
        public array $visibleif = [],
        public array $displayifset = [],
        // public ?string $prefix = null,
        // public ?string $mask = null,
        // public ?int $maxlength = null,
        // public ?string $placeholder = null,
        // public ?string $icon = null
    ) {
    }
    public function toArray(): array
    {
        return [
            'type' => 'markdown-textarea',
            // 'mask' => $this->mask,
            'label' => $this->label,
            // 'icon' => $this->icon,
            // 'maxlength' => $this->maxlength,
            // 'disabled' => $this->disabled,
            // 'placeholder' => $this->placeholder,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset
        ];
    }
}