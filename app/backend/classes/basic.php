<?php
function am()
{
    $r = array();
    $args = func_get_args();
    foreach ($args as $a) {
        if (!is_array($a)) {
            $a = array($a);
        }
        $r = array_merge($r, $a);
    }
    return $r;
}

function pr($ar)
{
    echo '<pre>';
    print_r($ar);
    echo '</pre>';
}

function convertObject2Array($params) {
    return json_decode(json_encode($params),true);
}

function __autoload($className) {
    try {

        //echo substr(Inflector::underscore($className),-6)."<br/>";
        /**
         * interfaces
         */

        //echo $className.'#<br/>';

        if (substr(Inflector::underscore($className),-9) === 'interface'){
            if (!file_exists(PATH_TO_CLASSES . 'interfaces/' . Inflector::underscore($className) . '.php')) {
                throw new exception;
            }
            require_once(PATH_TO_CLASSES . 'interfaces/' . Inflector::underscore($className) . '.php');

        /**
         * models
         */
        } else  if (substr(Inflector::underscore($className),-5) === 'model'){
            if (!file_exists(PATH_TO_CLASSES . 'models/' . Inflector::underscore($className) . '.php')) {
                throw new exception('File in path <b>'.PATH_TO_CLASSES . 'models/' . Inflector::underscore($className) . '.php</b> doesnt exist!');
            }

            require_once(PATH_TO_CLASSES . 'models/' . Inflector::underscore($className) . '.php');

        /**
         * modules
         */
        } else if (substr(Inflector::underscore($className),-6) === 'module'){
            if (!file_exists(PATH_TO_MODULES . substr(Inflector::underscore($className),0,-7) .'/'. Inflector::underscore($className) . '.php')) {
                throw new exception('File in path <b>'.PATH_TO_MODULES . substr(Inflector::underscore($className),0,-7) .'/'. Inflector::underscore($className) . '.php</b> doesnt exist!');
            }
            require_once(PATH_TO_MODULES . substr(Inflector::underscore($className),0,-7) .'/'. Inflector::underscore($className) . '.php');

        /**
         * components
         */
        } else if (substr(Inflector::underscore($className),-9) === 'component'){
            if (!file_exists(PATH_TO_COMPONENTS . substr(Inflector::underscore($className),0,-10) .'/'. Inflector::underscore($className) . '.php')) {
                throw new exception('File in path <b>'.PATH_TO_COMPONENTS . substr(Inflector::underscore($className),0,-10) .'/'. Inflector::underscore($className) . '.php</b> doesnt exist!');
            }
            require_once(PATH_TO_COMPONENTS . substr(Inflector::underscore($className),0,-10) .'/'. Inflector::underscore($className) . '.php');

        /**
         * classes
         */
        } else {
            if (!file_exists(PATH_TO_CLASSES . Inflector::underscore($className) . '.php')) {
                throw new exception;
            }
            require_once(PATH_TO_CLASSES . Inflector::underscore($className) . '.php');
        }
    } catch (Exception $exception) {
        die($exception->getMessage());
    }
}

/**
 * Recursively strips slashes from all values in an array
 *
 * @param array $values Array of values to strip slashes
 * @return mixed What is returned from calling stripslashes
 * @link http://book.cakephp.org/view/709/stripslashes_deep
 */
function stripslashes_deep($values) {
    if (is_array($values)) {
        foreach ($values as $key => $value) {
            $values[$key] = stripslashes_deep($value);
        }
    } else {
        $values = stripslashes($values);
    }
    return $values;
}

function router($url = ''){
    $route = array();
    preg_match_all('([A-Za-z0-9_-]+)', $url, $route);
//		pr($route);
    $route = array(explode('/',trim($url,'/')));

    //	include(CONFIG.'router.php');
    //	$routes = Router::_get();

    if ($url && strpos($url, '/') !== 0) {
        $url = '/' . $url;
    }

    if (strpos($url, '?') !== false) {
        $url = substr($url, 0, strpos($url, '?'));
    }

    if (isset($routes[$url])){
        $params['action'] = $routes[$url]['actions'];
        $params['controller'] = $routes[$url]['controller'];
    } else {
        $params['action'] = isset($route[0][1])?$route[0][1]:'index';
        $params['controller'] = $route[0][0];

    }

    unset(
        $route[0][0],
        $route[0][1]
    );

    foreach ($route[0] as $key => $value) {
        if (is_integer($key)) {
            $params['pass'][] = $value;
        }
    }

    return $params;
}

/**
 * [encrypt_decrypt This function will encrypt or decrypt the string. (Email)]
 * @param  [string] $action [encrypt or decrypt]
 * @param  [string] $string [This is the text what we want to encrypt or decrypt]
 * @return [string]         [output]
 */
function encrypt_decrypt($action, $string) {
    $output = false;

    $key = 'B2ET3cH!$*'.date("Ymd");

    // initialization vector
    $iv = md5(md5($key));

    if( $action == 'encrypt' ) {
        $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
        $output = strtr(base64_encode($output), '+/', '-_');
        //$output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        //$output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
        $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode(strtr($string, '-_', '+/')), MCRYPT_MODE_CBC, $iv);
        $output = rtrim($output, "");
    }

    return $output;
}

/**
 * Function which converts an xml file to php array
 * @param null $path
 */
function read_xml($path = null){

    if($path === null){
        return false;
    }

    if(!file_exists($path)){
        return false;
    }

    // Convert the file
    ob_start();
    require $path;
    $xml = ob_get_clean();
    $xml = simplexml_load_string($xml,'SimpleXMLElement', LIBXML_NOCDATA);
    $json = json_encode($xml);
    $result = json_decode($json, TRUE);
    return $result;
}

/**
 * Remove a list of keys from an array
 *
 * @param array $data This is the data source
 * @param $key_list This contains the list of keys you want to remove from $data
 * @return array
 */
function remove_keys_from_array(&$data = array(), $key_list = array())
{

    if(empty($data) || empty($key_list)){
        return false;
    }

    foreach($key_list as $key) {
        unset($data[$key]);
    }

    return true;
}

?>