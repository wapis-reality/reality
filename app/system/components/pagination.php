<?php

class Pagination extends Component {

    var $modelClass = null;
    var $paging;
    var $params;
    var $group = null;

    function startup(&$controller) {
        $this->controller = $controller;
        $this->modelClass = $this->controller->uses[0];
    }

    /**
     * Set the page to the last if there would be no results, and to 1 if a negetive
     * page number is specified
     *
     * @param integer
     */
    function checkPage($count = 0) {
        if ((($this->paging['page'] - 1) * $this->paging['show']) >= $count) {
            $this->paging['page'] = floor($count / $this->paging['show'] + 0.99);
        }
    }

    /**
     * set orders
     * Exactly as :  Model.column direction(
     * Direction - ASC/DESC
     */
    function set_order($order) {
        list($ModelCol, $direction) = explode(' ', $order);
        list($model, $col) = explode('.', $ModelCol);
        $this->paging['direction'] = $direction;
        $this->paging['sortByClass'] = ucfirst($model);
        $this->paging['sortBy'] = strtolower($col);
        $this->paging['group'] = null;
    }

    function start($conditions = array(), $params = array()) {
        // defaultni nastaveni
        self::default_setting();

        foreach ($params as $key => $param) {
            if ($key == 'order')
                self::set_order($param);
            else
                $this->paging[$key] = $param;
        }

        self::params_setting();

        // Nastaveni /zjisteni/ celkoveho poctu zaznamu
        if (isset($this->total)) {
            $count = $this->total;
        } else {
            //$this->controller->{$this->modelClass}->debug = true;
            //pr($this->group);
            $this->controller->{$this->modelClass}->simulation_count = true;
            $count = $this->controller->{$this->modelClass}->find('first', array('fields'=>array('COUNT( DISTINCT '.$this->controller->{$this->modelClass}->name.'.'.$this->controller->{$this->modelClass}->primaryKey.') as pocet'),'conditions' => $conditions));
            $this->controller->{$this->modelClass}->simulation_count = false;
            $count = $count[0]['pocet'];
        }

        $this->checkPage($count);
        if ($this->paging['page'] == 0)
            $this->paging['page'] = 1;

        $this->paging['total'] = $count;

        // Pocet stranek
        $this->paging['pageCount'] = ceil($count / $this->paging['show']);

        // order output
        $this->order = $this->paging['sortByClass'] . "." . $this->paging['sortBy'] . ' ' . strtoupper($this->paging['direction']);
        $this->controller->set('paging',$this->paging);
        return (Array($this->order, $this->paging['show'], $this->paging['page'], $this->paging));
    }

    function params_setting() {
        $to_extract = array('show', 'sortBy', 'direction', 'page', 'sortByClass');
        $this->params = $this->controller->params['url'];
        $this->paging = am($this->paging,$this->params);

        foreach ($to_extract as $param) {
            if (isset($this->params['url'][$param]) && !empty($this->controller->params['url'][$param]))
                $this->paging[$param] = $this->controller->params['url'][$param];
        }
    }

    function default_setting() {
        if (!isset( $this->controller->{$this->modelClass})) {
            $this->controller->loadModel($this->modelClass);
        }

        $this->paging = array(
            'page' => 1,
            'pageCount' => 0,
            'show' => 10,
            'total' => 0,
            'sortBy' => $this->controller->{$this->modelClass}->primaryKey,
            'sortByClass' => $this->modelClass,
            'direction' => 'ASC',
            'group' => null
        );
    }

}

?>