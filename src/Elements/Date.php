<?php
namespace Veneridze\LaravelForms\Elements;

class Date extends Text {
    public function toData($value): array {
        return [
            $this->label => $value
        ];
    }
}