<?php
namespace Mr\Core\Scopes;

trait TimestampNewerThanScope {
    /**
     * Retrieve a list of objects where the `timestamp` date is at least $interval duration old.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \DateInterval $interval The duration that the row has not been updated for
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNewerThan($query, \DateInterval $interval) {
        $age = new \DateTime;
        $age->sub($interval);

        return $query->where('timestamp', '>', $age->getTimestamp());
    }
}