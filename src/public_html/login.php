<?php
require_once 'User.php';

session_start();
$nameErr = $passwordErr = $loginErr = '';
if (empty($_POST['name'])) {
    $nameErr = 'Имя обязательно';
}
if (empty($_POST['password'])) {
    $passwordErr = 'Пароль обязателен';
}

if (!($nameErr | $passwordErr)) {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $user = User::findByName($name);
    if ($user) {
        if ( !($password == $user->password)) {
            $loginErr = 'Неверное имя или пароль';
            error_log("Ошибка входа: name: " . $name
            . ", password: " . $password);
        } else {
            $_SESSION['user'] = $user;
        } 
    } else {
        $loginErr = 'Неверное имя или пароль';
        error_log("Ошибка входа: name: " . $name
            . ", password: " . $password);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<title>Log in</title>
</head>
<body>
<div class="container">
<?php if (isset($_SESSION['user'])): ?> 
Вы вошли как <?= $_SESSION['user']->name ?> 
<a href="logout.php"> log out</a>
<a href="users.php">Список сотрудников</a>
<?php endif; ?>
<h1>Log in</h1>
<span class="help-inline alert-danger"><?= $loginErr ?></span>
<form class="form-horizontal" action="login.php" method="POST">
<div class="form-group">
<label class="col-sm-2 control-label">name</label>
<div class="col-sm-10">
<input type="text" class="form-control" name="name" value="" >
    <span class="help-inline alert-danger"><?= $nameErr ?></span>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label">password</label>
<div class="col-sm-10">
<input type="password" class="form-control" name="password" value="" >
    <span class="help-inline alert-danger"><?= $passwordErr ?></span>
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<button type="submit" class="btn btn-default">Log in</button>
</div>
</div>
</form>
</div>
</body>
