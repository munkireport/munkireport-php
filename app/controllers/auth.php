<?php

namespace munkireport\controller;

use \Controller, \View;
use munkireport\lib\Recaptcha;
use munkireport\lib\AuthHandler;
use munkireport\lib\AuthSaml;
use munkireport\lib\AuthConfig;
use munkireport\lib\AuthWhitelist;

class Auth extends Controller
{
    private $authHandler;
    
    public function __construct()
    {
        if (conf('auth_secure') && empty($_SERVER['HTTPS'])) {
            redirect('error/client_error/426'); // Switch protocol
        }
        
        $this->authHandler = new AuthHandler;        
    }

    //===============================================================

    public function index()
    {
        redirect('auth/login');
    }

    public function login($return = '')
    {
        if (func_get_args()) {
            $return_parts = func_get_args();
            $return = implode('/', $return_parts);
        }

        if ($this->authorized()) {
            redirect($return);
        }

        // If no valid mechanisms found, redirect to account generator
        if (! $this->authHandler->authConfigured()) {
            redirect('auth/generate');
        }
        
        $auth_verified = false;
        $pre_auth_failed = false;


        $login = post('login');
        $password = post('password');

        //check for recaptcha
        if (conf('recaptchaloginpublickey')) {
        //recaptcha enabled by admin; checking it
            if ($response = post('g-recaptcha-response')) {
                $recaptchaObj = new Recaptcha(conf('recaptchaloginprivatekey'));
                $remote_ip = getRemoteAddress();

                if (! $recaptchaObj->verify($response, $remote_ip)) {
                    error('', 'auth.captcha.invalid');
                    $pre_auth_failed = true;
                }
            } else {
                if ($_POST) {
                    error('', 'auth.captcha.empty');
                    $pre_auth_failed = true;
                }
            }
        }

       if(array_key_exists('network', conf('auth'))) {
           $authWhitelist = new AuthWhitelist;
           $authWhitelist->check_ip(getRemoteAddress());
       }

        // Check if pre-authentication is successful
        if (! $pre_auth_failed && $this->authHandler->login($login, $password)) {
            if($_SESSION['initialized']){
                redirect($return);
            }else{
                redirect('/system/show/database');
            }
        }

        // If POST and no other alerts, auth has failed
        if ($_POST && ! $GLOBALS['alerts']) {
            if (! $login or ! $password) {
                error('Empty values are not allowed', 'auth.empty_not_allowed');
            } else {
                error('Wrong username or password', 'auth.wrong_user_or_pass');
            }
        }

        $data = array('login' => $login, 'url' => url("auth/login/$return"));

        $obj = new View();
        $obj->view('auth/login', $data);
    }
    
    public function set_session_props($show = false)
    {
        // Initialize session
        $this->authorized();
        $props = $this->authHandler->setSessionProps();
        // Show current session info
        if ($show) {
            $obj = new View();
            $obj->view('json', array('msg' => $props));
        }
    }
    
    public function unauthorized($value='')
    {
        $obj = new View();
        $obj->view('auth/unauthorized', ['why' => $value]);
    }

    public function logout()
    {
        // Initialize session
        $this->authorized();
        
        // Check if saml
        if($_SESSION['auth'] == 'saml'){
            redirect('auth/saml/slo');
        }
        else{
            // Destroy session;
            session_destroy();
            redirect('');
        }
    }

    public function generate()
    {
        // Add a reason why generate is called
        $data = array('reason' => empty($this->auth_mechanisms) ? 'noauth' : 'none');

        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $data['login'] = isset($_POST['login']) ? $_POST['login'] : '';

        if ($_POST && (! $data['login'] or ! $password)) {
            error('Empty values are not allowed', 'auth.empty_not_allowed');
        }

        if ($data['login'] && $password) {
            $auth_config = new AuthConfig([]);
            $t_hasher = $auth_config->load_phpass();
            $data['generated_pwd'] = $t_hasher->HashPassword($password);
        }

        $obj = new View();
        $obj->view('auth/generate_password', $data);
    }
    
    
    public function saml($endpoint = 'sso')
    {        
        $saml_config = $this->authHandler->getConfig('saml');
        if($saml_config){
            $this->authorized();
            $saml_obj = new AuthSaml($saml_config, $this->authHandler);
            $saml_obj->handle($endpoint);
        }
        else{
            echo 'SAML not configured';
        }
    }
}
