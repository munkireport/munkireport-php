<?php
namespace Mr\Core\Scopes;

/**
 * This is a pseudo global scope to retrieve objects that have been updated between two DateIntervals subtracted
 * from the current datetime.
 *
 * NOTE: This was not implemented as a global scope object, since they cannot take parameters.
 *
 * @package Mr\Scopes
 */
trait UpdatedBetweenScope
{
    /**
     * Retrieve a list of models where the `updated_at` date is between two durations in age.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \DateInterval $minAge The minimum age of the record
     * @param \DateInterval $minAge The maximum age of the record
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpdatedBetween($query, \DateInterval $minAge, \DateInterval $maxAge) {
        $minAgeDate = new \DateTime();
        $minAgeDate->sub($minAge);
        $maxAgeDate = new \DateTime();
        $maxAgeDate->sub($maxAge);

        return $query->whereBetween('updated_at', [$minAgeDate, $maxAgeDate]);
    }
}