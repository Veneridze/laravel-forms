<?php
namespace Veneridze\LaravelForms\Elements;

final class Date extends Text {
    public function toData($value): array {
        return [
            $this->label => $value
        ];
    }
}