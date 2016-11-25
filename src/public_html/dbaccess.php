<?php

function getPDO() {
    $config = parse_ini_file('../config.ini');
    // Исключение обрабатывается выше
    $PDO = new PDO('mysql:host=localhost;' .
        'dbname=' . $config['schema'] . ';' .
        'charset=UTF8',
        $config['username'], $config['password']);
    return $PDO;
}

function get_mysqli() {

    $config = parse_ini_file('../config.ini');
    $mysqli = new mysqli('localhost', $config['username'], 
        $config['password'], $config['schema']); 
    if ($mysqli->connect_errno) {
        error_log("Ошибка соединения с MySQL: (" . 
            $mysqli->connect_errno . ")" . 
            $mysqli->connect_error);
        return NULL;
    }
    if (!$mysqli->set_charset("utf8")) { 
        error_log("Ошибка загрузки кодировки utf8: " .
            $mysqli->error, 0);
    } else {
        error_log("Кодировка соединения с MySQL: " . 
            $mysqli->character_set_name());
    }
    
    return $mysqli;
}
?>

