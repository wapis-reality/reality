<?php
// Report all PHP errors (see changelog)
error_reporting(E_ALL);
ini_set("display_errors", 1);

class Pages
{
    static $current_page = null;
    static $current_title = null;
    static $path = 'views/pages/';
    static $default_view = 'job_positions';
    static $default_title = 'Default page';
    static $con_array = array(
        'candidates' => array(
            'title' => 'Candidates',
            'url' => 'candidates'
        ),
        'positions' => array(
            'title' => 'Job position',
            'url' => 'job_positions'
        ),
        'tests' => array(
            'title' => 'Test questions',
            'url' => 'tests'
        ),
        'results' => array(
            'title' => 'Results',
            'url' => 'results'
        ),
        'users' => array(
            'title' => 'Users',
            'url' => 'users'
        ),
        'departments' => array(
            'title' => 'Departments',
            'url' => 'departments'
        ),
    );

    function __constructor()
    {

    }

    function getView()
    {
        self::findView();
        return self::$path . self::$current_page . '.ctp';
    }
    function gettitle()
    {
        self::findTitle();
        return self::$current_title;
    }

    function findTitle()
    {
        if (isset($_GET['page']) && !empty($_GET['page']) && isset(self::$con_array[$_GET['page']])) {
            self::$current_title = self::$con_array[$_GET['page']]['title'];
        } else {
            self::$current_title = self::$default_title;
        }
    }


    function findView()
    {
        if (isset($_GET['page']) && !empty($_GET['page']) && isset(self::$con_array[$_GET['page']])) {
            self::$current_page = self::$con_array[$_GET['page']]['url'];
        } else {
            self::$current_page = self::$default_view;
        }
    }


}

?>