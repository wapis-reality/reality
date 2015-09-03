<?php

class Model
{
    var $debug = false;
    var $useDbConfig = 'default';
    var $primaryKey = 'id';
    var $schema = array();
    var $foreign_schema = array();
    var $__sqlOps = array('like', 'ilike', 'or', 'not', 'in', 'between', 'regexp', 'similar to');
    public $con_kos = true;
    public $con_status = true;
    public $set = array();
    public $queryData = array();
    protected $link;
    public $use_schema = true;
    public $useTable = null;
    public $trans = array();
    var $create_trans = false;
    private $_bindTrans = array();
    private $type_of_find = null;
    var $select_lang = null;

    /* promenne pro bindModel */
    var $bindModels = array();

    /* Zda ma tahat useSetting ze Session, nebo z configu */
    var $self_db = false;

    /* Reference na controller, ktery vola dany model */
    var $controller = null;
    var $actions = array('ondelete' => array(), 'ontrash' => array());

    function __construct($controller, $bUseSchema = true)
    {
        if ($controller != false)
            $this->controller = $controller;

        /* Natahnuti seznamu nastaveni db */
        require_once(CONFIG . '/database.php');

        /**
         * Nastaveni udaju pro db_connect, jde o to, ze pokud nema model promenou self_db == true, tak
         * bude vytahovat nastaveni ze session, ktera vznikla v loginu, jinak je vezme z klasickeho nastaveni
         * dle modelu a nastaveni $useSetting /defautne "defaukl"/
         */
        $component_file = Paths::findPath('component', 'session.php');
        require_once($component_file);
        $session = new Session();
        $session->start();
        if ($session->check('localDbSetting') && $session->read('localDbSetting') && $this->self_db === false) {
            $dset = $session->read('localDbSetting');
            $dbset = new DATABASE_CONFIG();
            $this->set = $dbset->{$dset};
        } else {
            $dbset = new DATABASE_CONFIG();
            $this->set = $dbset->{$this->useDbConfig};
            unset($dbset);
        }
        unset($session);

        /* Natahnuti schematu z XML souboru daneho modelu */

        if ($this->use_schema === true) {
            if ($bUseSchema)
                self::load_schema();
        }

        /* initializace nazvu tabulky a nazvu tabulky i s prefixem */
        $this->table_name = ($this->useTable == null) ? (Inflector::pluralize(Inflector::underscore($this->name))) : $this->useTable;
        $this->full_table_name = $this->set['prefix'] . $this->table_name;

        /* Pripojeni k DB */
        self::connect_mssql();

        /* Nastaveni Charsetu */
        $this->query("SET NAMES 'utf8'");


    }

    /* @param $sql - string to be writen
     * @return true when write succesful or false in other case
     */

    function writeToFile($somecontent)
    {
        //log file
        $filename = 'data.txt';

        $somecontent .= $somecontent . "\n";
        if (is_writable($filename)) {
            if (!$handle = fopen($filename, 'a')) {
                return false;
            }
            if (fwrite($handle, $somecontent) === FALSE) {
                return false;
            }
            return true;
            fclose($handle);
        } else {
            return false;
        }
    }

    function bindModel($arr)
    {
        foreach ($arr as $model => $setting) {
            $this->bindModels[$model] = $setting;
            $this->load_foreign_schema($model);
        }
    }

    public function connect_mysql()
    {
        $this->link = mysql_connect($this->set['host'], $this->set['login'], $this->set['password']);

        if (!$this->link) {
            //log::write('Could not connect: ' . mysql_error());
            die('Could not connect: ' . mysql_error());
        }

        $db_selected = mysql_select_db($this->set['database'], $this->link);
        if (!$db_selected) {
            die('Can\'t use database : ' . mysql_error());
        }

        //mysql_close($link);
    }

    public function connect_mssql()
    {
        $this->link = mssql_connect($this->set['host'], $this->set['login'], $this->set['password']);

        if (!$this->link) {
            log::write('Could not connect: ' . mysql_error());
            //die('Could not connect: ' . mysql_error());
            die('Could not connect: ' );
        }

        $db_selected = mssql_select_db ($this->set['database'], $this->link);
        if (!$db_selected) {
            //die('Can\'t use database : ' . mysql_error());
            die('Can\'t use database : ');
        }

        //mysql_close($link);
    }


