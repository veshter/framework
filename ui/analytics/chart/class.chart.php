<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.chart.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

if (!defined(_PATH_FRAMEWORK_PLUGINS_CHART))
{
    /**
     * @ignore
     */
    define ('_PATH_FRAMEWORK_PLUGINS_CHART', _PATH_FRAMEWORK_PLUGINS . 'OFC' . _DIRSLASH);

    /**
     * @ignore
     */
    require_once(_PATH_FRAMEWORK_PLUGINS_CHART . 'OFC_Chart.php');
}


/**
 * @ignore
 * @package VESHTER
 */
class CChartBase extends OFC_Chart{}

/**
 * Simple chart value
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */
class CChartValue extends CData
{
    public $value;
    public $label;

    function __construct($value, $label)
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.3 $');

        $this->value = $value;
        $this->label = $label;
    }

    function __destruct()
    {
        parent::__destruct();
    }
}

/**
 * Multipurpose chart
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */
class CChart extends CGadget
{

    /**
     * @var CChartBase
     * @ignore
     */
    protected $base;

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.3 $');

        //set up the base object
        $this->base = new CChartBase();
        $this->SetTitle('VESHTER Chart');
        $this->SetBackgroundColor('#FFFFFF');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function SetTitle( $heading )
    {
        $title = new OFC_Elements_Title( $heading );
        $this->base->title = $title;
    }

    function SetXAxis( $min, $max, $steps=1, $labels = null, $areLabelsVertical = false)
    {
        $axis= new OFC_Elements_Axis_X();
        $axis->set_range($min, $max, $steps);

        $x_axis_labels = new OFC_Elements_Axis_X_Label_Set();
        $x_axis_labels->set_labels( $labels );

        if ($areLabelsVertical)
        {
            $x_axis_labels->set_vertical();
        }

        $axis->set_labels($x_axis_labels);
        $this->base->x_axis = $axis;
    }

    function SetYAxis($min, $max, $steps=1, $labels = null)
    {
        $axis = new OFC_Elements_Axis_Y();
        $axis->set_range($min, $max, $steps);
        $axis->set_labels($labels);
        $this->base->y_axis = $axis;
    }


    function AddElement( $element )
    {
        $this->base->elements[] = $element;
    }


    function AddLineElement($data, $color = '#6363AC', $opacity = 1, $width = 5,  $dotSize = 5 )
    {
        $line = new OFC_Charts_Line();
        $line->set_width( $width );
        $line->set_colour( $color );
        $line->set_dot_size( $dotSize );
        $line->set_values( $data );
        $line->set_alpha($opacity);
        $this->AddElement($line);
    }

    function AddScatterElement($data, $color = '#6363AC', $opacity = 1, $width = 5,  $dotSize = 5 )
    {
        $scatter = new OFC_Charts_Scatter();
        $scatter->set_width( $width );
        $scatter->set_colour( $color );
        $scatter->set_dot_size( $dotSize );
        $scatter->set_values( $data );
        $scatter->set_alpha($opacity);
        $this->AddElement($scatter);
    }


    function AddAreaElement($data, $color = '#6363AC', $opacity = 1 )
    {
        $area = new OFC_Charts_Area();
        //$area->set_width( $width );
        $area->set_colour( $color );
        //$area->set_dot_size( $dotSize );
        $area->set_values( $data );
        $area->set_alpha($opacity);
        $this->AddElement($area);
    }

    function AddBarElement($data, $color = '#6363AC', $opacity = 1, $width = 5)
    {
        $bar = new OFC_Charts_Bar();
        $bar->set_colour($color);
        $bar->set_values($data);
        $bar->set_alpha($opacity);
        $this->AddElement($bar);
    }

    function AddPieElement($data, $colors, $opacity = 1, $startAngle = 0, $animate = 'fade')
    {
        $pie = new OFC_Charts_Pie();
        $pie->set_start_angle($startAngle);
        $pie->set_animate($animate);
        $pie->set_colours($colors);
        $pie->set_alpha($opacity);
        $pie->set_values($data);
        $this->AddElement($pie);
    }





    function SetXLegend( $x )
    {
        $legend = new OFC_Elements_Legend_X($x);
        $this->base->set_x_legend($legend);
    }

    function SetYLegend( $y )
    {
        $legend = new OFC_Elements_Legend_Y($x);
        $this->base->set_y_legend($legend);
    }

    function SetBackgroundColor( $color )
    {
        $this->base->bg_colour = $color;
    }

    function ToString()
    {
        if (function_exists('json_encode'))
        {
            return json_encode($this->base);
        }
        else
        {
            $json = new Services_JSON();
            return $json->encode( $this->base );
        }
    }

    function ToChartString()
    {
        return json_format( $this->base->toString() );
    }

}

/*
 *
 * Changelog:
 * $Log: class.chart.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2010-04-05 03:11:07  dkolev
 * Class reorganization
 *
 * Revision 1.5  2010-03-01 09:13:35  dkolev
 * Added documentation
 *
 * Revision 1.4  2010-03-01 08:40:06  dkolev
 * Fixed inheritance
 *
 * Revision 1.3  2010-02-23 04:37:55  dkolev
 * Fixed default labels
 *
 * Revision 1.2  2010-02-21 21:37:25  dkolev
 * Adding more chart types
 *
 * Revision 1.1  2009-12-27 19:57:19  dkolev
 * Refactoring and moving to proper folders
 *
 * Revision 1.18  2009-05-26 03:39:44  dkolev
 * Updated code for new version of TBS
 *
 * Revision 1.17  2008-12-15 03:45:27  dkolev
 * *** empty log message ***
 *
 * Revision 1.16  2008-09-18 16:15:25  dkolev
 * Made the self and request variables always merge in a document.
 *
 * Revision 1.15  2008-06-09 07:21:05  dkolev
 * Changed protecteemail default from true to false. Some forms that use email addresses to send information get errors if the email is obfuscated
 *
 * Revision 1.14  2008/05/31 04:29:07  dkolev
 * Added more parameters to the ToString function
 *
 * Revision 1.13  2008/05/20 01:57:51  dkolev
 * Added try-catch around GetMainApplication call.
 *
 * Revision 1.12  2008/05/18 00:43:27  dkolev
 * Removed unnecessary try-catch blocks
 *
 * Revision 1.11  2008/05/08 04:09:51  dkolev
 * Added a try/catch block to make sure that when the framework is not initialized, document merging does not fail.
 *
 * Revision 1.10  2008/05/06 04:59:01  dkolev
 * Added config and server information as default information for every merge
 *
 * Revision 1.9  2008/02/05 08:56:57  dkolev
 * Added RestorePseudoCode function
 *
 * Revision 1.8  2007/11/12 05:05:44  dkolev
 * Quick fix for MergeField
 *
 * Revision 1.7  2007/09/26 22:38:22  dkolev
 * Changed inheritance
 *
 * Revision 1.6  2007/05/17 06:25:04  dkolev
 * Reflect C-names
 *
 * Revision 1.5  2007/02/28 10:12:54  dkolev
 * Added MergeField
 *
 * Revision 1.4  2007/02/26 04:03:27  dkolev
 * Added page-level DocBlock
 *
 * Revision 1.3  2007/02/26 02:57:57  dkolev
 * Made CDocument class non-abstract
 *
 * Revision 1.2  2007/02/26 02:50:30  dkolev
 * Added standardized CVS commenting
 *
 *
 *
 */
?>