<?php

namespace munkireport\lib;
use \OneLogin\Saml2\Auth as OneLogin_Saml2_Auth;
use \OneLogin\Saml2\Settings as OneLogin_Saml2_Settings;
use \OneLogin\Saml2\Error as OneLogin_Saml2_Error;
use \Exception, \View;
use \Illuminate\Filesystem\Filesystem;

class AuthSaml extends AbstractAuth

{
    private $config, $mr_config, $error, $authController, $groups, $login, $forceAuthn, $filesystem;

    public function __construct($config, $authHandler)
    {
        $this->authController = $authHandler;
        
        $this->filesystem = new Filesystem;

        $this->config = $config;
        $this->mr_config = $this->config['munkireport'];
        unset($this->config['munkireport']);
        
        if( empty($this->config['sp']['entityId'])){
            $this->config['sp']['entityId'] = url('auth/saml/metadata', true);
        }
        $this->config['sp']['assertionConsumerService'] = [
            'url' => url('auth/saml/acs', true)
        ];
        $this->config['sp']['singleLogoutService'] = [
            'url' => url('auth/saml/sls', true)
        ];
        $this->forceAuthn = $this->config['disable_sso'];
        
        if( ! $this->certificateInConfig('sp', 'x509cert')){
            $this->loadCertificateFromFile('sp', 'x509cert', 'sp.crt');
        }
        
        if( ! $this->certificateInConfig('sp', 'privateKey')){
            $this->loadCertificateFromFile('sp', 'privateKey', 'sp.key');
        }

        if( ! $this->certificateInConfig('idp', 'x509cert')){
            $this->loadCertificateFromFile('idp', 'x509cert', 'idp.crt');
        }

    }
    
    private function certificateInConfig($provider, $cert)
    {
        return ! empty($this->config[$provider][$cert]);
    }
    
    private function loadCertificateFromFile($provider, $cert, $filename)
    {
        $certdir = $this->mr_config['cert_directory'];
        if($this->filesystem->exists($certdir . $filename)){
            $this->config[$provider][$cert] = $this->filesystem->get($certdir . $filename);
        }
    }

    public function handle($endpoint)
    {
        switch ($endpoint) {
            case 'metadata':
                $this->metadata();
                break;
            case 'sso':
                $this->sso();
                break;
            case 'slo':
                $this->slo();
                break;
            case 'acs':
                $this->acs();
                break;
            case 'sls':
                $this->sls();
                break;
            default:
                throw new Exception("Unknown endpoint: $endpoint", 1);

                break;
        }
    }