    function query($sql)
    {
        if ($this->debug == TRUE) {
            $re = $this->writeToFile($sql);
            echo 'DEBUG - ON<br/>';
            echo 'QUERY : ' . $sql;
        }

        $result = mysql_query($sql, $this->link);
        if (!$result) {
            $sStr = "Invalid query: " . mysql_error() . "\n" . $sql;
            if ($this->debug == TRUE)
                $this->writeToFile($sStr);
            die($sStr);
        }
        return $result;
    }

    function beforeSave()
    {

    }

    function beforeFind()
    {
        if (!CLIENTS::exist('multilang')) {
            $this->create_trans = false;
        }

    }

    function save($data)
    {
        $this->data = $data;
        $this->beforeSave();

        if (isset($this->data[$this->name]))
            $this->data = $this->data[$this->name];

        self::safe_data();

        if (isset($this->data[$this->primaryKey]) && !empty($this->data[$this->primaryKey])) {
            $this->id = $this->data[$this->primaryKey];
            $this->data['updated'] = date('Y-m-d H:i:s');
            $this->data = array_intersect_key($this->data, array_flip($this->schema));
            return self::_update();
        } else {
            $this->data['updated'] = $this->data['created'] = date('Y-m-d H:i:s');
            $this->data = array_intersect_key($this->data, array_flip($this->schema));
            $result = self::_insert();
            $this->id = mysql_insert_id();
            return $result;
        }
    }

    function _insert()
    {
        if (count($this->data)) {
            $keys = array_keys($this->data);
            $values = array_values($this->data);

            $sql = "INSERT INTO `" . $this->full_table_name . "` (`" . implode('`, `', $keys) . "`) VALUES ('" . implode("', '", $values) . "');";
            return self::query($sql);
        }
        return null;
    }

    function _update()
    {
        if (count($this->data)) {

            foreach ($this->data as $col => $value) {
                if ($col != $this->primaryKey)
                    $sql_update[] = "`" . $col . "` = '" . $value . "'";
            }

            $sql = "UPDATE `" . $this->full_table_name . "` SET " . implode(' ,', $sql_update) . " WHERE " . $this->primaryKey . "=" . $this->data[$this->primaryKey] . " LIMIT 1;";
            return self::query($sql);
        }
        return null;
    }

    function update($set, $conditions)
    {
        $set['updated'] = date('Y-m-d H:i:s');
        //        $this->data = array_intersect_key($this->data, array_flip($this->schema));
        $set = array_intersect_key($set, array_flip($this->schema));
        foreach ($set as $col => $value) {
            if ($col != $this->primaryKey)
                $sql_update[] = "`" . $col . "` = '" . $value . "'";
        }
        $sql = "UPDATE `" . $this->full_table_name . "` SET " . implode(' ,', $sql_update);
        $conditions = self::conditionKeysToString($conditions);
        if (count($conditions) > 0)
            $sql .= " WHERE  " . implode(' AND ', $conditions) . "";
        return self::query($sql);
    }

    function read($fields = null, $id)
    {
        return $this->find('first', array(
            'fields' => $fields,
            'conditions' => array(
                "{$this->name}.{$this->primaryKey}" => $id
            )
        ));
    }

    function safe_data()
    {
        foreach ($this->data as &$val) {
            $val = empty($val) ? '' : mysql_real_escape_string($val);
        }
    }

