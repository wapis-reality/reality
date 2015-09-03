<?
class MysqlModel extends DbModel
{
    var $set = array(
        'host' => 'localhost',
        'login' => "real_estates",
        'password' => "real_estates",
        'database' => "real_estates"
    );

    final private function __call_query()
    {

    }

    public function __call_connect($host, $login, $password)
    {
        if($link = mysqli_connect($host, $login, $password)){
            mysqli_set_charset($link ,"utf8");
            return $link;
        }else{
            return false;
        }
    }

    final private function __call_error($connection_link)
    {
        return mysqli_error($connection_link);
    }

    final function __call_select_db($connection_link, $database)
    {
        return mysqli_select_db($connection_link, $database);
    }

    final function __query($connection_link,$sql) {
        return mysqli_query($connection_link,$sql);
    }

    final function __get_insert_id($connection_link)
    {
        return mysqli_insert_id($connection_link);
    }

    final function __fetch_row($result)
    {
        return mysqli_fetch_row($result);
    }

    final function __fetch_field($result)
    {
        return mysqli_fetch_field($result);
    }

    final function __escape_string($connection_link,$string) {
        return mysqli_escape_string($connection_link,$string);
    }
}