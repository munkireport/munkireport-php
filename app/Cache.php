<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * This model provides a cache value eg. to supported_os which retrieves the latest supported operating system.
 *
 * @OA\Schema(
 *  schema="Cache",
 *  description="Basic k/v cache table",
 *  type="object",
 *  @OA\Property(
 *   property="module",
 *   type="string",
 *   description="The module that the value is cached for",
 *   example="supported_os",
 *  ),
 *  @OA\Property(
 *   property="property",
 *   type="string",
 *   description="The property name being cached",
 *   example="current_os",
 *  ),
 *  @OA\Property(
 *   property="value",
 *   type="string",
 *   description="The cached value",
 *  ),
 *  @OA\Property(
 *   property="timestamp",
 *   type="integer",
 *   format="epoch",
 *   description="The unix epoch timestamp when the value was last cached",
 *  ),
 * )
 */
class Cache extends Model
{
    protected $table = 'cache';
    public $timestamps = false;

    protected $fillable = [
        'module',
        'property',
        'value',
        'timestamp',
    ];
}