    function find($type = 'all', $data = array())
    {
        $conditions = array();
        $fields = array('*');
        $order = "{$this->name}.{$this->primaryKey} ASC";
        $limit = 10000;
        $page = 1;
        $recursive = 1;
        extract($data);


        if ($this->con_kos === true)
            $conditions["{$this->name}.kos"] = 0;

        if ($this->con_status === true)
            $conditions["{$this->name}.status"] = 1;

        $this->type_of_find = $type;
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

        $this->queryData = array(
            'conditions' => $conditions,
            'fields' => $fields,
            'limit' => $limit,
            'page' => $page,
            'order' => $order,
            'group' => $group,
            'having' => $having,
            'type' => $type
        );


        $this->_bindTrans = array();
        $this->beforeFind();

        //!!!must after bind translations !!!<o>-<o><o>-<o><o>-<o><o>-<o><o>-<o><o>-<o><o>-<o><o>-<o>
        $sql = "SELECT {$this->convert_fields($this->queryData['fields'])} FROM `{$this->full_table_name}` AS `{$this->name}`";

        if (isset($this->queryData['group']) && (is_string($this->queryData['group']) || is_array($this->queryData['group']))) {

            $SQLgroup = " GROUP BY " . $this->convert_fields($this->queryData['group']);
        }
        if (is_string($this->queryData['order']) || is_array($this->queryData['order'])) {
            $SQLorder = " ORDER BY " . $this->convert_fields($this->queryData['order']);
        }
        if (isset($this->queryData['having']) && (is_string($this->queryData['having']) || is_array($this->queryData['having']))) {

            $SQLhaving = " HAVING " . $this->convert_fields(implode(' AND ', $this->queryData['having']));
        }

        //kontrola skládáni OR/END
        $conditions = self::conditionKeysToString($this->queryData['conditions']);
        if (count($conditions) > 0)
            $SQLconditions = " WHERE  " . $this->convert_fields(implode(' AND ', $conditions)) . "";

        //!!!must after bind translations !!!<o>-<o><o>-<o><o>-<o><o>-<o><o>-<o><o>-<o><o>-<o><o>-<o>
        if (count($this->_bindTrans) > 0) {
            foreach ($this->_bindTrans as $TransModel => $_bindTrans) {
                $this->bindModels[$TransModel] = array(
                    'type' => 'joinSpec',
                    'primaryKey' => "{$TransModel}.parent_id",
                    'foreignKey' => (isset($_bindTrans[3])?$_bindTrans[3]:$_bindTrans[0]).'.id',
                    'className' => 'Translation',
                    'conditions' => array(
                        "{$TransModel}.lang" => isset($this->controller->select_lang)?$this->controller->select_lang:$this->select_lang,
                        "{$TransModel}.history" => 0,
                        "{$TransModel}.modul" => $_bindTrans[0],
                        "{$TransModel}.col" => $_bindTrans[1]
                    )
                );
            }
        }


       // pr($this->bindModels);


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
                    $bFoundModelClass = false;

                    if (file_exists(PROJECT_PATH . 'models/' . strtolower($modelName) . '.php')) {
                        $bFoundModelClass = true;
                        include_once(PROJECT_PATH . 'models/' . strtolower($modelName) . '.php');
                    } elseif (file_exists(ROOT . 'app/models/' . strtolower($modelName) . '.php')) {
                        $bFoundModelClass = true;
                        include_once(ROOT . 'app/models/' . strtolower($modelName) . '.php');
                    }

                    if ($bFoundModelClass) {
                        $sTempController = 1;
                        $oModelik = new $modelName($sTempController);

                        if (is_object($oModelik)) {
                            if (property_exists($oModelik, 'useTable')) {
                                $bForcedTableName = $oModelik->useTable;
                            }
                        }
                    }
                    $sql .= " {$setting['joinSide']} JOIN " . $this->set['prefix'] . ($bForcedTableName != false ? $bForcedTableName : Inflector::pluralize(Inflector::underscore($clasname))) . " AS `{$modelName}` ON (" . implode(' AND ', $con_on) . ")";
                    unset($oModelik);
                }
            }
        }
        $sql .= $SQLconditions;
        $sql .= isset($SQLgroup) ? $SQLgroup : '';
        $sql .= isset($SQLhaving) ? $SQLhaving : '';
        $sql .= $SQLorder;
        $sql .= $this->limit($limit, ($page - 1) * $limit);
        $result = self::query($sql);

        $out = array();


        switch ($type) {
            case 'all':
            case 'list':
                while (is_resource($result) && $item = $this->fetchResult($result)) {

                    $out[] = ($this->create_trans) ? $this->afterFindTranBind($item) : $item;
                }
                if ($type == 'list') {
                    $out = Set::combine($out, '{n}.' . $fields[0], '{n}.' . $fields[1]);
                }
                break;
            case 'count':
                $total_rows = mysql_fetch_row($result);
                return $total_rows[0];
                break;
            case 'first':
                $out = (is_resource($result)) ? $this->fetchResult($result) : false;
                if (count($this->_bindTrans) > 0)
                    $out = $this->afterFindTranBind($out);

                break;
        }



        /**
         * bindovani pro hasMany
         */
        /**
         * bindovani pro hasMany
         */
        if (count($this->bindModels) > 0 && !in_array($type, array('count', 'list'))) {
            foreach ($this->bindModels as $modelName => $setting) {
                $primaryKey = (isset($setting['primaryKey']) ? $setting['primaryKey'] : $this->primaryKey);
                if (strpos($primaryKey, '.') != false) {
                    list($mdlP, $primaryKey) = explode('.', $primaryKey);
                }

                if (strpos($setting['foreignKey'], '.') != false) {
                    list($mdlF, $foreignKey) = explode('.', $setting['foreignKey']);
                }

                if ($setting['type'] == 'hasMany' && count(Set::extract("/{$this->name}/{$primaryKey}", $out))) {
                    if (!isset($this->controller->$modelName))
                        $this->controller->loadModel($modelName);

                    $this->controller->$modelName->con_kos = false;
                    $this->controller->$modelName->con_status = false;

                    // natahnuti bind pro hasManyJoinSpec
                    foreach ($this->bindModels as $hMjS_Model => $hMjS_setting) {
                        if ($hMjS_setting['type'] == 'HasManyJoinSpec' && $hMjS_setting['model'] == $modelName) {
                            $this->controller->$modelName->bindModel(
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
                    $data = $this->controller->$modelName->find(
                        'all',
                        array(
                            'fields' => (isset($setting['fields']) ? $setting['fields'] : array('*')),
                            'conditions' => am(
                                isset($setting['conditions']) ? $setting['conditions'] : array(),
                                array("`{$modelName}`.`{$foreignKey}` IN (" . implode(', ', Set::extract("/{$this->name}/{$primaryKey}", $out)) . ")")
                            ),
                            'order' => isset($setting['order'])?$setting['order']:null
                        )
                    );


                    if ($type == 'first') {
                        if (!isset($out[$modelName]))
                            $out[$modelName] = array();
                        foreach ($data as $bindItem) {
                            $out[$modelName][] = $bindItem[$modelName];
                        }
                    } else if ($type == 'all') {
                        foreach ($out as &$o) {
                            if (!isset($o[$modelName]))
                                $o[$modelName] = array();

                            foreach ($data as $bName => $bindItem) {
                                if ($bindItem[$modelName][$foreignKey] == $o[$this->name][$primaryKey]) {
                                    $add = $bindItem[$modelName];
                                    unset($bindItem[$modelName]);

                                    $o[$modelName][] = am($add, $bindItem);
                                }

                            }
                        }
                    }
                }
            }
        }
        mysql_free_result($result);
        return $this->afterFind($out);
    }

    function limit($limit, $offset = null)
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

    /**
     *
     */
    function fireDelete($conditions)
    {
        if (count($conditions) > 0)
            $whereSQL = " WHERE  " . implode(' AND ', $conditions) . ")";
        self::query("SET autocommit=0 ");
        self::query("START TRANSACTION");
        try {
            foreach ($this->actions['ondelete'] AS $table => $data) {
                switch (strtolower($data['action'])) {
                    case 'cascade':
                        $sql = "DELETE FROM `{$table}` WHERE `{$data['key']}` IN (SELECT `{$this->primaryKey}` FROM `{$this->full_table_name}` {$whereSQL})";
                        self::query($sql);
                        break;
                    case 'restrict':
                        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `{$table}` WHERE `{$data['reference']}` IN (SELECT `{$this->primaryKey}` FROM `{$this->full_table_name}` {$whereSQL})";
                        mysql_num_rows(self::query($sql));
                        if ($find_rows) {
                            self::query("ROLLBACK");
                            return false;
                        }
                        break;
                    case 'set null':
                        $sql = "UPDATE `{$table}` SET `{$data['key']}`=NULL WHERE `{$data['reference']}` IN (SELECT `{$this->primaryKey}` FROM `{$this->full_table_name}` {$whereSQL})";
                        self::query($sql);
                        break;
                    case 'set default':
                        $sql = "UPDATE `{$table}` SET `{$data['key']}`=DEFAULT WHERE `{$data['reference']}` IN (SELECT `{$this->primaryKey}` FROM `{$this->full_table_name}` {$whereSQL})";
                        self::query($sql);
                        break;
                }
            }
        } catch (Exception $e) {
            self::query("ROLLBACK");
            return false;
        }
        self::query("COMMIT");
        return true;
    }

    function fireTrash($conditions)
    {
        if (count($conditions) > 0)
            $whereSQL = " WHERE  " . implode(' AND ', $conditions) . ")";
        if (!count(mysql_fetch_array(self::query("SHOW COLUMNS FROM `{$this->full_table_name}` WHERE `Field`='kos'")))) {
            return false;
        }

        self::query("SET autocommit=0 ");
        self::query("START TRANSACTION");
        try {
            foreach ($this->actions['ontrash'] AS $table => $data) {
                if (!count(mysql_fetch_array(self::query("SHOW COLUMNS FROM `{$table}` WHERE `Field`='kos'")))) {
                    return true;
                }
                switch (strtolower($data['action'])) {
                    case 'cascade':
                        $sql = "UPDATE `{$table}` SET `kos`=1 WHERE `{$data['key']}` IN (SELECT `{{$this->primaryKey}}` FROM `{$this->full_table_name}` {$whereSQL})";
                        self::query($sql);
                        break;
                    case 'restrict':
                        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `{$table}` WHERE `kos`=1 AND `{$data['key']}` IN (SELECT `{{$this->primaryKey}}` FROM `{$this->full_table_name}` {$whereSQL})";
                        mysql_num_rows(self::query($sql));
                        if ($find_rows) {
                            self::query("ROLLBACK");
                            return false;
                        }
                        break;
                    case 'set null':
                        $sql = "UPDATE `{$table}` SET `{$data['key']}`=NULL WHERE `{$data['reference']}` IN (SELECT `{{$this->primaryKey}]}` FROM `{$this->full_table_name}` {$whereSQL})";
                        self::query($sql);
                        break;
                    case 'set default':
                        $sql = "UPDATE `{$table}` SET `{$data['key']}`=DEFAULT IN (SELECT `{{$this->primaryKey}}` FROM {$this->full_table_name} {$whereSQL})";
                        self::query($sql);
                        break;
                }
            }
        } catch (Exception $e) {
            self::query("ROLLBACK");
            return false;
        }
        self::query("COMMIT");
        return true;
    }

    function beforeDelete()
    {

    }

    function delete($id = null, $shallow = false)
    {
        if ($id == null)
            return false;
        $this->id = $id;
        $this->beforeDelete();
        return $this->deleteAll(array($this->primaryKey => $id), $shallow);
    }

    function deleteAll($conditions = array(), $shallow = false)
    {
        if (!is_array($conditions))
            return false;
        $conditions = self::conditionKeysToString($conditions);
        $ok = $shallow ? $shallow : $this->fireDelete($conditions);
        if ($ok) {
            $sql = "DELETE FROM `{$this->full_table_name}` ";
            if (count($conditions) > 0)
                $sql .= " WHERE  " . implode(' AND ', $conditions) . "";
            self::query($sql);
            return true;
        }
        return false;
    }

    function trash($condition = array(), $shallow = false)
    {
        $conditions = self::conditionKeysToString($conditions);
        $ok = $shallow ? $shallow : $this->fireTrash($conditions);
        if ($ok) {
            $sql = "UPDATE `{$this->full_table_name}` SET `kos`=1";
            if (count($conditions) > 0)
                $sql .= " WHERE  " . implode(' AND ', $conditions) . ")";
            self::query($sql);
            return true;
        }
        return false;
    }

    private function afterFindTranBind($data)
    {
        foreach ($this->_bindTrans as $TransModel => $_bindTrans) {
            if ($this->debug == true){
                //pr($data);
            }
            if (isset($data[$TransModel]['value']) || is_null($data[$TransModel]['value'])) {
                $data[isset($_bindTrans[3])?$_bindTrans[3]:$_bindTrans[0]][$_bindTrans[1]] = $data[$TransModel]['value'];
                unset($data[$TransModel]);
            }
        }
        return $data;
    }

    function afterFind($data)
    {
        return $data;
    }

    function fetchResult($result)
    {
        $this->map = array();
        $numFields = mysql_num_fields($result);
        $index = 0;
        $j = 0;

        while ($j < $numFields) {
            $column = mysql_fetch_field($result, $j);
            $this->map[$index++] = array($column->table, $column->name);
            $j++;
        }

        if ($row = mysql_fetch_row($result)) {
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
    }

    function convert_fields($fields)
    {
        if (is_string($fields)) {
            $fields = $this->fill_univerzal_fields($fields);
            $fields = $this->fill_localized_col($fields);
            return $fields;
        } else if (is_null($fields)) {
            if ($this->create_trans === true && count($this->trans) > 0) {
                $fields = $this->fill_univerzal_fields("*");
                $fields = $this->fill_localized_col($fields);
                return $fields;
            } else {
                return '*';
            }
        }
        if (is_array($fields)) {
            $fields = $this->fill_univerzal_fields(implode(", ", $fields));
            return $this->fill_localized_col($fields);

        } else {
            die('Neznamy field pri konverzi convert_fields()');
        }
    }

    protected function fill_univerzal_fields($fields)
    {
        if (trim($fields) != "*") {
            preg_match_all("/(([a-zA-Z_0-9]+|`[a-zA-Z\._0-9]+`)\.)(\*)( +AS +([a-zA-Z_0-9]+|`[a-zA-Z\._0-9]+`))?([\+\-\*%\/=, \)]+)/U", $fields . " ", $out, PREG_SET_ORDER);

            foreach ($out AS $finded) {
                $_model = str_replace("`", "", $finded[2]);
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
            foreach ($this->schema AS $col) {
                $_fields[] = $this->name . "." . (string)$col;
            }
            foreach ($this->foreign_schema AS $model => $schema) {
                foreach ($schema AS $col) {
                    $_fields[] = $model . "." . (string)$col;
                }
            }
            return implode(", ", $_fields);
        }
    }

    protected function fill_localized_col($fields)
    {
        if ($this->create_trans === true && count($this->trans) > 0) {
            //change localized value to source col
            //serach fields

            preg_match_all("/(([a-zA-Z_0-9]+|`[a-zA-Z\._0-9]+`)\.)?([a-zA-Z_0-9]+|`[a-zA-Z\._0-9]+`)( +AS +([a-zA-Z_0-9]+|`[a-zA-Z\._0-9]+`))?([\+\-\*%\/=, \)]+)/U", $fields . " ", $out, PREG_SET_ORDER);


         //   pr($this->bindModels);

            foreach ($out AS $k => $finded) {
                $t_model = $_model = str_replace("`", "", $finded[2]);
                $_col = str_replace("`", "", $finded[3]);

                if ($finded[2]) {
                    $sourceColName = $finded[2] . "." . $finded[3];
                    $fullColName = $_model . "." . $_col;
                } else {
                    $sourceColName = $finded[3];
                    $fullColName = $_col;
                }



                if (in_array($fullColName, $this->trans)) {
                    $newColName = "Translation{$_model}" . Inflector::camelize($_col) . ".value";
                    $fields = str_replace($sourceColName, $newColName, $fields);

                    if (isset($this->bindModels[$finded[2]]) && isset($this->bindModels[$finded[2]]['className'])){
                        //echo "$finded[2]<br/>";
                        $_model = $this->bindModels[$finded[2]]['className'];
                    }

            //        echo "Translation{$t_model}" . Inflector::camelize($_col)."<br/>";

                    if (isset($this->bindModel[$t_model]['clasName'])) {
                        $this->_bindTrans["Translation{$t_model}" . Inflector::camelize($_col)] = array($this->bindModel[$_model]['clasName'], $_col);
                    } else {
                        $this->_bindTrans["Translation{$t_model}" . Inflector::camelize($_col)] = array($_model, $_col);
                    }

                    if ($_model != $t_model){
                        $this->_bindTrans["Translation{$t_model}" . Inflector::camelize($_col)][3] = $t_model;
                    }
                }
            }


        }
        return $fields;
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

    function unbindModels($arr)
    {
        foreach ($arr as $model) {
            unset($this->bindModels[$model]);
            unset($this->foreign_schema[$model]);
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
                $out[] = $not . $this->__quoteFields($value); //line before change add mysql_real_escape_string
                //                $value = $this->__quoteFields($value);
                //                $value = mysql_real_escape_string($value);
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
                    $data = $this->__parseKey($model, trim($key), mysql_real_escape_string($value));
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

    function load_schema()
    {
        $cols = array();

        $schema_file = Paths::findPath('db_source', Inflector::underscore($this->name) . '.xml');
        if ($schema_file == null)
            die("Schema " . Inflector::underscore($this->name) . '.xml' . " nebylo nalezeno");

        $xml = simplexml_load_file($schema_file);
        for ($i = 0; $i < count($xml->cols->col); $i++) {
            $atrs = get_object_vars($xml->cols->col[$i]);
            if (isset($atrs['@attributes']['translation']) && $atrs['@attributes']['translation'] == 'true')
                $this->trans[] = $this->name . "." . (string)$xml->cols->col[$i];
            $cols[] = (string)$xml->cols->col[$i];
        }
        $this->schema = $cols;

        if (!in_array('kos', $this->schema)) {
            $this->con_kos = false;
        }
        if (!in_array('status', $this->schema)) {
            $this->con_status = false;
        }
        for ($i = 0; $i < count($xml->actions->table); $i++) {
            foreach ($xml->actions->table[$i] as $$table) {
                if (isset($table['ondelete'])) {
                    $this->actions['ondelete'][(string)$table] = array(
                        'key' => $table['foreign_key'],
                        'action' => $table['ondelete']);
                }
                if (isset($table['ontrash'])) {
                    $this->actions['ontrash'][(string)$table][$table['ontrash']] = array(
                        'key' => $table['foreign_key'],
                        'action' => $table['ontrash']);
                }
            }
        }
    }

    protected function load_foreign_schema($model)
    {
        $cols = array();
        if (isset($this->bindModels[$model]['className'])) {
            $modelClass = $this->bindModels[$model]['className'];
        } else {
            $modelClass = $model;
        }
        $schema_file = Paths::findPath('db_source', Inflector::underscore($modelClass) . '.xml');
        if ($schema_file == null) {
            die("Schema " . Inflector::underscore($modelClass) . '.xml' . " nebylo nalezeno");
        }

        $xml = simplexml_load_file($schema_file);
        for ($i = 0; $i < count($xml->cols->col); $i++) {
            $atrs = get_object_vars($xml->cols->col[$i]);
            if (isset($atrs['@attributes']['translation']) && $atrs['@attributes']['translation'] == true)
                $this->trans[] = $model . "." . (string)$xml->cols->col[$i];
            $cols[] = (string)$xml->cols->col[$i];
        }
        $this->foreign_schema[$model] = $cols;
    }

    /**
     * Rekurzivni vyhledavani ve strome
     * @param $id - id prvku kteremu hledame cestu ke korenu
     *
     * Nejcasteji se pouziva pro vyhledavani v kategoriich,
     * kde vyhledavame zanorene polozce jeho nadrazene kategorie.
     */
    public function get_path($id)
    {
        $output = array();
        $output[] = $id;

        $cur = $this->find('first', array(
            'conditions' => array(
                "{$this->name}.id" => $id
            ),
            'fields' => array(
                "{$this->name}.id",
                'parent_id'
            )
        ));

        if ($cur[$this->name]['parent_id'] != 0) {
            $output = array_merge($output, self::get_path($cur[$this->name]['parent_id']));
        }

        return $output;
    }

    /**
     * nove funkce pro vytahnuti listu
     * klasicky id, name
     * @param $con - array conditions, am with basic array
     * @param $order - classic order string, default is "name ASC"
     * @return array list
     * basic condition kos=0
     */
    function get_list($con = array(), $order = 'name ASC')
    {
        if ($this->con_kos == true)
            $con = am(array('kos' => 0), $con);

        $this->query("SET NAMES 'utf8'");
        return $this->find('list', array(
            'conditions' => $con,
            'order' => $order
        ));
    }

}

?>