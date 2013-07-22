<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.codeinterpreter.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Programming language interpreter that runs different scripts and returns their result
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */
class CCodeInterpreter extends CGadget
{

    /**
     * @param string Original code wrapper for the interpreter
     */
    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.3 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
    
    /**
     * Executes a script in the current wrapper
     */
    public function EvalScript($snippet, &$context)
    {
        //don't waste any time trying to run an empty script
        if (CString::IsNullOrEmpty($snippet))
        {
            throw new CExceptionInvalidData('No script to interpret');
        }

        try
        {
            $snippet = preg_replace('/<\?(php)?/i', '######### PHP begin token removed', $snippet);
            $snippet = preg_replace('/\?>/i', '######### PHP end token removed', $snippet);
            $snippet = trim($snippet);

            $wrapper = <<<PHP

######### Wrapper begin
%s
######### Wrapper end
	
//return false is nothing happened
return null;

PHP;

            //print ($script);
            //exit;

            $script = CString::Format($wrapper, $snippet);

            $result = eval($script);
        }
        catch (CExceptionEx $ex)
        {
            $result = false;
            throw $ex;
            //CEnvironment::Dump($ex);
        }

        return $result;
    }

}

/*
 *
 * Changelog:
 * $Log: class.codeinterpreter.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:39  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2009-12-27 19:57:19  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.6  2009-11-15 00:52:08  dkolev
 * Added <?/<?php filter
 *
 * Revision 1.5  2009-03-30 05:11:36  dkolev
 * Improved encapsulated code execution and better bubbling of exceptions
 *
 * Revision 1.4  2009-03-30 01:05:44  dkolev
 * Added context to the EvalScript function
 *
 * Revision 1.3  2008-12-15 03:41:56  dkolev
 * Minor changes
 *
 * Revision 1.2  2008/08/18 08:27:22  dkolev
 * Changed some double quotes to singles
 *
 * Revision 1.1  2008/05/31 04:28:25  dkolev
 * Initial import
 *
 *
 */

?>