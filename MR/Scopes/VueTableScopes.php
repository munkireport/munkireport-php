<?php
namespace Mr\Scopes;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;

/**
 * VueTableScope allows you to limit queries based upon parameters passed by a vue-table-2 server table request.
 * This is the trait version of VueTableScope
 * 
 * @package Mr\Scopes
 */
trait VueTableScopes
{
    /**
     * Apply ordering to a single column in the query.
     *
     * @param Builder $builder The eloquent builder
     * @param string $orderBy The column to order by
     * @param int $ascending 1 if ascending, otherwise descending.
     * @return Builder modified builder
     */
    protected function orderBy(Builder $builder, $orderBy, $ascending = 0) {
        return $builder->orderBy($orderBy, $ascending ? "ASC" : "DESC");
    }

    /**
     * Filter by specific column(s).
     *
     * @param Builder $builder
     * @param array $query
     * @return Builder
     */
    protected function filterByColumn(Builder $builder, array $query) {
        foreach ($query as $field => $q) {
            if (!$q) continue;

            if (is_string($q)) {
                $builder->where($field, 'LIKE', "%{$q}%");
            } else {
                $start = Carbon::createFromFormat('Y-m-d',$query['start'])->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d',$query['end'])->endOfDay();

                $builder->whereBetween($field, [$start, $end]);
            }
        }

        return $builder;
    }
}