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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<title>Log in</title>
</head>
<body>
<?php if (isset($_SESSION['user'])) echo 'Вы вошли как ' . $_SESSION['user']->name;  ?>
<h1>Log in</h1>
<span><?php echo $loginErr; ?></span>
<form action="login.php" method="POST">
<table>
<tr>
<td><label>name</label></td>
<td><input type="text" name="name" value="" >
    <span><?php echo $nameErr ?></span></td>
</tr><tr>
<td><label>password</label></td>
<td><input type="password" name="password" value="" >
    <span><?php echo $passwordErr ?></span></td>
</tr><tr>
<td colspan="2"><input name="loginBtn" type="submit" value="Log in"></td>
</tr>
</table>
</form>
<a href="users.php">Список сотрудников</a>
</body>
