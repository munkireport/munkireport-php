<?php
namespace MR\Kiss;
use MR\Kiss\Core\Controller as KISS_Controller;
use Illuminate\Database\Capsule\Manager as Capsule;

//===============================================================
// Controller
//===============================================================
class Controller extends KISS_Controller
{
    protected $capsule;

    protected function connectDB()
    {
        if(! $this->capsule){
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

    /**
     * Check if there is a valid session
     * and if the person is authorized for $what
     *
     * @return boolean TRUE on authorized
     * @author AvB
     **/
    public function authorized($what = '')
    {
        return authorized($what);
    }

    /**
     * Connect to database when authorized
     *
     * Create a database connection when user is authorized
     *
     * @return type
     * @throws conditon
     **/
    public function connectDBWhenAuthorized()
    {
        if($this->authorized()){
            $this->connectDB();
        }
    }
}

