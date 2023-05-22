<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     description="The hash model is used by MunkiReport PHP to calculate whether information submitted by a machine needs to be updated or not",
 *     @OA\Property(
 *      property="id",
 *      type="integer",
 *      description="Unique Identifier",
 *     ),
 *     @OA\Property(
 *      property="serial_number",
 *      type="string",
 *      description="The serial number of the machine that submitted the data",
 *     ),
 *     @OA\Property(
 *      property="name",
 *      type="string",
 *      description="The module name that the data was collected for",
 *     ),
 *     @OA\Property(
 *      property="hash",
 *      type="string",
 *      description="Hash digest of data that was sent by the client",
 *     ),
 *     @OA\Property(
 *      property="timestamp",
 *      type="integer",
 *      description="unix epoch formatted timestamp of the datetime when the data was last collected",
 *     ),
 * )
 */
class Hash extends Model
{
    protected $table = 'hash';
    public $timestamps = false;

    protected $fillable = [
        'serial_number',
        'name',
        'hash',
        'timestamp',
    ];
}
