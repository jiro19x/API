<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST, GET, PATCH, DELETE");
header("Access-Control-Max-Age: 3600");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers");
date_default_timezone_set("Asia/Manila");

define("SERVER", "localhost");
define("DBASE", "event_portal");//name of the database "event_portal"
define("USER", "root");
define("PWORD", "");
define("TOKEN_KEY", "989C18A3827D6CD5D7297C9A7C299");//token key
//define("SECRET_KEY", "Your_secret_key");

class Connection {
    protected $connectionString = "mysql:host=" . SERVER . ";dbname=" . DBASE . ";charset=utf8";
    protected $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false
    ];
    
    public function connect() {
        return new \PDO($this->connectionString, USER, PWORD, $this->options);
    }
}
?>
