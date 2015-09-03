<?php

class Paths {

    static $helper = array('app/views/helpers/');
    static $view = array('app/views/');
    static $controller = array('app/controllers/');
    static $db_source = array('app/db_source/');
    static $component = array('app/components/');
    static $panel = array('app/panels/');
    static $model = array('app/models/');
    static $layout = array('app/views/layout/');
    static $element = array('app/views/elements/');



    static function set($type, $data, $clear=false) {
        if (!isset(self::$$type)) {
            die('Neznamy typ cesty "' . $type . '"');
        }
        if ($clear == true) {
            self::$$type = array();
        }
        if (is_string($data)) {
            array_push(self::$$type, $data);
        }

        if (is_array($data)) {
            array_push(self::$$type, $data);
        }
    }

    static function findPath($type = null, $file = null) {
        if ($type == null or !isset(self::$$type)) {
            die('Neznamy typ cesty');
        }
        if ($file == null)
            return false;

        $sFileToLoad = "";
        foreach (self::$$type as $path) {

              //  echo self::findRoot() . $path . $file . "</br>\n";

            

            if (file_exists(self::findRoot() . $path . $file)){
                $sFileToLoad = self::findRoot() . $path . $file;

                //fix for priority of loading when project is running Pawel 2011-07-18
                if(defined('PROJECT_PATH')){
                    if(strpos($path, 'projects')){
                        break;
                    }
                } else {
                    break;
                }
                    
            }
        }
        
        if(!empty($sFileToLoad))
            return $sFileToLoad;

        return false;
    }

    static function findRoot() {
        return './';
        $server_arr = explode('.', $_SERVER['HTTP_HOST']);
        $recognize_path = $server_arr[0];
        $base_path = substr($_SERVER['SCRIPT_FILENAME'], strpos($_SERVER['SCRIPT_FILENAME'], $recognize_path) + strlen($recognize_path) + 1);
        return $relative_path = (substr_count($base_path, "/") == 0) ? '' : str_repeat('../', substr_count($base_path, "/"));
    }

    static function getPath($sClassName, $sProjectName) {
        foreach (self::$$sClassName as $row) {
            if (strpos($row, $sProjectName)) {
                return self::findRoot() . ltrim($row,'/');
            }
        }
    }

}

?>