<?php

namespace munkireport\lib;
use \OneLogin_Saml2_Auth, \OneLogin_Saml2_Settings, \OneLogin_Saml2_Error;
use \Exception, \View;

class Auth_saml
{
    private $config, $error, $authController;

    public function __construct($authController)
    {
        $this->authController = $authController;

        // TODO Check config
        $this->config = $authController->getConfig('saml');
        $this->config['sp']['entityId'] = url('auth/saml/metadata', true);
        $this->config['sp']['assertionConsumerService'] = [
            'url' => url('auth/saml/acs', true)
        ];
        $this->config['sp']['singleLogoutService'] = [
            'url' => url('auth/saml/sls', true)
        ];
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
        $auth->login();
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
        $this->authController->storeAuthData($this->mapSamlAttrs($attrs));
        $this->authController->set_session_props();
        
        //var_dump($_SESSION);
        // Go to dashboard
        redirect('show/dashboard');

    }
    
    // Single Logout
    private function slo()
    {
        $auth = new OneLogin_Saml2_Auth($this->config);

        $returnTo = url('auth/saml/sls');
        $paramters = array();
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

        $auth->logout($returnTo, $paramters, $nameId, $sessionIndex, false, $nameIdFormat);
        
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
                $obj->view('auth/logout');
            } else {
                echo '<p>' . implode(', ', $errors) . '</p>';
            }
        } catch (OneLogin_Saml2_Error $e) {
            echo 'An error occurred during logout';
            print_r($e->getMessage());
        }
    }
    
    private function mapSamlAttrs($attrs)
    {
        $out = [
            'auth' => 'saml',
        ];

        if(isset($this->config['attr_mapping'])){
            $attr_mapping = $this->config['attr_mapping'];
        }
        else{
            $attr_mapping = [
                'memberOf' => 'groups',
                'User.email' => 'user',
            ];
        }
        foreach($attr_mapping as $key => $mappedKey){
            if( ! isset($attrs[$key])){
                throw new Exception("SAML Mapping error. $key not found in SAML attributes", 1);
            }
            if($mappedKey == 'groups'){
                $out[$mappedKey] = $attrs[$key];
            }
            else{
                $out[$mappedKey] = $attrs[$key][0];
            }
            
        }
        
        return $out;
    }

}