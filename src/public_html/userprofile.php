<?php
include_once 'User.php';

session_start();
if(empty($_SESSION['user'])) {
    require 'login.php';
    exit(0);
}
// TODO: Проверить ввод.
$user = User::findById(strip_tags( $_REQUEST['id'] ));
if(empty($user)) {
    // TODO: Информативно обработать ошибку
    $errMsg = 'Внутренняя ошибка ...';
    require 'error.php';
    exit(0);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<title>Профиль пользователя</title>
</head>
<body>
<div class="container">
<a href="users.php">Список пользователей</a>
<h1><?= htmlentities($user->fio) ?></h1>
<table>
<tr><td>email:</td><td><?= htmlentities($user->email) ?></td></tr>
<tr><td>роль:</td><td><?= htmlentities($user->role) ?></td></tr>
<tr><td>пароль:</td><td><?= htmlentities($user->password) ?></td></tr>
</table>
</div>
</body>
