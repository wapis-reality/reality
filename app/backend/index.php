<?php
function debug_pr($data){
    ob_start();
        echo 'DEBUG>><br/><pre>';
        print_r($data);
        echo '</pre>';
    $str = ob_get_contents();
    ob_end_flush();
    return $str;
}
date_default_timezone_set("Europe/London");

define('APP_ADDR',"");
define('PATH','');
define('PATH_TO_CLASSES','./classes/');
define('PATH_TO_COMPONENTS','./components/');
define('PATH_TO_MODULES','./modules/');
define('WIDGETS','./modules/cms/widgets/');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once PATH_TO_CLASSES . 'basic.php';
include_once PATH_TO_CLASSES . 'inflector.php';

$app = new App();

?>