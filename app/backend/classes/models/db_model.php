<?php
class DbModel implements DataSourceLayerInterface
{
    var $bindModel = array();
    var $connection_link;
    var $debug = false;
    var $name = null;
    var $setting = null;
    var $primaryKey = 'id';
    var $tableName = '';
    var $schema = null;
    var $foreign_schema = null;
    var $__sqlOps = array('like', 'ilike', 'or', 'not', 'in', 'between', 'regexp', 'similar to');
    var $bindModels = array();
    private $map = array();


    var $sql_query_templates = array(
        'select' => 'SELECT {fields} FROM {table} {bind} {where} {group} {order} {limit} ',
        'insert' => 'INSERT INTO {table} ({where}) VALUES ({what})',
        'update' => 'UPDATE {table} SET {what} WHERE {where} LIMIT {limit}',
        'delete' => 'DELETE FROM {table} {where}',
        'select_limit' => 'LIMIT {FROM},{TO}'
    );

    public function __construct(&$controller, $name, $setting)
    {
        $this->controller = $controller;
        $this->name = $name;
        $this->tableName = isset($setting->useTable)?$setting->useTable: (Inflector::pluralize(Inflector::underscore(substr($this->name,0,-5))));

        $this->setting = $setting;
        $this->connect();
        $this->schema = $this->loadSchema();
    }

    function loadSchema($table = null){

        $out = array();

        if($table == null) {
            $table = $this->tableName;
        }


        $result = $this->query('DESCRIBE ' . $table);
        if ($result) {
            while ($columns = $this->__fetch_row($result)) {
                $out[$columns[0]] = array(
                    'type' => $columns[1]
                );
            }
        }

        return $out;
    }

    public function connect(){
        $this->connection_link = $this->__call_connect($this->set['host'], $this->set['login'], $this->set['password']);
        if (!$this->connection_link) {
            //log::write('Could not connect: ' . $this->__call_error($this->connection_link));
            die('Could not connect: ' . $this->__call_error($this->connection_link));
        }

        $db_selected = $this->__call_select_db($this->connection_link, $this->set['database']);
        if (!$db_selected) {
            die('Can\'t use database : ' . $this->__call_error($this->connection_link));
        }
    }

    protected function fill_univerzal_fields($fields)
    {

        if (trim($fields) != "*") {
            preg_match_all("/(([a-zA-Z_0-9]+|`[a-zA-Z\._0-9]+`)\.)(\*)( +AS +([a-zA-Z_0-9]+|`[a-zA-Z\._0-9]+`))?([\+\-\*%\/=, \)]+)/U", $fields . " ", $out, PREG_SET_ORDER);
            foreach ($out AS $finded) {
                $_model = str_replace("`", "", $finded[2]);
                $universum = $finded[3];
                $_fields = array();
                if ($_model == $this->name) {
                    $schema = $this->schema;
                } else if (count($this->foreign_schema[$_model])) {
                    $schema = $this->foreign_schema[$_model];
                }
                foreach ($schema AS $col) {
                    $_fields[] = $_model . "." . (string)$col;
                }

                $fields = str_replace("{$finded[2]}.{$finded[3]}", implode(", ", $_fields), $fields);

            }
            return $fields;
        } else {
            $_fields = array();
            foreach (array_keys($this->schema) AS $col) {
                $_fields[] = $this->name . "." . (string)$col;
            }

            if(!empty($this->foreign_schema)) {
                foreach ($this->foreign_schema AS $model => $schema) {
                    foreach ($schema AS $col => $p) {
                        $_fields[] = $model . "." . (string)$col;
                    }
                }
            }
            return implode(", ", $_fields);
        }
    }

