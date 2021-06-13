<?php

declare(strict_types=1);

namespace Domain\Formatters;

final class ResidentsAgeAverageToString
{
    public static function format(array $residentsByGender): string
    {
        $ageAverage = '';

        foreach ($residentsByGender as $gender => $ages) {
            $ageAverage .= sprintf(
                '%s: %f' . PHP_EOL,
                $gender,
                array_sum($ages) / sizeof($ages)
            );
        }

        return $ageAverage;
    }
}
