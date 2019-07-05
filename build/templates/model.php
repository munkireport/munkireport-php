<?php

use munkireport\models\MRModel as Eloquent;

class MODULE_model extends Eloquent
{
    protected $table = 'MODULE';

    protected $fillable = [
      'serial_number',
      'item1',
      'item2',
    ];

    public $timestamps = false;

}
