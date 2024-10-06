<?php
namespace Veneridze\LaravelForms\Elements;

final class Checkbox extends Text {
    public function toData($value): array {
        return [
            $this->label => $value ? 'Да' : 'Нет'
        ];
    }
}