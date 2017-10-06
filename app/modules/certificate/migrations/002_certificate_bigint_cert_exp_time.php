<?php

// Sets the cert_exp_time colum to be BIGINT

class Migration_certificate_bigint_cert_exp_time extends \Model
{

    
    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'certificate';
    }

    public function up()
    {
        switch ($this->get_driver())
        {
            // Set column type
            case 'sqlite':
                     
                break;

            case 'mysql':
                
                    $sql = 'ALTER TABLE certificate CHANGE cert_exp_time cert_exp_time BIGINT';
                    $this->exec($sql);
                
                break;

            default:
                throw new Exception("Unknown Datbase driver", 1);
        }
    }

    public function down()
    {
        throw new Exception("Can't go back", 1);
    }
}
