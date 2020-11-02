<?php
namespace Mr\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;

class VueTableScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $params = Request::only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']);

        if (!empty($params['query'])) {
            if ($params['byColumn']) {
                $builder = $this->filterByColumn($builder, $params['query']);
            } else {
                $builder = $this->filter($builder, $params['query']);
            }
        }

        if (!empty($params['orderBy'])) {
            $builder = $this->orderBy($builder, $params['orderBy'], $params['ascending']);
        }

        $builder = $builder->limit($params['limit'])->skip($params['limit'] * ($params['page'] - 1));
    }

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
     * Filter using a general text query.
     * 
     * @param Builder $builder
     * @param array $query
     * @return Builder
     */
    protected function filter(Builder $builder, $query) {
        // TODO: model needs to indicate which fields are queried
        return $builder;
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