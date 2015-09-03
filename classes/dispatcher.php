<?php

class Router {
    static $_compiledRoutes = array();
    static $template = '';
    static $defaults = null;
    static $_greedy;

   static function connect($url = '', $set = array()){
        self::$template = $url;
        self::$defaults = $set;
        extract(@self::_writeRoute());
        @self::$_compiledRoutes[$template] = $set + array('keys'=>$keys);
    }

    function _get(){
        return $this->routes;
    }

    static function _writeRoute() {
        if (empty(self::$template) || (self::$template === '/')) {
            return array('template'=>'#^/*$#','keys'=>array());
        }
        $route = self::$template;
        $names = $routeParams = array();
        $parsed = preg_quote(self::$template, '#');
        $parsed = str_replace('\\-', '-', $parsed);


        preg_match_all('#:([A-Za-z0-9_-]+[A-Z0-9a-z])#', $parsed, $namedElements);
        foreach ($namedElements[1] as $i => $name) {
            $search = '\\' . $namedElements[0][$i];
            if (isset(self::$options[$name])) {
                $option = null;
                if ($name !== 'plugin' && array_key_exists($name, $this->defaults)) {
                    $option = '?';
                }
                $slashParam = '/\\' . $namedElements[0][$i];
                if (strpos($parsed, $slashParam) !== false) {
                    $routeParams[$slashParam] = '(?:/(' . self::$options[$name] . ')' . $option . ')' . $option;
                } else {
                    $routeParams[$search] = '(?:(' . self::$options[$name] . ')' . $option . ')' . $option;
                }
            } else {
                $routeParams[$search] = '(?:([^/]+))';
            }
            $names[] = $name;
        }

        if (preg_match('#\/\*$#', $route, $m)) {
            $parsed = preg_replace('#/\\\\\*$#', '(?:/(?P<_args_>.*))?', $parsed);
            self::$_greedy = true;
        }
        krsort($routeParams);
        $parsed = str_replace(array_keys($routeParams), array_values($routeParams), $parsed);
        return array('template'=>'#^' . $parsed . '[/]*$#','keys'=>$names);
    }

    static function load_setting(){
        include_once(CONFIG.'router.php');
    }

    static function parse($url) {
        $return = false;
        foreach(self::$_compiledRoutes as $rout_preg => $set){

            if (!preg_match($rout_preg, $url, $parsed)) {
                //return false;
            } else {
                array_shift($parsed);
                $route = array();
                foreach ($set['keys'] as $i => $key) {
                    if (isset($parsed[$i])) {
                        $route[$key] = $parsed[$i];
                    }
                }
                $route['pass'] = $route['named'] = array();
                $route += $set;

                if (isset($parsed['_args_'])) {
                    $route['_args_'] = $parsed['_args_'];
                    $route['pass'] = explode('/',$parsed['_args_']);
                }

                foreach ($route as $key => $value) {

                    if (is_integer($key) || in_array($key,$route['keys'])) {
                        $route['pass'][] = $value;
                        unset($route[$key]);
                    }
                }
                if ($route != null)
                    return  $route;
            }
            //	pr($return);
        }


        return $return;
    }

}

class dispatcher {
    /**
     * the params for this request
     *
     * @var string
     * @access public
     */
    var $params = null;

    /**
     * Constructor.
     */
    function __construct($url = null){
        if ($url !== null) {
            return $this->dispatch($url);
        }
    }

    function dispatch($url = null, $additionalParams = array()) {
        if (is_array($url)) {
            $url = $this->__extractParams($url, $additionalParams);
        } else {
            if ($url) {
                $_GET['url'] = $url;
            }

            $url = $this->getUrl();

            $this->params = array_merge($this->parseParams($url), $additionalParams);

        }

        $controller =& $this->__getController();


        if (!is_object($controller)) {
            die('missingController: '.@Inflector::camelize($this->params['controller']) . 'Controller' );
        }

        $controller->params =& $this->params;
        $controller->action =& $this->params['action'];
        $controller->data = !empty($this->params['data'])?$this->params['data']:null; // u prejimani by melo byt =& $this->params['data']

        if (array_key_exists('return', $this->params) && $this->params['return'] == 1) {
            $controller->autoRender = false;
        }

        return $this->_invoke($controller, $this->params);
    }


    /**
     * Invokes given controller's render action if autoRender option is set. Otherwise the
     * contents of the operation are returned as a string.
     *
     * @param object $controller Controller to invoke
     * @param array $params Parameters with at least the 'action' to invoke
     * @param boolean $missingAction Set to true if missing action should be rendered, false otherwise
     * @return string Output as sent by controller
     * @access protected
     */
    function _invoke(&$controller, $params) {
        $controller->__mergeVars();
        $controller->initClasses();
        if ($controller->beforeFiltered == true) {
            $controller->beforeFilter();
        }

        $methods = array_flip($controller->methods);

        if (!isset($methods[strtolower($params['action'])]) && !in_array($params['action'],array('index','trash','edit'))) {
            die ('Chybi action '. @Inflector::camelize($params['controller']."Controller").' '.$params['action']);
        }

        $output = $controller->dispatchMethod($params['action'], isset($params['pass'])?$params['pass']:array());

        if ($controller->autoRender) {
            if ($controller->rendered == false)
         
                $controller->output = $controller->render();
        } elseif (empty($controller->output)) {
            $controller->output = $output;
        }

        $controller->afterFilter();

        if (isset($params['return'])) {
            return $controller->output;
        }

        if (isset($controller->output))
            echo($controller->output);

    }



