<?php

namespace munkireport\models;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Capsule\Manager as Capsule;

class Hash extends Eloquent
{
    
    protected $fillable = ['serial_number', 'name', 'hash'];
    
    public function __construct($serial = '', $name = '')
    {
        
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => conf('application_path').'db/db.sqlite',
            'username' => conf('pdo_user'),
            'password' => conf('pdo_pass'),
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();


        return $this;
    }
}
