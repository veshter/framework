<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.databasehelper.php,v 1.6 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Database helper used to generate data requests
 *
 * @version $Revision: 1.6 $
 * @package VESHTER
 *
 */
class CDatabaseHelper extends CGadget
{
    protected $location = null;

    protected $keys = array();

    protected $values = array();

    protected $where = 1;

    protected $limit_lower = 0;
    protected $limit_upper = 1;

    protected $orderby = null;

    protected $groupby = null;

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.6 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    public function SetLocation($location)
    {
        $this->location = $location;
    }

    public function GetLocation()
    {
        return $this->location;
    }

    public function SetKeys($keys)
    {
        //we didn't get an array, make it into an array
        if (!is_array($keys))
        {
            $keys = array($keys);
        }
        $this->keys = $keys;
    }

    public function GetKeys()
    {
        return $this->keys;
    }

    public function SetValues($values)
    {
        //we didn't get an array, make it into an array
        if (!is_array($values))
        {
            $values = array($values);
        }
        $this->values = $values;
    }

    public function GetValues()
    {
        return $this->values;
    }

    public function SetWhere($where)
    {
        $this->where = $where;
    }

    public function GetWhere()
    {
        return $this->where;
    }

    public function SetLimit($limit = "0,1")
    {
        $limits = explode(',', $limit);
        list($limit_lower, $limit_upper) = $limits;

        //special case
        if ($limit_lower == -1)
        {
            $limit_lower = 0;
            $limit_upper = intval('18446744073709551615'); //crazy no?
        }
        //we were only sent one limit (the other one is implied)
        else if (count($limits) == 1)
        {
            $limit_upper = $limit_lower;
            $limit_lower = 0;
        }

        if (empty($limit_lower))
        {
            $limit_lower = 0;
        }
        if (empty($limit_upper))
        {
            $limit_upper = 1;
        }

        if (!is_numeric($limit_lower))
        {
            throw new CExceptionInvalidData('Lower limit has to be numeric');
        }

        else if (!is_numeric($limit_upper))
        {
            throw new CExceptionInvalidData('Upper limit has to be numeric');
        }




         
        //		if ($limit_lower >= $limit_upper)
        //		{
        //			throw new CExceptionInvalidData('Upper limit has to be greater than the lower limit');
        //		}

        $this->limit_lower = $limit_lower;
        $this->limit_upper = $limit_upper;
    }

    public function GetLimit()
    {
        //is there is an upper limit, use it and return a formatted string
        if (!empty($this->limit_upper))
        {
            return CString::Format('%d, %d', $this->limit_lower, $this->limit_upper);
        }

        return $this->limit_lower;
    }

    public function SetOrderBy($orderby)
    {
        $this->orderby = $orderby;
    }

    public function GetOrderBy()
    {
        return $this->orderby;
    }

    public function SetGroupBy($groupby)
    {
        $this->groupby = $groupby;
    }

    public function GetGroupBy()
    {
        return $this->groupby;
    }
}

/*
 *
 * Changelog:
 * $Log: class.databasehelper.php,v $
 * Revision 1.6  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.5.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.5  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.4  2009-05-28 20:13:55  dkolev
 * Improved the error messages for upper and lower limits
 *
 * Revision 1.3  2008-02-03 10:19:44  dkolev
 * Upper limit does NOT have to be greater.
 *
 * Revision 1.2  2007/10/02 06:02:29  dkolev
 * Documentation changes for API doc
 *
 */
?>