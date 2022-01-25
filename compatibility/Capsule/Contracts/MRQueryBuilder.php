<?php

namespace Compatibility\Capsule\Contracts;

/**
 * This contract declares the interface required for making a model compatible with the Eloquent Capsule era query
 * builder that was modified in later versions of MunkiReport v5.
 */
interface MRQueryBuilder
{
    /**
     * Add a WHERE condition for the serial number to the current query, based on the primary
     * table name.
     *
     * @param string $serial_number
     * @return void
     */
    public function whereSerialNumber($serial_number);

    /**
     * Add filter conditions to the current query.
     *
     * By default, the filter removes items/machines that are marked as archived.
     * If the string 'groupOnly' is passed as the $what parameter, then data is further filtered
     * by machine groups.
     *
     * @param string $what 'groupOnly' (filter by machine group) or '' (filter archived)
     * @return MRQueryBuilder
     */
    public function filter($what = '');

    /**
     * SQLite helper: Insert values in chunks of 999 rows to satisfy upper limits.
     *
     * @param array $values Values to insert, array of rows
     * @param int $chunkSize The chunk size to use when inserting, if this is zero, the default 999 is used.
     * @return mixed bool True if values are empty, otherwise void.
     */
    public function insertChunked(array $values, int $chunkSize = 0);
}
