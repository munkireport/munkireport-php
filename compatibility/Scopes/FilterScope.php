<?php

namespace Compatibility\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

/**
 * The FilterScope provides partial compatibility to models which inherit Compatibility\Capsule\MRModel and use
 * MRQueryBuilder to filter rows which are `archived` or not part of a selected machine group.
 *
 * It can be removed when no models call $this->filter().
 *
 * NOTE: if you don't do a join/with('reportdata') on your model, this will be unusable, and the scope doesn't enforce
 *       this constraint.
 *
 * @see \Compatibility\Capsule\Contracts\MRQueryBuilder
 * @see \Compatibility\Capsule\MRQueryBuilder
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
        // For this WHERE IN query to work, the query has to be joined to report_data already.
        if ($groups = get_filtered_groups()) {
            $query->whereIn('machine_group', $groups);
        }

        if ($what != 'groupOnly') {
            if (is_archived_filter_on()) {
                $query->where('reportdata.archive_status', 0);
            } elseif (is_archived_only_filter_on()) {
                $query->where('reportdata.archive_status', '!=', 0);
            }
        }

        return $query;
    }
}
