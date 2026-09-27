<?php

namespace App\Support;

/**
 * Writes numbers the way the visitor's language writes them.
 *
 * Prices stay in lira whichever language is showing — the atelier is in İzmir
 * and charges in lira — but "1.150,00" reads as a fraction to someone on the
 * English pages, so the separators follow the locale rather than the currency.
 */
class Format
{
    public static function number(float $value, int $decimals = 2): string
    {
        return app()->getLocale() === 'tr'
            ? number_format($value, $decimals, ',', '.')
            : number_format($value, $decimals, '.', ',');
    }

    public static function price(float $value, int $decimals = 2): string
    {
        return self::number($value, $decimals).' ₺';
    }
}
