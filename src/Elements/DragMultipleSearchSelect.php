<?php
namespace Veneridze\LaravelForms\Elements;
use Exception;
use Illuminate\Support\Collection;
use Veneridze\LaravelForms\Prototype\MultipleSelectFromList;
use Veneridze\LaravelForms\UI\Card;

final class DragMultipleSearchSelect extends MultipleSelectFromList
{
    public string $type = 'dragmultiplesearchselect';
    public function __construct(
        public string $label,
        public string $key,
        public string $link,
        public ?string $addLink = null,
        public ?array $fields = null,
        public bool $emptyFetch = false,
        public array $visibleif = [],
        public array $displayifset = [],
        public ?\Closure $format = null
        //public ?string $placeholder = null,
        //public ?string $icon = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => 'searchselect',
            'emptyFetch' => $this->emptyFetch,
            'link' => $this->link,
            'fields' => $this->fields,
            'addLink' => $this->addLink,
            'label' => $this->label,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset
        ];
    }
}