<?php


/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.canvas.php,v 1.5 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */

/**
 * Generic UI canvas
 *
 * @version $Revision: 1.5 $
 * @package VESHTER
 */

class CCanvas extends CGadget
{
    private $prefix;
    
    function __construct($prefix = '/content/') 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.5 $');

        $this->prefix = $prefix;
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }

    private function GetTheme($realm, $id)
    {
        //look for an explicitely requested theme
        if (!empty($id))
        {
            $c = 0;
            //go through all available datagrids and see if you can get the information
            while (($datagrid = CEnvironment::GetMainApplication()->GetDataGrid($c)) != null)
            {

                //select the explicitely specified theme
                if ($datagrid->Select('basiccontent', '*', CString::Format('realm=%s AND type=%s AND guid=%s', CString::Quote($realm), CString::Quote('theme'), CString::Quote($id)), '1'))
                {
                    $this->Notify('Using explicit theme');
                    return $datagrid->GetRow(0,false);
                }
                $c++;

                //CEnvironment::Dump($status = $datagrid->GetStatus());
                //CEnvironment::Dump($status = $datagrid->GetDatabaseLink()->GetStatus());
            }
        }

        else
        {
            $c = 0;
            //go through all available datagrids and see if you can get the information
            while (($datagrid = CEnvironment::GetMainApplication()->GetDataGrid($c)) != null)
            {

                //select the default theme for that realm
                if ($datagrid->Select('basiccontent', '*', CString::Format('realm=%s AND type=%s AND priority=0', CString::Quote($realm), CString::Quote('theme')), '1'))
                {
                    $this->Notify('Using default theme');
                    return $datagrid->GetRow(0,false);
                }
                $c++;

                //CEnvironment::Dump($status = $datagrid->GetStatus());
                //CEnvironment::Dump($status = $datagrid->GetDatabaseLink()->GetStatus());
            }
        }

        $this->Warn('No suitable theme was found');
        return null;
    }



    private function GetFormattedPath()
    {
        $pathinfo = pathinfo (CEnvironment::GetScriptVirtualName());

        //CEnvironment::Dump($pathinfo);

        $filename = '';

        if (!empty($pathinfo['basename']))
        {
            $parts = explode('.', $pathinfo['basename']);

            //CEnvironment::Dump($parts);

            if (count($parts) == 1)
            {
                $filename = $parts[0];
            }
            else
            {
                $filename = CString::Format('%s.%s', $parts[0], $parts[count($parts)-1]);
            }
        }

        $dir = $pathinfo['dirname'] != '/' ? $pathinfo['dirname'] : '';

        $path = CString::Format('%s/%s', $dir, $filename);

        return $path;
    }

    private function RegisterUrlVariables()
    {

        $pathinfo = pathinfo (CEnvironment::GetScriptVirtualName());

        //CEnvironment::Dump($pathinfo);

        $args = explode('.', $pathinfo['basename']);
                
        if (count($args) > 2)
        {
            //go through all the args except the actual file name and extension
            for ($loop = 1; $loop < count($args)-1; $loop++)
            {
                $pair = explode('_', $args[$loop]);
                if (count($pair) == 2)
                {
                    CEnvironment::RegisterGlobalVariable($pair[0], $pair[1]);
                    $this->Notify(CString::Format('Registering explicit URI key-value pair %s with value %s', $pair[0], $pair[1]));
                }
                else
                {
                    throw new CExceptionInvalidData('Supplied explicit URI key-value pair is invalid');
                }


            }

            return true;

        }

        return false;
    }

    /**
     * Renders the canvas. Canvas contents are lookup using the specified virtual extension
     * @param $extension Virtual file extension for dynamic scripts
     */
    function Render($extension = 'html')
    {
        $this->RegisterUrlVariables();

        $path_value = $this->GetFormattedPath();

        $path_value_index = CString::Format('%s/index%s', $path_value, $extension);

        $path_key_full = CString::Format('CONCAT(%s, realm, CASE WHEN folder!="" AND folder IS NOT NULL THEN CONCAT("/", folder) ELSE "" END, "/", filename)', CString::Quote($this->prefix));
        $path_key_relative = CString::Format('CONCAT(CASE WHEN folder!="" AND folder IS NOT NULL THEN CONCAT("/", folder) ELSE "" END, "/", filename)');

        $this->Notify(CString::Format('Looking for path %s using %s and %s', CString::Quote($path_value), $path_key_full, $path_key_relative));

        //CEnvironment::Dump($this->GetStatus());        

        $c = 0;
        //go through all available datagrids and see if you can get the information
        while (($datagrid = CEnvironment::GetMainApplication()->GetDataGrid($c)) != null)
        {

            if ($datagrid->Select('basiccontent', '*', CString::Format('(%s=%s) OR (%s=%s) OR (%s=%s) OR (%s=%s)',
            //full path
            $path_key_full, CString::Quote($path_value),
            //relative path
            $path_key_relative, CString::Quote($path_value),
            //full path + index
            $path_key_full, CString::Quote($path_value_index),
            //relative path + index
            $path_key_relative, CString::Quote($path_value_index)
            ),
				'1'))
            {
                $data = $datagrid->GetRow(0,false);

                if ($data['workgroup'] != 'any')
                {
                    CEnvironment::GetMainApplication()->SetType($data['workgroup']);
                }

                $widget = CWidgetFactory::CreateWidget($data['type']);

                $widget->LoadData($data);

                if ($widget instanceof CWidgetThemed)
                {
                    $widget->SetTheme($this->GetTheme($data['realm'], $data['wrapper']));
                }

                //CEnvironment::Dump($widget);

                $widget->Render();

                return true;
            }
            //CEnvironment::Dump($datagrid->GetDatabaseLink()->GetStatus());
            $c++;


        }



        throw new CExceptionNotFound(CString::Format('Resource %s not found', $path_value));

        //old school style
        //$this->RenderV0Code();

    }
}


?>