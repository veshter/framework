<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.analyticsprovidergoogle.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

if (!defined(_PATH_FRAMEWORK_PLUGINS_GAPI))
{
    /**
     * @ignore
     */
    define ('_PATH_FRAMEWORK_PLUGINS_GAPI', _PATH_FRAMEWORK_PLUGINS . 'GAPI' . _DIRSLASH);

    /**
     * @ignore
     */
    require_once(_PATH_FRAMEWORK_PLUGINS_GAPI . 'gapi.class.php');
}


/**
 * @ignore
 * @package VESHTER
 */
class CAnalyticsProviderGoogleBase extends gapi
{
    function CAnalyticsProviderGoogleBase($email, $password, $token=null)
    {
        parent::__construct($email, $password, $token);
    }
}

/**
 * Document class
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */
class CAnalyticsProviderGoogle extends CAnalyticsProvider
{

    /**
     * @var CAnalyticsProviderGoogleBase
     * @ignore
     */
    protected $base;

    function __construct($login, $password, $token = null)
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.3 $');

        $this->properties['login'] = $login;
        $this->properties['password'] = $password;
        $this->properties['token'] = $token;

        //set up the base object
        $this->base = new CAnalyticsProviderGoogleBase($this->properties['login'], $this->properties['password'], $this->properties['token']);
    }

    function __destruct()
    {
        parent::__destruct();
    }
     
    function GetAccountData()
    {
        $this->base->requestAccountData();

        $data = array();

        foreach($this->base->getResults() as $result)
        {
            $data[] = $result->getProperties();
        }

        return $data;
    }

    function GenerateReport($report_id, $dimensions, $metrics, $sort_metric=null, $filter=null, $start_date=null, $end_date=null, $start_index=1, $max_results=30)
    {
        $this->base->requestReportData($report_id,$dimensions, $metrics, $sort_metric, $filter, $start_date, $end_date, $start_index, $max_results);
    }

    function Get($aggregated = false)
    {
         
        if (!$aggregated)
        {
            $data = array();
            foreach ($this->base->getResults() as $row)
            {

                $data[] = array(
			'dimensions' => $row->getDimesions(), //good programmer but can't spell dimensions
			'metrics' => $row->getMetrics());
            }
            return $data;
        }
        else
        {
            return $this->base->getMetrics();
        }
    }


}

/*
 *
 * Changelog:
 * $Log: class.analyticsprovidergoogle.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2010-04-05 03:11:07  dkolev
 * Class reorganization
 *
 * Revision 1.18  2009-05-26 03:39:44  dkolev
 *
 */
?>