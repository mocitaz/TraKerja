<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

final class DatabaseDateExpressions
{
    public static function month(string $column): string
    {
        $column = DB::connection()->getQueryGrammar()->wrap($column);

        return DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', {$column})"
            : "DATE_FORMAT({$column}, '%Y-%m')";
    }

    public static function hour(string $column): string
    {
        $column = DB::connection()->getQueryGrammar()->wrap($column);

        return DB::connection()->getDriverName() === 'sqlite'
            ? "CAST(strftime('%H', {$column}) AS INTEGER)"
            : "HOUR({$column})";
    }

    public static function wholeHoursBetween(string $start, string $end): string
    {
        $grammar = DB::connection()->getQueryGrammar();
        $start = $grammar->wrap($start);
        $end = $grammar->wrap($end);

        return DB::connection()->getDriverName() === 'sqlite'
            ? "CAST((strftime('%s', {$end}) - strftime('%s', {$start})) / 3600 AS INTEGER)"
            : "TIMESTAMPDIFF(HOUR, {$start}, {$end})";
    }

    public static function fractionalHoursBetween(string $start, string $end): string
    {
        $grammar = DB::connection()->getQueryGrammar();
        $start = $grammar->wrap($start);
        $end = $grammar->wrap($end);

        return DB::connection()->getDriverName() === 'sqlite'
            ? "((julianday({$end}) - julianday({$start})) * 24.0)"
            : "(TIMESTAMPDIFF(SECOND, {$start}, {$end}) / 3600.0)";
    }
}
