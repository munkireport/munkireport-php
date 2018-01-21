<?php
namespace Mr\Network;

use Mr\Core\SerialNumberModel;

class Network extends SerialNumberModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    protected $table = 'network';

    protected $fillable = [
        'service',
        'order',
        'status',
        'ethernet',
        'clientid',
        'ipv4conf',
        'ipv4ip',
        'ipv4mask',
        'ipv4router',
        'ipv6conf',
        'ipv6ip',
        'ipv6prefixlen',
        'ipv6router'
    ];

    protected $casts = [
        'order' => 'integer',
        'ipv6prefixlen' => 'integer'
    ];
}