    private function metadata()
    {
        try {
            $settings = new OneLogin_Saml2_Settings($this->config, true);
            $metadata = $settings->getSPMetadata();
            $errors = $settings->validateMetadata($metadata);
            if (empty($errors)) {
                header('Content-Type: text/xml');
                echo $metadata;
            } else {
                throw new OneLogin_Saml2_Error(
                    'Invalid SP metadata: '.implode(', ', $errors),
                    OneLogin_Saml2_Error::METADATA_SP_INVALID
                );
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    // Initiate Single Sign On
    private function sso()
    {
        $auth = new OneLogin_Saml2_Auth($this->config);
        $auth->login(
            $returnTo = null,
            $parameters = array(),
            $forceAuthn = $this->forceAuthn,
            $isPassive = false
        );
    }

    // Retrieve Data from IDP
    private function acs()
    {
        $auth = new OneLogin_Saml2_Auth($this->config);
        if (isset($_SESSION) && isset($_SESSION['AuthNRequestID'])) {
            $requestID = $_SESSION['AuthNRequestID'];
        } else {
            $requestID = null;
        }

        $auth->processResponse($requestID);

        $errors = $auth->getErrors();

        if ( ! empty($errors)) {
            echo '<p>',implode(', ', $errors),'</p>';
        }

        if ( ! $auth->isAuthenticated()) {
            echo "<p>Not authenticated</p>";
            exit();
        }

        // Not sure why we have to store these:
        $_SESSION['samlUserdata'] = $auth->getAttributes();
        $_SESSION['samlNameId'] = $auth->getNameId();
        $_SESSION['samlNameIdFormat'] = $auth->getNameIdFormat();
        $_SESSION['samlSessionIndex'] = $auth->getSessionIndex();
        unset($_SESSION['AuthNRequestID']);

        $attrs = $auth->getAttributes();
        $auth_data = $this->mapSamlAttrs($attrs);
        if ($this->authorizeUserAndGroups($this->mr_config, $auth_data)) {
            $this->login = $auth_data['user'];
            $this->groups = $auth_data['groups'];
            $this->authController->storeAuthData($this);
            $this->authController->setSessionProps();

            //var_dump($_SESSION);
            // Go to dashboard
            redirect('show/dashboard');
        }
        else{
            redirect('auth/unauthorized');
        }

    }

    // Single Logout
    private function slo()
    {
        // Check if SSO is disabled, if yes, destroy session and move on
        if($this->config['disable_sso']){
            session_destroy();
            $obj = new View();
            $obj->view('auth/logout', ['loginurl' => url()]);
            return;
        }

        $auth = new OneLogin_Saml2_Auth($this->config);

        $returnTo = url('auth/saml/sls');
        $parameters = array();
        $nameId = null;
        $sessionIndex = null;
        $nameIdFormat = null;

        if (isset($_SESSION['samlNameId'])) {
            $nameId = $_SESSION['samlNameId'];
        }
        if (isset($_SESSION['samlSessionIndex'])) {
            $sessionIndex = $_SESSION['samlSessionIndex'];
        }
        if (isset($_SESSION['samlNameIdFormat'])) {
            $nameIdFormat = $_SESSION['samlNameIdFormat'];
        }

        $auth->logout($returnTo, $parameters, $nameId, $sessionIndex, false, $nameIdFormat);

    }

    private function sls()
    {
        $auth = new OneLogin_Saml2_Auth($this->config);

        if (isset($_SESSION) && isset($_SESSION['LogoutRequestID'])) {
            $requestID = $_SESSION['LogoutRequestID'];
        } else {
            $requestID = null;
        }
        try {
            $auth->processSLO(false, $requestID);
            $errors = $auth->getErrors();
            if (empty($errors)) {
                $obj = new View();
                $obj->view('auth/logout', ['loginurl' => url()]);
            } else {
                echo '<p>' . implode(', ', $errors) . '</p>';
            }
        } catch (OneLogin_Saml2_Error $e) {
            if(isset($this->config['disable_sso_sls_verify']) && $this->config['disable_sso_sls_verify'] === true && strpos($e->getMessage(),'Only supported HTTP_REDIRECT Binding') !== false ){
                session_destroy();
                $obj = new View();
                $obj->view('auth/logout', ['loginurl' => url()]);
                return;
            }
            echo 'An error occurred during logout';
            print_r($e->getMessage());
        }
    }

    private function mapSamlAttrs($attrs)
    {
        $out = [
            'auth' => 'saml',
            'user' => '',
            'groups' => [],
        ];
        
        $userAttribute = $this->config['attr_mapping']['user'];
        $groupAttributes = $this->config['attr_mapping']['groups'];
        
        if(isset($attrs[$userAttribute])){
            $out['user'] = $attrs[$userAttribute][0];
        }
        
        foreach ($groupAttributes as $groupAttribute) {
            if(isset($attrs[$groupAttribute])){
                $out['groups'] = array_merge( $out['groups'], $attrs[$groupAttribute]);
            }
        }

        // Check if we have a user
        if( $this->debug() && ! $out['user'] )
        {
            $attr_list = implode(', ', array_keys($attrs));
            die("<pre>SAML Mapping error: user not found in SAML attributes ($attr_list)");
        }

        return $out;
    }

    private function debug()
    {
        return isset($this->config['debug']) && $this->config['debug'] == true;
    }

    public function login($login, $password)
    {
        redirect('auth/saml/sso');
    }

    public function getAuthMechanism()
    {
        return 'saml';
    }

    public function getAuthStatus()
    {
        return 'success'; //FIXME
    }

    public function getUser()
    {
        return $this->login;
    }

    public function getGroups()
    {
        return $this->groups;
    }
}
