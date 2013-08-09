<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.identityprovidergoogle.php,v 1.4.4.2 2012-06-02 20:20:23 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

if (!defined(_PATH_FRAMEWORK_PLUGINS_GOOGLEAPI))
{

    /**
     * @ignore
     */
    define ('_PATH_FRAMEWORK_PLUGINS_GOOGLEAPI', _PATH_FRAMEWORK_PLUGINS . 'googleapi' . _DIRSLASH);

    /**
     * @ignore
     */
    require_once(_PATH_FRAMEWORK_PLUGINS_GOOGLEAPI . 'Google_Client.php');
    require_once(_PATH_FRAMEWORK_PLUGINS_GOOGLEAPI . 'contrib' . _DIRSLASH . 'Google_Oauth2Service.php');
}

/**
 * Identity provider for Google
 *
 * @version $Revision: 1.4.4.2 $
 * @package VESHTER
 *
 */

class CIdentityProviderGoogle extends CIdentityProvider
{
    /**
     * @var Google_Client
     * @ignore
     */
    protected $base;

    function __construct($clientId, $clientSecret, $api_key, $redirectUrl)
    {
        global $apiConfig;

        parent::__construct();
        $this->SetVersion('$Revision: 1.4.4.2 $');
        
        $this->base = new Google_Client();

        $this->base->setClientId($clientId);
        $this->base->setClientSecret($clientSecret);
        $this->base->setDeveloperKey($api_key);

        $this->oauth2 = new Google_Oauth2Service($this->base);

        //$scopes = $apiConfig['services']['oauth2']['scope'];
        //$this->base->setScopes($scopes);

        $this->base->setRedirectUri($redirectUrl);

    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    public function IsAuthenticating()
    {
        return $this->base->getAccessToken() != null;
    }

    public function GetAuthenticationUrl()
    {
        return $this->base->createAuthUrl();
    }

    public function Authenticate($code)
    {
        $token = $this->base->authenticate($code);
        $this->base->setAccessToken($token);
    }

    /**
     *
     * @param $token
     * @return unknown_type
     */
    public function GetUserIdentity ()
    {
        $rawUser = $this->oauth2->userinfo->get();

        $user = new CUser();

        $user->SetLogin($this->PrefixLogin($rawUser['id']));
        $user->SetNameFirst($rawUser['given_name']);
        $user->SetNameLast($rawUser['family_name']);
        $user->SetEmail($rawUser['email']);
        $user->SetProfileUrl($rawUser['link']);

        //CEnvironment::Dump($user);

        return $user;

    }

    public function RevokeToken()
    {
        $this->base->revokeToken();
    }

}
?>