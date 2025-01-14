<?php
namespace Veneridze\LaravelForms\UI;
use Illuminate\Contracts\Support\Arrayable;
//use Veneridze\LaravelForms\Prototype\MultipleSelectFromList;

final class Card implements Arrayable
{
    public function __construct(
        public int $id,
        public string $label,
        public ?string $icon = null,
        public ?array $features = null,
        public ?array $actions = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'icon' => $this->icon,
            'features' => $this->features,
            'actions' => $this->actions
        ];
    }
}