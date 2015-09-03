<?php
/**
 * Class ModuleService
 */
abstract class ModuleService
{
    private $setting = array(
        'db' => 'MySQL'
    );

    function __construct()
    {
        $this->loadConfig();
        //$this->dataLayer = new MysqlModel($this->setting);
    }

    final private function loadConfig()
    {

    }

    /**
     * will load model class from an existing file
     * @param $name
     */
    private function loadModel($modelName = null)
    {
        $model_file = Paths::findPath('model', Inflector::underscore($modelName) . ".php");

        if ($model_file !== null) {
            include_once $model_file;
            $this->{$modelName . 'Model'} = new $modelName($this);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $method
     * @param $arguments
     * @return bool
     */
    function __call($method, $arguments)
    {
        if (substr($method, 0, 9) == 'loadModel') {
            $name = substr($method, 9) . 'Model';
            $this->{$name} = $this->__getDBClass($name, $this->setting);
            return true;
        }

        if (substr($method, 0, 13) == 'loadComponent') {
            $name = substr($method, 13) . 'Component';
            $this->{$name} = new $name();
            return true;
        }
    }

    function __getDBClass($name, $setting)
    {
        switch (strtolower($setting['db'])) {
            case 'mysql':
                return new MysqlModel($this,$name, $this->setting);
                break;
        }
    }

    function renderIndex($module = null, $client_id = null, $scheme_id = null)
    {
        $condition = array();
        $fields = "";
        $client_id = 1;
        $scheme_id = 1;
        $settings = $this->renderIndexGetSettings($module);
        $buttonsPossibility = $this->renderIndexModulPossibility($settings); //Add the default setting for buttons

        $this->{"loadModel".$module}();

//        pr($settings);
//        pr($buttonsPossibility);


        if(!is_array($settings) && empty($settings)) {
            die("render_index component error. Settings is empty check your modul xml.");
        }

        unset($settings["index"]["bindModels"]["comment"]);

        // Binding models
        if(isset($settings["index"]["bindModels"]) && !empty($settings["index"]["bindModels"])) {

            $bind = array();
            foreach($settings["index"]["bindModels"] as $model => $value) {
                $bind[$model] = $value;
            }
            $this->{$module."Model"}->bindModel($bind);
        }

        // Conditions
        if(isset($settings["index"]["condition"]) && !empty($settings["index"]["condition"])) {
            $conditions = $settings["index"]["condition"];

            foreach ($conditions as $key => $val) {
                if(strtoupper($key) == "OR") {
                    foreach($val as $table => $arr) {
                        if(is_array($arr)) {
                            foreach($arr as $or) {
                                $condition["OR"][$table] = $or;
                            }
                        } else {

                        }
                    }
                } else if(strtoupper($key) == "SQL") {
                    $condition[] = $val;
                } else {
                    if(!isset($val["value"])) {
                        foreach($val as $operator => $arr) {
                            if(strtoupper($operator) == "IN") {
                                $condition[$key] = $arr["value"];
                            }
                        }
                    } else {
                        $condition[$key] = $val["value"];
                    }
                }
            }
        }

        // Fields
        if($settings["index"]["primaryKey"] == "true") {
            $fields .= ($fields == "") ? $module."Model.id" : ",".$module."Model.id";
        }

        // SQL Fields
        if (isset($settings["index"]["sql_fields"]['item']) && count($settings["index"]["sql_fields"]['item']) != 0) {
            $fields = $settings["index"]["sql_fields"]['item'];
        } else {
            foreach ($settings["index"]["columns"]["column"] as $column) {
                foreach ($column["field"] as $field) {
                    if (is_array($field)) {
                        $fields .= ($fields == "") ? implode(",", $field) : "," . implode(",", $field);
                    } else {
                        $fields .= ($fields == "") ? $field : "," . $field;
                    }
                }
            }
        }

//

//        $result =  $this->paginationStart($condition, array('show' => 10, 'url' => 'moduleURL'), $module);
//        list($order, $limit, $page, $paging) = $this->paginationStart($condition, array('show' => 10, 'url' => 'moduleURL'), $module);
//
//        pr($conditions);
//        list($order, $limit, $page, $paging) = $result;
//        pr($result);
//        die('dieeee');
        $items = $this->{$module."Model"}->find("all", array(
            "conditions" => $condition,
            "fields" => $fields,
//            "order" => $order,
//            "page" => $page,
//            "limit" => $limit
        ));

        return array("setting"=>$settings,"items"=>$items,"buttonsPossibility"=>$buttonsPossibility,"model"=>$module."Model");
    }

    /**
     * Get the current module settings
     *
     * Part of the renderIndex
     */
    private function renderIndexGetSettings($module = null)
    {

        $path = null;

        if(file_exists(Paths::findRoot()."modules/".$module."/".$module.".xml")) {
            $path = Paths::findRoot()."modules/".$module."/".$module.".xml";
        }

        if($path !== null){
            ob_start();
                require_once($path);
            $xml = ob_get_clean();

            $xml = simplexml_load_string($xml);
            $json = json_encode($xml);
            $setting = json_decode($json,TRUE);
            $setting = $setting["setting"];

            if(array_keys($setting) !== range(0, count($setting) - 1)) {
                return $setting;
            }

            for($i = 0; $i < count($setting); $i++) {
                if($setting[$i]["@attributes"]["data-name"] == $this->controller->params["controller"]) {
                    $setting = $setting[$i];
                    break;
                }
            }

            return $setting;
        }

        return false;
    }

    /**
     * This function will make the basic buttons (edit,delete etc). This information need for helper.
     *
     * Part of the renderIndex
     * @param null $setting
     */
    private function renderIndexModulPossibility($setting = null)
    {
        global $possibility;
        global $possibility_options;
        global $possibility_style;

        $possibility = array();
        $possibility_options = array();
        $possibility_style = $setting["possibility_style"];

        if(isset($setting["possibility"])) {
            $possibility = $setting["possibility"];
        }

        if(array_keys($setting["index"]["top_actions"]["action"]) !== range(0, count($setting["index"]["top_actions"]["action"]) - 1)) {
            $setting["index"]["top_actions"]["action"] = array($setting["index"]["top_actions"]["action"]);
        }
        if(array_keys($setting["index"]["row_actions"]["action"]) !== range(0, count($setting["index"]["row_actions"]["action"]) - 1)) {
            $setting["index"]["row_actions"]["action"] = array($setting["index"]["row_actions"]["action"]);
        }

        $possibility_options = array("top"=>$setting["index"]["top_actions"]["action"],"row"=>$setting["index"]["row_actions"]["action"]);
        return $possibility_options;
    }

    //extend the function by using xml to define the enc is important for the function/not
    private function renderIndexCheckEncrypt() {
        $enc_id = $this->enc_id;
        if(!empty($enc_id) && strlen($enc_id) <= 44) {
            $dec = explode("-",encrypt_decrypt("decrypt",$enc_id));

            if(count($dec) != 2) {
                return false;
            } else {
                $dec[1] = (int)$dec[1];
                return $dec;
            }
        } else {
            return false;
        }
    }

    public function paginationStart($conditions = array(), $params = array(), $module = null)
    {

        /**
         * Set default settings
         */
//        if (!isset( $this->controller->{$this->modelClass})) {
//            $this->controller->loadModel($this->modelClass);
            $this->{"loadModel".$module}();
//        }



        $paging = array(
            'page' => 1,
            'pageCount' => 0,
            'show' => 10,
            'total' => 0,
            'sortBy' => $this->{$module."Model"}->primaryKey,
            'sortByClass' => $module.'Model',
            'direction' => 'ASC',
            'group' => null
        );

        foreach ($params as $key => $param) {
            if ($key == 'order')
                $this->paginationSetOrder($param, $paging);
            else
                $paging[$key] = $param;
        }

        $to_extract = array('show', 'sortBy', 'direction', 'page', 'sortByClass');
        $params = $params['url'];
        $paging = am($paging, $params);

        foreach ($to_extract as $param){
            if (isset($params['url'][$param]) && !empty($params['url'][$param])){
                $paging[$param] = $params['url'][$param];
            }
        }

//        pr($conditions);


        if (isset($total)) {
            $count = $total;
        } else {
            $this->{$module."Model"}->simulation_count = true;
            $count = $this->{$module."Model"}->find('first', array(
                'fields' => array(
                    'COUNT( DISTINCT '.$this->{$module."Model"}->name.'.'.$this->{$module."Model"}->primaryKey.') as pocet'),
                'conditions' => $conditions));
            $this->{$module."Model"}->simulation_count = false;
            $count = $count[0]['pocet'];
        }

        /**
         * Check page
         */
        if ((($paging['page'] - 1) * $paging['show']) >= $count) {
            $paging['page'] = floor($count / $paging['show'] + 0.99);
        }

        if ($paging['page'] == 0){
            $paging['page'] = 1;
        }

        $paging['total'] = $count;
        $paging['pageCount'] = ceil($count / $paging['show']);

        $order = $paging['sortByClass'] . "." . $paging['sortBy'] . ' ' . strtoupper($paging['direction']);
        return(array($order, $paging['show'], $paging['page'], $paging));

    }

    /**
     * set orders
     * Exactly as :  Model.column direction(
     * Direction - ASC/DESC
     */
    private function paginationSetOrder($order, &$paging) {
        list($ModelCol, $direction) = explode(' ', $order);
        list($model, $col) = explode('.', $ModelCol);
        $paging['direction'] = $direction;
        $paging['sortByClass'] = ucfirst($model);
        $paging['sortBy'] = strtolower($col);
        $paging['group'] = null;
    }
}



//    public function getClassName()
//    {
//        return $this->className;
//    }
//
//    public function getMethodList()
//    {
//        return $this->arrMethods;
//    }

// Constructor which MUST be called from derived Class Constructor
//    protected function __construct($strDerivedClassName)
//    {
//        $oRefl = new ReflectionClass($strDerivedClassName);
//        if (is_object($oRefl) && isset($this->methods) && count($this->methods) > 0) {
//            $this->className = $oRefl->getName();
//            $arrMethods = $oRefl->getMethods();
//
//            foreach ($arrMethods as $method) {
//                $in_array = in_array($method->getName(), $this->methods);
//
//                if ($in_array === true) {
//                    $callerFncName = array_search($method->getName(), $this->methods);
//                    if ($callerFncName) {
//
//                        $this->arrMethods[$callerFncName] = array(
//                            'module' => $this->className,
//                            'action' => $method->getName(),
//                            'params' => array()
//                        );
//                        $params = $method->getParameters();
//                        foreach ($params as $param) {
//                            $this->arrMethods[$callerFncName]['params'][] = $param->getName();
//                        }
//                    }
//                }
//            }
//
//        }
//    }
//}