    /**
     * Creates a WHERE clause by parsing given conditions array. Used by DboSource::conditions().
     *
     * @param array $conditions Array or string of conditions
     * @param boolean $quoteValues If true, values should be quoted
     * @param Model $model A reference to the Model instance making the query
     * @return string SQL fragment
     * @access public
     */
    function conditionKeysToString($conditions, $quoteValues = true, $model = null)
    {
        $c = 0;
        $out = array();
        $data = $columnType = null;
        $bool = array('and', 'or', 'not', 'and not', 'or not', 'xor', '||', '&&');

        foreach ($conditions as $key => $value) {
            $join = ' AND ';
            $not = null;

            if (is_array($value)) {
                $valueInsert = (
                    !empty($value) &&
                    (substr_count($key, '?') == count($value) || substr_count($key, ':') == count($value))
                );
            }

            if (is_numeric($key) && empty($value)) {
                continue;
            } elseif ($value != 0 && empty($value)) { //IS NULL CONDITION ADDED
                $out[] = trim($key) . ' IS NULL';
            } elseif ($value === 'NOT NULL') { //IS NOT NULL CONDITION ADDED
                $out[] = trim($key) . ' IS NOT NULL';
            } elseif (is_numeric($key) && is_string($value)) {
                $out[] = $not . $this->__quoteFields($value); //line before change add mysqli_escape_string
                //                $value = $this->__quoteFields($value);
                //                $value = mysqli_escape_string($this->link,$value);
                //                $out[] = $not . $value;
            } elseif ((is_numeric($key) && is_array($value)) || in_array(strtolower(trim($key)), $bool)) {
                if (in_array(strtolower(trim($key)), $bool)) {
                    $join = ' ' . strtoupper($key) . ' ';
                } else {
                    $key = $join;
                }
                $value = $this->conditionKeysToString($value, $quoteValues, $model);
                if (strpos($join, 'NOT') !== false) {
                    if (strtoupper(trim($key)) == 'NOT') {
                        $key = 'AND ' . trim($key);
                    }
                    $not = 'NOT ';
                }

                if (empty($value[1])) {
                    if ($not) {
                        $out[] = $not . '(' . $value[0] . ')';
                    } else {
                        $out[] = $value[0];
                    }
                } else {
                    $out[] = '(' . $not . '(' . implode(') ' . strtoupper($key) . ' (', $value) . '))';
                }
            } else {
                if (is_object($value) && isset($value->type)) {
                    if ($value->type == 'identifier') {
                        $data .= $this->name($key) . ' = ' . $this->name($value->value);
                    } elseif ($value->type == 'expression') {
                        if (is_numeric($key)) {
                            $data .= $value->value;
                        } else {
                            $data .= $this->name($key) . ' = ' . $value->value;
                        }
                    }
                } elseif (is_array($value) && !empty($value) && !$valueInsert) {
                    $keys = array_keys($value);
                    if ($keys === array_values($keys)) {
                        $count = count($value);
                        if ($count === 1) {
                            $data = $this->__quoteFields($key) . ' = (';
                        } else {
                            $data = $this->__quoteFields($key) . ' IN (';
                        }
                        if ($quoteValues) {
                            if (is_object($model)) {
                                $columnType = $model->getColumnType($key);
                            }
                            $data .= implode(', ', $this->value($value, $columnType));
                        }
                        $data .= ')';
                    } else {
                        $ret = $this->conditionKeysToString($value, $quoteValues, $model);
                        if (count($ret) > 1) {
                            $data = '(' . implode(') AND (', $ret) . ')';
                        } elseif (isset($ret[0])) {
                            $data = $ret[0];
                        }
                    }
                } elseif (is_numeric($key) && !empty($value)) {
                    $data = $this->__quoteFields($value);
                } else {
                    $data = $this->__parseKey($model, trim($key), $this->__escape_string($this->connection_link,$value));
                }

                if ($data != null) {
                    $out[] = $data;
                    $data = null;
                }
            }
            $c++;
        }
        return $out;
    }

    /**
     * Extracts a Model.field identifier and an SQL condition operator from a string, formats
     * and inserts values, and composes them into an SQL snippet.
     *
     * @param Model $model Model object initiating the query
     * @param string $key An SQL key snippet containing a field and optional SQL operator
     * @param mixed $value The value(s) to be inserted in the string
     * @return string
     * @access private
     */

