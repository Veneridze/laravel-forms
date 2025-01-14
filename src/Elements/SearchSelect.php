<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Prototype\MultipleSelectFromList;
use Veneridze\LaravelForms\UI\Card;

final class SearchSelect extends MultipleSelectFromList
{
    public function __construct(
        public string $label,
        public string $key,
        public string $link,
        public ?string $addLink = null,
        public ?array $fields = null,
        public bool $emptyFetch = false,
        public bool $multiple = false,
        public array $linkIncludes = [],
        public array $visibleif = [],
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
            //'disabled' => $this->disabled,
            'label' => $this->label,
            //'icon' => $this->icon,
            //'options' => $this->options,
            'key' => $this->key,
            'visibleif' => $this->visibleif
        ];
    }
}