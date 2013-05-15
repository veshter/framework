<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.pdf.php,v 1.4 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

if (!defined(_PATH_FRAMEWORK_PLUGINS_PDF))
{
    /**
     * @ignore
     */
    define ('_PATH_FRAMEWORK_PLUGINS_PDF', _PATH_FRAMEWORK_PLUGINS . 'html2fpdf' . _DIRSLASH);

    /**
     * @ignore
     */
    require_once(_PATH_FRAMEWORK_PLUGINS_PDF . "html2fpdf.php");
}

/**
 * @ignore
 * @package VESHTER
 */
class CPDFBase extends HTML2FPDF
{
    //Page footer
    function Footer()
    {
        //no footer
    }
}

/**
 *
 * PDF file
 *
 * @version $Revision: 1.4 $
 * @package VESHTER
 *
 */
class CPDF extends CFile
{
    /**
     * @var CPDFBase
     * @ignore
     */
    protected $base;

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.4 $');

        //set up the base object
        $this->base = new CPDFBase();

        $this->filename = 'file.pdf';
    }

    function __destruct()
    {
        parent::__destruct();
    }

    public function SetHTML($html, $newpage = true)
    {
        if ($newpage)
        {
            $this->base->AddPage();
        }
        $this->base->WriteHTML($html);
    }

    public function GetCurrentPageNumber()
    {
        return $this->base->PageNo();
    }

    public function Flush($stream = null)
    {
        $this->base->Output($this->filename,'D');

    }
}

/*
 *
 * Changelog:
 * $Log: class.pdf.php,v $
 * Revision 1.4  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.3.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.3  2010-07-04 18:32:40  dkolev
 * Sniff improvements
 *
 * Revision 1.2  2009-06-21 03:10:21  dkolev
 * Documentation Improvement
 *
 * Revision 1.1  2008-01-05 22:55:14  dkolev
 * Initial import
 *
 * Revision 1.1  2007/12/21 08:39:10  dkolev
 * Initial import
 *
 *
 *
 */

?>