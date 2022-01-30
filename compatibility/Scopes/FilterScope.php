<?php

namespace Compatibility\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

/**
 * The FilterScope provides partial compatibility to models which inherit Compatibility\Capsule\MRModel and use
 * MRQueryBuilder to filter rows which are `archived` or not part of a selected machine group.
 *
 *
 * It can be removed when no models call $this->filter()
 * @see Compatibility\Capsule\Contracts\MRQueryBuilder
 */
trait FilterScope
{
    /**
     * Filter by archive status (default), based on session.
     *
     * If $what is 'groupOnly', filter by machine groups based on session and authz.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query Query builder
     * @param string $what empty string or 'groupOnly'
     */
    public function scopeFilter(Builder $query, string $what = ''): Builder
    {
        Log::critical("not implemented filter()");
        throw new \Exception("filter() scope not implemented");
        return $query;
    }
}