    function __parseKey(&$model, $key, $value)
    {

        $operatorMatch = '/^((' . implode(')|(', $this->__sqlOps);
        $operatorMatch .= '\\x20)|<[>=]?(?![^>]+>)\\x20?|[>=!]{1,3}(?!<)\\x20?)/is';
        $bound = (strpos($key, '?') !== false || (is_array($value) && strpos($key, ':') !== false));

        if (!strpos($key, ' ')) {

            $operator = '=';

        } else {

            list($key, $operator) = explode(' ', trim($key), 2);



            if (!preg_match($operatorMatch, trim($operator)) && strpos($operator, ' ') !== false) {

                $key = $key . ' ' . $operator;

                $split = strrpos($key, ' ');

                $operator = substr($key, $split);

                $key = substr($key, 0, $split);

            }

        }



        $virtual = false;

        if (is_object($model) && $model->isVirtualField($key)) {

            $key = $this->__quoteFields($model->getVirtualField($key));

            $virtual = true;

        }



        $type = (is_object($model) ? $model->getColumnType($key) : null);



        $null = ($value === null || (is_array($value) && empty($value)));



        if (strtolower($operator) === 'not') {

            $data = $this->conditionKeysToString(

                array($operator => array($key => $value)), true, $model

            );

            return $data[0];

        }



        $value = $this->value($value, $type);



        if (!$virtual && $key !== '?') {

            $isKey = (strpos($key, '(') !== false || strpos($key, ')') !== false);

            $key = $isKey ? $this->__quoteFields($key) : $this->name($key);

        }



        if ($bound) {

            return String::insert($key . ' ' . trim($operator), $value);

        }



        if (!preg_match($operatorMatch, trim($operator))) {

            $operator .= ' =';

        }

        $operator = trim($operator);



        if (is_array($value)) {

            $value = implode(', ', $value);



            switch ($operator) {

                case '=':

                    $operator = 'IN';

                    break;

                case '!=':

                case '<>':

                    $operator = 'NOT IN';

                    break;

            }

            $value = "({$value})";

        } elseif ($null) {

            switch ($operator) {

                case '=':

                    $operator = 'IS';

                    break;

                case '!=':

                case '<>':

                    $operator = 'IS NOT';

                    break;

            }

        }

        if ($virtual) {

            return "({$key}) {$operator} {$value}";

        }

        return "{$key} {$operator} {$value}";

    }



    /**
     * Prepares a value, or an array of values for database queries by quoting and escaping them.
     *
     * @param mixed $data A value or an array of values to prepare.
     * @param string $column The column into which this data will be inserted
     * @param boolean $read Value to be used in READ or WRITE context
     * @return mixed Prepared value or array of values.
     * @access public
     */
    function value($data, $column = null, $read = true)
    {
        if (is_array($data) && !empty($data)) {
            return array_map(
                array(&$this, 'value'),
                $data, array_fill(0, count($data), $column), array_fill(0, count($data), $read)
            );
        } elseif (is_object($data) && isset($data->type)) {
            if ($data->type == 'identifier') {
                return $this->name($data->value);
            } elseif ($data->type == 'expression') {
                return $data->value;
            }
        } else {
            return "'$data'";
        }
    }

    function name($data)
    {
        if (is_object($data) && isset($data->type)) {
            return $data->value;
        } else {
            return $data;
        }
    }

    /**
     * Quotes Model.fields
     *
     * @param string $conditions
     * @return string or false if no match
     * @access private
     */
    function __quoteFields($conditions)
    {
        $start = $end = null;
        $original = $conditions;

        if (!empty($this->startQuote)) {
            $start = preg_quote($this->startQuote);
        }
        if (!empty($this->endQuote)) {
            $end = preg_quote($this->endQuote);
        }
        $conditions = str_replace(array($start, $end), '', $conditions);
        $conditions = preg_replace_callback('/(?:[\'\"][^\'\"\\\]*(?:\\\.[^\'\"\\\]*)*[\'\"])|([a-z0-9_' . $start . $end . ']*\\.[a-z0-9_' . $start . $end . ']*)/i', array(&$this, '__quoteMatchedField'), $conditions);
        if ($conditions !== null) {
            return $conditions;
        }
        return $original;
    }

