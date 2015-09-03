<?php

class ToMoveController extends AppController
{
    var $name = 'ToMove';

    function loadModuleList()
    {
        //load modules list
        $modules = array();
        $path = $_SERVER["DOCUMENT_ROOT"]."/app/modules.xml";

        $xml = read_xml($path);
        $xml = $xml["module"];

        $this->moduleName = array();
        $temp_modules = array();
        $menu_items = array();

        foreach ($xml as $a => $b) {
            if ($b['type'] == "page") {
                $modules["page"][] = array(
                    'name' => $b['name'],
                    'key' => $b['key']
                );
            } else {
                $modules["modul"][] = array(
                    'name' => $b['name'],
                    'key' => $b['key']
                );
            }
        }


        Log::djt($modules);
    }
}

?>