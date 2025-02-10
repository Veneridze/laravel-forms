<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Prototype\MultipleSelectFromList;
use Veneridze\LaravelForms\UI\Card;

final class SearchSelect extends MultipleSelectFromList
{
    public string $type = 'searchselect';
    public function __construct(
        public string $label,
        public string $key,
        public string $link,
        public ?string $addLink = null,
        public ?array $fields = null,
        public ?bool $canSearch = false,
        public bool $emptyFetch = false,
        public bool $multiple = false,
        public array $linkIncludes = [],
        public array $visibleif = [],
        public array $displayifset = [],
        //public ?string $placeholder = null,
        //public ?string $icon = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => 'searchselect',
            'multiple' => $this->multiple,
            'emptyFetch' => $this->emptyFetch,
            'link' => $this->link,
            'fields' => $this->fields,
            'addLink' => $this->addLink,
            'linkIncludes' => $this->linkIncludes,
            'label' => $this->label,
            'canSearch' => $this->canSearch,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'displayifset' => $this->displayifset
        ];
    }
}