    /**
     * Auxiliary function to quote matches `Model.fields` from a preg_replace_callback call
     *
     * @param string matched string
     * @return string quoted strig
     * @access private
     */
    function __quoteMatchedField($match)
    {
        if (is_numeric($match[0])) {
            return $match[0];
        }
        return $this->name($match[0]);
    }

    private function convert_fields($fields)
    {
        if (is_string($fields)) {
            return $fields = $this->fill_univerzal_fields($fields);
        } else if (is_null($fields)) {
            return '*';
        } else if (is_array($fields)) {
            return $fields = $this->fill_univerzal_fields(implode(", ", $fields));
        } else {
            die('Problem with a ConvertField');
        }
    }

    private function limit($limit, $offset = null)
    {
        if ($limit) {
            $rt = '';
            if (!strpos(strtolower($limit), 'limit') || strpos(strtolower($limit), 'limit') === 0) {
                $rt = ' LIMIT';
            }

            if ($offset) {
                $rt .= ' ' . $offset . ',';
            }

            $rt .= ' ' . $limit;
            return $rt;
        }
        return null;
    }

    public function find($type = 'all', $data = array())
    {
        $conditions = array();
        $fields = array('*');
        $order = "{$this->name}.{$this->primaryKey} ASC";
        $limit = 10000;
        $page = 1;
        $sql = "";
        $out = array();
        extract($data);

        $this->queryData = array(
            'conditions' => $conditions,
            'fields' => $fields,
            'limit' => $limit,
            'page' => $page,
            'order' => $order,
            'group' => isset($group) ? $group : null,
            'having' => isset($having) ? $having : null,
            'type' => $type
        );

        switch ($type) {
            case 'first':
                $limit = 1;
                break;
            case 'list':
                $fields = (count($fields) != 2) ? array("{$this->name}.{$this->primaryKey}", "{$this->name}.name") : $fields;
                break;
            case 'count':
                $fields = "COUNT(*)";
                $limit = null;
                break;
        }

        $SqlFields = $this->convert_fields($fields);

        if (isset($this->queryData['group']) && (is_string($this->queryData['group']) || is_array($this->queryData['group']))) {
            $SqlGroup = " GROUP BY " . $this->convert_fields($this->queryData['group']);
        }
        if (is_string($this->queryData['order']) || is_array($this->queryData['order'])) {
            $SqlOrder = " ORDER BY " . $this->convert_fields($this->queryData['order']);
        }
        if (isset($this->queryData['having']) && (is_string($this->queryData['having']) || is_array($this->queryData['having']))) {
            $SqlHaving = " HAVING " . $this->convert_fields(implode(' AND ', $this->queryData['having']));
        }

        $conditions = $this->conditionKeysToString($this->queryData['conditions']);
        if (count($conditions) > 0) {
            $SqlCondition = " WHERE  " . $this->convert_fields(implode(' AND ', $conditions)) . "";
        }

        $SqlLimit = $this->limit($limit, ($page - 1) * $limit);

        if (count($this->bindModels) > 0) {
            foreach ($this->bindModels as $modelName => $setting) {

                if (!isset($setting['type']))
                    $setting['type'] = 'belongsTo';

                switch ($setting['type']) {
                    case 'belongsTo':
                        // DORESIT
                        $con_on = array("{$modelName}.id = {$this->name}." . Inflector::underscore($modelName) . "_id");
                        break;
                    case 'hasOne':
                        $foreignKey = (isset($setting['foreignKey'])) ? $setting['foreignKey'] : Inflector::underscore($this->name) . "_id";
                        $con_on = array("{$this->name}.id = {$modelName}." . $foreignKey);
                        break;
                    case 'joinSpec':
                        $con_on = array($setting['primaryKey'] . ' = ' . $setting['foreignKey']);
                        break;
                    default:
                        $con_on = array();
                        break;
                }

                if ($setting['type'] != 'hasMany') {

                    if (isset($setting['conditions']))
                        $con_on = am($con_on, self::conditionKeysToString($setting['conditions']));

                    $clasname = isset($setting['className']) ? $setting['className'] : $modelName;

                    //add property type of join (left, right, inner)
                    if (!key_exists('joinSide', $setting)) {
                        $setting['joinSide'] = 'LEFT';

                    } else {
                        $setting['joinSide'] = strtoupper($setting['joinSide']);

                        if (!in_array($setting['joinSide'], array('LEFT', 'RIGHT', 'INNER'))) {
                            $setting['joinSide'] = 'LEFT';
                        }
                    }

                    $oModelik = false;
                    $bForcedTableName = false;
                    $bFoundModelClass = true;

//                    $model_file = Paths::findPath('model', Inflector::underscore($modelName) . ".php");
//
//                    if ($model_file !== null) {
//                        require_once($model_file);
//                    }

//                    if (file_exists( 'models/' . strtolower($modelName) . '.php')) {
//                        $bFoundModelClass = true;
//                        include_once( 'models/' . strtolower($modelName) . '.php');
//                    } elseif (file_exists(ROOT . 'app/models/' . strtolower($modelName) . '.php')) {
//                        $bFoundModelClass = true;
//                        include_once(ROOT . 'app/models/' . strtolower($modelName) . '.php');
//                    }

//                    if ($bFoundModelClass) {
                        $sTempController = 1;
//                        $oModelik = new $modelName($sTempController);

//                        if (is_object($oModelik)) {
//                            if (property_exists($oModelik, 'useTable')) {
//                                $bForcedTableName = $oModelik->useTable;
//                            }
//                        }
//                    }

                    $sql .= " {$setting['joinSide']} JOIN " . "" . ($bForcedTableName != false ? $bForcedTableName : Inflector::pluralize(Inflector::underscore($clasname))) . " AS `{$modelName}` ON (" . implode(' AND ', $con_on) . ")";
                    unset($oModelik);
                }
            }
        }

        $sql = strtr($this->sql_query_templates['select'],array(
            '{fields}' => $SqlFields,
            '{table}' => $this->tableName . ' AS ' . $this->name,
            '{bind}' => $sql,
            '{where}' => isset($SqlCondition)?$SqlCondition:'',
            '{group}' => isset($SqlGroup)?$SqlGroup:'',
            '{order}' => isset($SqlOrder)?$SqlOrder:'',
            '{limit}' => isset($SqlLimit)?$SqlLimit:'',
        ));


        $result = $this->query($sql);


        $out = array();
        switch ($type) {
            case 'all':
            case 'list':
                while ($item = $this->fetchResult($result)) {
                    $out[] = $item;
                }
                if ($type == 'list') {
                    $out = Set::combine($out, '{n}.' . $fields[0], '{n}.' . $fields[1]);
                }
                break;
            case 'count':
                $total_rows = $this->__fetch_row($result);
                return $total_rows[0];
                break;
            case 'first':
                $out = $this->fetchResult($result);
                //Commented this two line we don't need trans
//                if (count($this->_bindTrans) > 0)
//                    $out = $this->afterFindTranBind($out);

                break;
        }

        foreach ($this->bindModels as $modelName => $setting) {
            $primaryKey = (isset($setting['primaryKey']) ? $setting['primaryKey'] : $this->primaryKey);
            if (strpos($primaryKey, '.') != false) {
                list($mdlP, $primaryKey) = explode('.', $primaryKey);
            }

            if (strpos(@$setting['foreignKey'], '.') != false) {
                list($mdlF, $foreignKey) = explode('.', $setting['foreignKey']);
            }
            if ($setting['type'] == 'hasMany' && count(Set::extract("/{$this->name}/{$primaryKey}", $out))) {


                if (!isset($this->controller->{$modelName."Model"}))
                    $this->controller->{'loadModel' . $modelName}();

                // natahnuti bind pro hasManyJoinSpec
                foreach ($this->bindModels as $hMjS_Model => $hMjS_setting) {
                    if ($hMjS_setting['type'] == 'HasManyJoinSpec' && $hMjS_setting['model'] == $modelName) {
                        $this->controller->{$modelName."Model"}->bindModel(
                            array(
                                $hMjS_Model => array(
                                    'type' => 'joinSpec',
                                    'foreignKey' => $hMjS_setting['foreignKey'],
                                    'primaryKey' => $hMjS_setting['primaryKey'],
                                    'conditions' => isset($hMjS_setting['conditions'])
                                        ? $hMjS_setting['conditions'] : array()
                                )
                            )
                        );
                    }
                }
                //$this->controller->$modelName->debug = true;
                $this->controller->{'loadModel' . $modelName}();


                $data = $this->controller->{$modelName."Model"}->find(
                    'all',
                    array(
                        'fields' => (isset($setting['fields']) ? $setting['fields'] : array('*')),
                        'conditions' => am(
                            isset($setting['conditions']) ? $setting['conditions'] : array(),
                            array("`{$modelName}"."Model`".".`{$foreignKey}` IN (" . implode(', ', Set::extract("/{$this->name}/{$primaryKey}", $out)) . ")")
                        )
                    )
                );

                if ($type == 'first') {

                    if (!isset($out[$modelName."Model"]))
                        $out[$modelName] = array();

                    foreach ($data as $bindItem) {
                        $out[$modelName."Model"][] = $bindItem[$modelName."Model"];
                    }
                } else if ($type == 'all') {

                    foreach ($out as &$o) {

                        if (!isset($o[$modelName."Model"]))
                            $o[$modelName."Model"] = array();

                        foreach ($data as $bName => $bindItem) {

                            if ($bindItem[$modelName."Model"][$foreignKey] == $o[$this->name][$primaryKey]) {

                                $add = $bindItem[$modelName."Model"];

                                unset($bindItem[$modelName."Model"]);

                                $o[$modelName."Model"][] = am($add, $bindItem);
                            }
                        }
                    }
                }
            }
        }

//        mysql_free_result($result);

        return $out;
    }

