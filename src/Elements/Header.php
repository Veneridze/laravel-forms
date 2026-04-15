<?php
namespace Veneridze\LaravelForms\Elements;
use Veneridze\LaravelForms\Interfaces\Element;
final class Header implements Element
{
    public string $type = 'header';
    public function __construct(
        public string $label,
        public array $actions = [],
        public int $size = 1
    ) {
    }

    public function __serialize(): array
    {
        return $this->toArray();
    }

    public function toData($value): array
    {
        return [
            $this->label => $value
        ];
    }

    public function toArray(): array
    {
        return [
            'type' => 'header',
            'label' => $this->label,
            'actions' => $this->actions,
            'size' => $this->size
        ];
    }

    public function getFormatValue(string|int $value)
    {
        return $value;
    }
}
?>