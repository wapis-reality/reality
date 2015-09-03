<?php

/**
 * Class ModulesHolder
 */
class ModulesHolder {

    /**
     * @var
     */
    static $modules;

    /**
     * @var Singleton
     */
    private static $instance;

    function __constructor(){
        self::$modules = new stdClass();
    }

    /**
     * @return ServiceRegistry|Singleton
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

/**
 * Class ServiceRegistry
 */
class ServiceRegistry
{
    static $registry = array();
    const setting_path = 'configs/service_registry/services.xml';

    /**
     *
     * @var Singleton
     */
    private static $instance;

    /**
     * @return ServiceRegistry|Singleton
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     *
     */
    private function __construct()
    {
        $xml = simplexml_load_file(self::setting_path);
        foreach ($xml as $service) {
            self::$registry = am(
                self::$registry,
                array((string)$service->name => array(
                        'component' => (string)$service->component,
                        'method' => (string)$service->method,
                        'parameters' => $service->parameters
                    )
                )
            );
        }
    }

    /**
     * @param $class
     * @return bool
     */
    static public function initClass($class)
    {
        $list = $class->getMethodList();
        if (isset($list) && count($list) > 0) {
            self::$registry = am(self::$registry, $list);
            return true;
        } else {
            return null;
        }
    }

    /**
     *
     */
    function __toString()
    {
        pr(self::$registry);
    }

    /**
     * @return array
     */
    static public function getList()
    {
        return self::$registry;
    }

    /**
     * @param $self
     * @param $actionName
     * @param array $params
     * @return mixed
     */
    static public function run($actionName, $params = array())
    {
        if (isset(self::$registry[$actionName])) {
            $action = self::$registry[$actionName];
            $componentName = $action['component'];

            if (self::existComponent($componentName)) {
                if(is_object($params)) {
                    $params = convertObject2Array($params);
                } else if(is_string($params)) {
                    $params = array($params);
                }

                return call_user_func_array(array(ModulesHolder::$modules->{$componentName . 'Module'}, $action['method']), $params);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $self
     * @param $componentName
     * @param bool|true $autoLoad
     * @return bool
     */
    static private function existComponent($componentName, $autoLoad = true)
    {
        if (isset(ModulesHolder::$modules->{$componentName . 'Module'})) {
            return true;
        } else {
            if ($autoLoad === true) {
                return self::loadComponent($componentName);
            } else {
                return false;
            }
        }
    }

    /**
     * @param $self
     * @param null $componentName
     * @return bool
     */
    static private function loadComponent($componentName = null)
    {
        if (ModulesHolder::$modules === null){
            ModulesHolder::$modules = new stdClass();
        }

        if ($componentName === null) {
            return false;
        } else {
            $moduleNewName = $componentName . 'Module';
           // $file = './components/' . $componentName . '.php';
            //include_once $file;

            ModulesHolder::$modules->{$moduleNewName} = new $moduleNewName();
            return true;
        }
    }

    /**
     * @param $module
     * @param $function
     */
    static public function add($module, $function)
    {

    }

    /**
     * @param $module
     * @param $function
     */
    static public function remove($module, $function)
    {

    }
}