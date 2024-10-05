<?php
namespace Veneridze\LaravelForms\Interfaces;
use Illuminate\Contracts\Support\Arrayable;
interface Element extends Arrayable {
    public function toData($value): array;
}