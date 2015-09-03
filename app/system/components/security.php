<?php

class Security extends Component {

    function __construct($base = null, $start = true)
    {

    }

    public function startup(&$controller)
    {
        $this->controller = &$controller;

    }

    /**
     * This is just for test
     */
    public function isLoggedIn() {
        if(isset($_SESSION["user"])) {
            echo "You are not in";
        }
    }
}