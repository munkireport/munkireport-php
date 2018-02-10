<?php
namespace Mr\ServerMetrics;

use Mr\Core\SerialNumberModel;

class ServerMetric extends SerialNumberModel
{
    protected $table = 'servermetrics';
    public $timestamps = false;
}
