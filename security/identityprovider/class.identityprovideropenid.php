<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.identityprovideropenid.php,v 1.4.4.2 2012-06-02 20:20:23 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Identity provider for OpenID
 *
 * @version $Revision: 1.4.4.2 $
 * @package VESHTER
 *
 */

class CIdentityProviderOpenID extends CIdentityProvider
{
    private $api_key = null;
    private $base_url = null;
    private $format = "xml";
    private $response_body = "";
    
    function __construct($api_key, $base_url) 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.4.4.2 $');
        
        while ($base_url[strlen($base_url) - 1] == "/")
        {
            $base_url = substr($base_url, 0, strlen($base_url) - 1);
        }

        $this->api_key = $api_key;
        $this->base_url = $base_url;
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    /**
     *
     * @param $token
     * @return unknown_type
     */
    public function GetUserIdentity ($token)
    {
        //CEnvironment::Dump($token);

        $response = $this->auth_info($token);
        //CEnvironment::Dump($response);

        $parser = new CParserXML();
        $parser->Parse($response);

        $status = $parser->GetAttribute('/rsp[1]', 'stat');

        //CEnvironment::Dump($status);
        if ($status == 'ok')
        {
            //$profile = $parser->GetElementXML('/rsp[1]/profile[1]');
            //CEnvironment::Dump($profile);
            	
            $user = new CUser();
            list($name_first, $name_last) = explode(' ', $parser->GetElementValue("/rsp[1]/profile[1]/displayName[1]"));
            	
            $user->SetLogin($parser->GetElementValue("/rsp[1]/profile[1]/identifier[1]"));
            $user->SetNameFirst($parser->GetElementValue("/rsp[1]/profile[1]/name[1]/givenName[1]"));
            $user->SetNameLast($parser->GetElementValue("/rsp[1]/profile[1]/name[1]/familyName[1]"));
            $user->SetEmail($parser->GetElementValue("/rsp[1]/profile[1]/email[1]"));
            //$user->SetNameMiddle('MName');
            //$user->SetNameLast('LName');
            //$user->SetEmail('fake@addy.com');
            $user->SetProfileUrl($parser->GetElementValue("/rsp[1]/profile[1]/url[1]"));
            	
            //CEnvironment::Dump($user);
            	
            return $user;
            	
        }
        else
        {
            throw new CExceptionTimeOut('Cannot login or token timed out');
        }
    }

    /*
     * Performs the 'auth_info' API call to retrieve information about
     * an OpenID authentication response.  You'll need to inspect the
     * resulting DOMDocument to get information about the response.
     * See the API documentation for details.
     *
     * https://rpxnow.com/docs
     */
    private function auth_info($token)
    {
        return $this->apiCall('auth_info', array('token' => $token, 'extended' => 'true'));
    }

    /*
     * Performs an API call using the specified name and arguments
     * array.  Automatically adds your API key to the request and
     * requests an XML response.  Returns a DOMDocument or raises
     * APIException.
     */
    private function apiCall($method_name, $partial_query)
    {
        $partial_query["format"] = $this->format;
        $partial_query["apiKey"] = $this->api_key;

        $query_str = "";
        foreach ($partial_query as $k => $v)
        {
            if (strlen($query_str) > 0)
            {
                $query_str .= "&";
            }

            $query_str .= urlencode($k);
            $query_str .= "=";
            $query_str .= urlencode($v);
        }

        $url = $this->base_url . "/api/v2/" . $method_name;
        $response_body = $this->Post($url, $query_str);

        return $response_body;
    }



    private function ResetPostData()
    {
        $this->response_data = "";
    }

    private function WriteResponseData($curl_handle, $raw)
    {
        $this->response_data .= $raw;
        return strlen($raw);
    }

    private function Post($url, $post_data)
    {
        $this->ResetPostData();

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_WRITEFUNCTION,
        array(&$this, "WriteResponseData"));

        curl_exec($curl);

        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (!$code)
        {
            throw new CExceptionInvalidData(sprintf("Error performing HTTP request: %s", curl_error($curl)));
        }

        $response_body = $this->response_data;
        $this->ResetPostData();
        curl_close($curl);

        return $response_body;
    }

}
?>