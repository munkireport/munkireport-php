<?php
namespace Mr\Extensions;

use Mr\Core\SerialNumberModel;

class Extension extends SerialNumberModel
{
    protected $table = 'extensions';
    public $timestamps = false;
}
