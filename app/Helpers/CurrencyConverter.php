<?php

namespace App\Helpers;


class CurrencyConverter
{
    public static function convertedTo($value, $conversionRate): string
    {

        $numericValue = (float) $value;
        $standardRate = $conversionRate->standard_rate;
        $convertedValTo = $numericValue * $standardRate;

        return $convertedValTo;
    }

    public static function convertedFrom($value, $conversionRate): string
    {

        $numericValue = (float) $value;
        $standardRate = $conversionRate->standard_rate;
        $convertedValfrom = $numericValue/ $standardRate;
        return $convertedValfrom;
    }
}
