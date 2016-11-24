<?php
include_once 'User.php';

session_start();
if(empty($_SESSION['user'])) {
    require 'login.php';
    exit(0);
}
// TODO: Проверить ввод.
$user = User::findById(strip_tags( $_REQUEST['id'] ));
if(!isset($user)) {
    // TODO: Информативно обработать ошибку
    $errMsg = 'Внутренняя ошибка ...';
    require 'error.php';
    exit(0);
}
?>

<html>
<head>
<title>Профиль пользователя</title>
</head>
<body>
<h1><?php echo htmlentities( $user->fio ) ?></h1>
<table>
<tr><td>email:</td><td><?php echo htmlentities( $user->email ) ?></td></tr>
<tr><td>роль:</td><td><?php echo htmlentities( $user->role ) ?></td></tr>
<tr><td>пароль:</td><td><?php echo htmlentities( $user->password ) ?></td></tr>
</table>
<a href="users.php">Список сотрудников</a>
</body>
