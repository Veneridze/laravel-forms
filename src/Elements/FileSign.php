<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
use Veneridze\LaravelForms\Prototype\Input;

class FileSign extends Input implements Element
{
    public string $type = 'filesign';
    public function __construct(
        public string $key,
        public ?string $label = null,
        public ?string $link = null,
        public ?string $source = 'link',
        public bool $attached = false,
        public bool $detached = true,
        public array $displayifset = [],
        public array $visibleif = [],
    ) {
    }
    public function toArray(): array
    {
        return [
            'type' => 'filesign',
            'source' => $this->source,
            'link' => $this->link,
            'label' => $this->label,
            'attached' => $this->attached,
            'detached' => $this->detached,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset
        ];
    }
}