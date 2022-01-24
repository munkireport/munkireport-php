<?php

namespace munkireport\models;

use munkireport\models\MRModel as Eloquent;

/**
 * Class Hash
 *
 * To be replaced by App\Hash
 *
 * @package munkireport\models
 */
class Hash extends Eloquent
{
    protected $table = 'hash';

    protected $fillable = [
        'serial_number',
        'name',
        'hash',
        'timestamp',
    ];
}
