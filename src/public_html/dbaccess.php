<?php

function getPDO() {
    $config = parse_ini_file('../config.ini');
    
    try {
        $PDO = new PDO('mysql:host=localhost;' .
        'dbname=' . $config['schema'] . ';' .
        'charset=UTF8',
        $config['username'], $config['password']);
    } catch (PDOException $e) {
        error_log($e->getCode() . ": " . $e->getMessage());
        $errMsg = 'Ошибка соединения с БД';
        require('error.php');
        exit(0);
    }
    return $PDO;
}

