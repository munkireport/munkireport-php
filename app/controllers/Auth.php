<?php

namespace munkireport\controller;

use Illuminate\Support\Str;
use MR\Kiss\Controller;
use MR\Kiss\View;
use munkireport\lib\Recaptcha;
use munkireport\lib\AuthHandler;
use munkireport\lib\AuthSaml;
use munkireport\lib\AuthLocal;
use munkireport\lib\AuthWhitelist;

class Auth extends Controller
{
    private $authHandler;

    public function __construct()
    {
        if (conf('auth_secure') && ! request()->secure()) {
            mr_redirect('error/client_error/426'); // Switch protocol
        }

        $this->connectDB();

        $this->authHandler = new AuthHandler;
    }

    //===============================================================

    public function index()
    {
        mr_redirect('auth/login');
    }

    public function login($return = '')
    {
        if (func_get_args()) {
            $return_parts = func_get_args();
            $return = implode('/', $return_parts);
        }

        if ($this->authorized()) {
            mr_redirect($return);
        }

        // If no valid mechanisms found, redirect to account generator
        if (! $this->authHandler->authConfigured()) {
            mr_redirect('auth/unavailable');
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
           $authWhitelist = new AuthWhitelist(conf('auth')['network']);
           $authWhitelist->check_ip(getRemoteAddress());
       }

        // Check if pre-authentication is successful
        if (! $pre_auth_failed && $this->authHandler->login($login, $password)) {

            // Set CSRF token for this session
            $_SESSION['csrf_token'] = Str::random(40);

            // Add token to cookie
            setcookie (
                "CSRF-TOKEN", // name
                $_SESSION['csrf_token'], //value
                time()+60, // expires
                conf('subdirectory'), // path
                "", // domain
                request()->secure(), // secure
                false // httponly
            );

            if($_SESSION['initialized']){
                mr_redirect($return);
            }else{
                mr_redirect('/system/show/database');
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

        $data = [
            // Prevent XSS
            'login' => htmlspecialchars($login, ENT_QUOTES, 'UTF-8'),
            'url' => mr_url("auth/login/$return")
        ];

        mr_view('auth/login', $data);
    }

    public function set_session_props($show = false)
    {
        // Initialize session
        $this->authorized();
        $props = $this->authHandler->setSessionProps();
        // Show current session info
        if ($show) {
            jsonView($props);
        }
    }

    public function unauthorized($value='')
    {
        mr_view('auth/unauthorized', ['why' => $value]);
    }

    public function unavailable()
    {
        mr_view('auth/unavailable');
    }

    public function logout()
    {
        // Initialize session
        $this->authorized();

        // Check if saml
        if(isset($_SESSION['auth']) && $_SESSION['auth'] == 'saml'){
            mr_redirect('auth/saml/slo');
        }
        else{
            // Destroy session;
            session_destroy();
            mr_redirect('');
        }
    }

    public function create_local_user()
    {

        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $data['login'] = isset($_POST['login']) ? $_POST['login'] : '';

        if ($_POST && (! $data['login'] or ! $password)) {
            error('Empty values are not allowed', 'auth.empty_not_allowed');
        }

        if ($data['login'] && $password) {
            $auth_config = new AuthLocal(conf('auth')['auth_local']);
            $t_hasher = $auth_config->load_phpass();
            $data['password_hash'] = $t_hasher->HashPassword($password);
            mr_view('auth/user', $data);
        }
        else {
          mr_view('auth/create_local_user', $data);
        }
    }

    public function generate()
    {
      // Legacy function
      mr_redirect('auth/create_local_user');
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
