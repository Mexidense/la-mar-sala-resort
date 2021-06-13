<?php

declare(strict_types=1);

namespace Domain\Formatters;

use Domain\Models\Stringable;

final class ArrayToString
{
    public static function format(array $elements): string
    {
        $elementsInString = '';
        foreach ($elements as $element) {
            if ($element instanceof Stringable) {
                $elementsInString .= $element->__toString() . PHP_EOL;
            }
        }

        return $elementsInString;
    }
}