    /**
     * @param $result
     * @return array|bool
     */
    function fetchResult(&$result)
    {
        $index = 0;
        // $j = 0;

//        if (is_resource($result)) {

            if (count($this->map) == 0) {

                while ($column = $this->__fetch_field($result)) {
                    $this->map[$index++] = array($column->table, $column->name);

                }

            }


            if ($row = $this->__fetch_row($result)) {
                $resultRow = array();
                $i = 0;
                foreach ($row as $index => $field) {

                    list($table, $column) = $this->map[$index];
                    $resultRow[empty($table) ? 0 : $table][$column] = $row[$index];
                    $i++;
                }

                return $resultRow;
            } else {
                return false;
            }
//        } else {
//            return false;
//        }
    }

    /**
     * @param null $fields
     * @param $id
     * @return array|bool
     */
    function read($fields = null, $id)
    {
        return $this->find('first', array(
            'fields' => $fields,
            'conditions' => array(
                "{$this->name}.{$this->primaryKey}" => $id
            )
        ));
    }

    public function query($sql = null)
    {
        if ($this->debug == true){
            pr($sql);
        }

        $this->last_sql = $sql;
       // Log::file($sql);
        $result = $this->__query($this->connection_link,$sql);
//        if (!$result) {
//            $sStr = "Invalid query: " . mysqli_error($this->connection_link) . "\n <br/>SQL:" . $sql ."\n<br/>".$this->name;
//            die('Invalid query: ' . $this->__call_error($this->connection_link). "\n <br/>SQL:" . $sql ."\n<br/>".$this->name);
//        }
        return $result;
    }

