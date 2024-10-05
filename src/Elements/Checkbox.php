<?php
namespace Veneridze\LaravelForms\Elements;

class Checkbox extends Text {
    public function toData($value): array {
        return [
            $this->label => $value ? 'Да' : 'Нет'
        ];
    }
}