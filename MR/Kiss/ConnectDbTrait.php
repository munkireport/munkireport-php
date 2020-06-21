<?php
namespace MR\Kiss;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * This trait implements the connectDB() function returning a Capsule instance for old-style controllers that expect
 * $this->connectDB() to work.
 *
 * @package MR\Kiss
 */
trait ConnectDbTrait
{
    protected $capsule;

    protected function connectDB()
    {
        if (!$this->capsule) {
            $this->capsule = new Capsule;

            if( ! $connection = conf('connection')){
                die('Database connection not configured');
            }

            if(has_mysql_db($connection)){
                add_mysql_opts($connection);
            }

            $this->capsule->addConnection($connection);
            $this->capsule->setAsGlobal();
            $this->capsule->bootEloquent();
        }

        return $this->capsule;
    }
}