    function __extractParams($url, $additionalParams = array()) {
        $defaults = array('pass' => array(), 'named' => array(), 'form' => array());
        $this->params = array_merge($defaults, $url, $additionalParams);
        return $url;

        //return Router::url($url);
    }

    function parseParams($fromUrl) {
        $params = array();

        if (isset($_POST)) {
            $params['form'] = $_POST;
            if (ini_get('magic_quotes_gpc') === '1') {
                $params['form'] = stripslashes_deep($params['form']);
            }
            if (env('HTTP_X_HTTP_METHOD_OVERRIDE')) {
                $params['form']['_method'] = env('HTTP_X_HTTP_METHOD_OVERRIDE');
            }
            if (isset($params['form']['_method'])) {
                if (!empty($_SERVER)) {
                    $_SERVER['REQUEST_METHOD'] = $params['form']['_method'];
                } else {
                    $_ENV['REQUEST_METHOD'] = $params['form']['_method'];
                }
                unset($params['form']['_method']);
            }
        }

        Router::load_setting();

        if ($fromUrl && strpos($fromUrl, '/') !== 0) {
            $fromUrl = '/' . $fromUrl;
        }

        if (strpos($fromUrl, '?') !== false) {
            $fromUrl = substr($fromUrl, 0, strpos($fromUrl, '?'));
        }



        $router = Router::parse($fromUrl);
        //pr($router);

        $params = array_merge((!$router)?$this->router($fromUrl):$router, $params);
        if (strlen(@$params['action']) === 0) {
            $params['action'] = 'index';
        }
        if (isset($params['form']['data'])) {
            $params['data'] = clear_checkbox($params['form']['data']);
            unset($params['form']['data']);
        }
       
        if (isset($_GET)) {
            if (ini_get('magic_quotes_gpc') === '1') {
                $url = stripslashes_deep($_GET);
            } else {
                $url = $_GET;
            }
            if (isset($params['url'])) {
                $params['url'] = array_merge($params['url'], $url);
            } else {
                $params['url'] = $url;
            }
        }

        return $params;

    }

    /**
     * Returns and sets the $_GET[url] derived from the REQUEST_URI
     *
     * @param string $uri Request URI
     * @param string $base Base path
     * @return string URL
     * @access public
     */
    function getUrl($uri = null, $base = null) {
        if (empty($_GET['url'])) {
            if ($uri == null) {
                $uri = '/';//$this->uri();
            }
            if ($base == null) {
                $base = @$this->base;
            }
            $url = null;
            $tmpUri = preg_replace('/^(?:\?)?(?:\/)?/', '', $uri);
            $baseDir = preg_replace('/^\//', '', dirname($base)) . '/';

            if ($tmpUri === '/' || $tmpUri == $baseDir || $tmpUri == $base) {
                $url = $_GET['url'] = '/';
            } else {
                if ($base && strpos($uri, $base) !== false) {
                    $elements = explode($base, $uri);
                } elseif (preg_match('/^[\/\?\/|\/\?|\?\/]/', $uri)) {
                    $elements = array(1 => preg_replace('/^[\/\?\/|\/\?|\?\/]/', '', $uri));
                } else {
                    $elements = array();
                }

                if (!empty($elements[1])) {
                    $_GET['url'] = $elements[1];
                    $url = $elements[1];
                } else {
                    $url = $_GET['url'] = '/';
                }

                if (strpos($url, '/') === 0 && $url != '/') {
                    $url = $_GET['url'] = substr($url, 1);
                }
            }
        } else {
            $url = $_GET['url'];
        }
        if ($url{0} == '/') {
            $url = substr($url, 1);
        }
        return $url;
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
     * Get controller to use, either plugin controller or application controller
     *
     * @param array $params Array of parameters
     * @return mixed name of controller if not loaded, or object if loaded
     * @access private
     */
    private function &__getController() {

            require_once('app/app_controller.php');


        $controller = false;
        $ctrlClass = $this->__loadController($this->params);
        if (!$ctrlClass) {
            return $controller;
        }
        $ctrlClass .= 'Controller';
        if (class_exists($ctrlClass)) {
            $controller = new $ctrlClass();
        }
        return $controller;
    }

    /**
     * Load controller and return controller classname
     *
     * @param array $params Array of parameters
     * @return string|bool Name of controller class name
     * @access private
     */
    private function __loadController($params) {
        $controller = null;

        if (!empty($params['controller'])) {

            $controller_file = @Paths::findPath('controller',$params['controller'].'_controller.php');
            if ($controller_file == null) {
                header('HTTP/1.0 404 Not Found', true, 404);
                die('Controller ' . @Inflector::camelize($params['controller']) . ' has not been found');
            }

            require_once($controller_file);

            $controller = @Inflector::camelize($params['controller']);

            return $controller;
        } else
            return false;







    }
}