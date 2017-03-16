<?php

namespace munkireport\lib;

/**
* Recaptcha class
*
*
*/
class Recaptcha
{
    
    private $url = 'https://www.google.com/recaptcha/api/siteverify';
    private $secret = '';
    public $json_result;

    public function __construct($secret)
    {
        $this->secret = $secret;
    }
    
    public function verify($recaptcharesponse, $userip)
    {

        //verifying recaptcha with google
        try {
            $data = array(
                'secret'   => $this->secret,
                'response' => $recaptcharesponse,
                'remoteip' => $userip,
            );
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                )
            );
            $context  = stream_context_create($options);
            $result = file_get_contents($this->url, false, $context);
            $this->json_result = json_decode($result);
            
            if ($this->json_result->success == 1) {
                return true;
            }
        } catch (Exception $e) {
            error($e->getMessage(), '');
        }
        
        return false;
    }
}
