<?php


namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * ProvidesHistogram trait
 *
 * Use this trait on Eloquent models which should be able to provide a histogram of a column by raw frequency, or
 * using a set of CASE ... WHEN statements to provide a "bucketed" histogram which contains ranges or other types of
 * conditions.
 *
 * @package App
 */
trait ProvidesHistogram
{

    //// CUSTOM

    /**
     * Retrieve a histogram (frequency) of values for the given column name.
     *
     * @param string $column The name of the column to count the number of rows for.
     * @return Builder The query builder, for further filtering, or sorting of values. The count of objects per-value is
     *                 returned in the `count` attribute.
     */
    public static function histogram(string $column): Builder {
        return static::query()->select($column, DB::raw('COUNT(*) as count'))
            ->groupBy($column);
    }

    /**
     * Retrieve a histogram (frequency) of values, given a list of CASE ... WHEN expressions.
     *
     * Returns a single row containing each case total as a column, including a `total` column
     * indicating the total number of rows evaluated.
     *
     * Example:
     * Model::histogramByCase([
     *  'col1' => 'COUNT(CASE WHEN timestamp > 1 THEN 1 END)',
     *  'col2' => 'COUNT(CASE WHEN timestamp BETWEEN 2 AND 3 THEN 1 END)',
     * ])
     *
     * @param array $cases
     * @return Builder
     */
    public static function histogramByCase(array $cases): Builder
    {
        $query = static::query()->select(DB::raw('COUNT(*) as total'));
        foreach ($cases as $alias => $case) {
            $query = $query->addSelect(DB::raw("{$case} AS {$alias}"));
        }

        return $query;
    }
}