    /**
     * Set a Mysql variable to a defined value
     * @param null $name
     * @param null $value
     */
    public function set($name = null, $value = null)
    {
        if(empty($name) || empty($value)){
            return false;
        }

        $sql = "SET $name = $value";

        $this->query($sql);
        return true;
    }

    /**
     * Save the data or update it depends on primary key
     * @param array $data
     * @return bool|mysqli_result|null
     */
    public function save($data = array())
    {
//        return($data);
        if(!is_array($data) || empty($data)) {
            return false;
        }

        if (isset($data[$this->primaryKey]) && !empty($data[$this->primaryKey])) {


            $this->id = $data[$this->primaryKey];

            $data['updated'] = date('Y-m-d H:i:s');

            $data = array_intersect_key($data, $this->schema);

            return $this->_update($data);
        } else {

            $data['updated'] = $data['created'] = date('Y-m-d H:i:s');

            $data = array_intersect_key($data, $this->schema);

            $result = $this->_insert($data);

            $this->id = $this->__get_insert_id($this->connection_link);

            return $result;
        }
    }

    /**
     * Delete one element the id required
     * @param null $id
     * @param bool $shallow
     * @return bool|mysqli_result
     */
    public function delete($id = null, $shallow = false)
    {
        if ($id == null) { return false; }

        return $this->deleteAll(array($this->primaryKey => $id), $shallow);
    }


