<?php

namespace Veneridze\LaravelForms\Normalizers;

use Spatie\LaravelData\Normalizers\Normalized\Normalized;
use Spatie\LaravelData\Normalizers\Normalizer;
class DataNormalizer implements Normalizer
{
    public function normalize(mixed $value): null|array|Normalized
    {
        if(is_object($value)) {
            $value = $value->toArray();
        }
        $array = $value;
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::normalize($value);
            } elseif (is_string($value) && is_numeric($value)) {
                // Определяем тип числа: целое или с плавающей точкой
                if (strpos($value, '.') !== false || strpos($value, 'e') !== false || strpos($value, 'E') !== false) {
                    $array[$key] = (float) $value;
                } else {
                    $array[$key] = (int) $value;
                }
            }
        }
        return $array;
    }
}
