<?php
namespace Veneridze\LaravelForms\Attributes;
class Name
{
    const VALUE = 'value';

    public $value;

    public function __construct($value = null)
    {
        $this->value = $value;
    }
}