    /**
     * Delete everything where the conditions is true
     * @param array $conditions
     * @param bool $shallow
     * @return bool|mysqli_result
     */
    function deleteAll($conditions = array(), $shallow = false)
    {

        if(!is_array($conditions) || empty($conditions)) {
            return false;
        }
        $update = array();
        $update["{table}"] = $this->tableName;
        $conditions = $this->conditionKeysToString($conditions);

        if(count($conditions) > 0) {
            $update["{where}"] = "WHERE ".implode(' AND ', $conditions);
        }

        $sql = strtr($this->sql_query_templates['delete'],$update);

        return $this->query($sql);
    }

    /**
     * Insert data
     * @param array $data
     * @return bool|mysqli_result|null
     */
    public function _insert($data = array())
    {
        if(!is_array($data) && empty($data)) {
            return null;
        }

        $keys = array_keys($data);
        $values = array_values($data);

        $sql = strtr($this->sql_query_templates['insert'],array(
            '{table}' => $this->tableName,
            '{where}' => "`" . implode('`, `', $keys) . "`",
            '{what}' => "'" . implode("', '", $values) . "'",
        ));

        return $this->query($sql);
    }

    /**
     * Update data with condition
     * @param array $data
     * @return bool|mysqli_result|null
     */
    public function _update($data = array())
    {
        if(!is_array($data) && empty($data)) {
            return null;
        }

        foreach ($data as $col => $value) {
            if ($col != $this->primaryKey) {
                $sql_update[] = "`" . $col . "` = '" . $value . "'";
            }
        }

        $sql = strtr($this->sql_query_templates['update'],array(
            '{table}' => $this->tableName,
            '{what}' => implode(' ,', $sql_update),
            '{where}' => $this->primaryKey . "=" . $data[$this->primaryKey],
            '{limit}' => "1",
        ));


        return $this->query($sql);;
    }

    /**
     * bind model
     * @param $arr
     */
    public function bindModel($arr)
    {

        foreach ($arr as $model => $setting) {
            $this->bindModels[$model] = $setting;
            $this->load_foreign_schema($model);
        }
    }

    /**
     * @param $model
     */
    protected function load_foreign_schema($model)
    {
        $cols = array();

        if (isset($this->bindModels[$model]['className'])) {
            $modelClass = $this->bindModels[$model]['className'];
        } else {
            $modelClass = $model;
        }
        $tableName = Inflector::pluralize(Inflector::underscore($modelClass));
        $scheme = $this->loadSchema($tableName);

        if (!is_array($scheme)) {
            die("No scheme");
        }

        $this->foreign_schema[$model] = $scheme;
    }


//    private function __call_connect()
//    {
//        echo '11111111';
//    }

    private function __call_error()
    {

    }
}