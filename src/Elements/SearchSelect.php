<?php
namespace Veneridze\LaravelForms\Elements;
use Illuminate\Support\Collection;
use Veneridze\LaravelForms\Prototype\MultipleSelectFromList;

final class SearchSelect extends MultipleSelectFromList
{
    public string $type = 'searchselect';
    public function __construct(
        public string $key,
        public string $link,
        public ?string $label = null,
        public ?string $addLink = null,
        public ?array $fields = null,
        public ?bool $canSearch = false,
        public bool $emptyFetch = false,
        public bool $multiple = false,
        public array $linkIncludes = [],
        public array $visibleif = [],
        public array $displayifset = [],
        public array $macros = [],
        public array $actions = [],
        public ?\Closure $tableData = null,
        public bool $required = false
    ) {
    }
    public function toTableData(): Collection
    {
        $tableData = $this->tableData;
        // if ($tableData) {
        //     throw new Exception(json_encode($tableData(), JSON_UNESCAPED_UNICODE));
        // }
        return $tableData ? $tableData() : collect();
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
            'required' => $this->required,
            'label' => $this->label,
            'canSearch' => $this->canSearch,
            'key' => $this->key,
            'visibleif' => $this->visibleif,
            'macros' => $this->macros,
            'actions' => $this->actions,
            'displayifset' => $this->displayifset
        ];
    }
}