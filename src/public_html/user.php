<?php
include_once 'User.php';

/**
 Четыре действия:
 1. new - заполнение параметров нового пользователя,
 2. edit - редактирование параметров существующего пользователя,
 3. add - сохранение нового пользователя в БД.
 4. update - обновление параметров пользователя в БД.
 */
    
    // Аутентификация
    session_start();
    if(empty($_SESSION['user'])) {
        require 'login.php';
        exit(0);
    }

    $action = strip_tags( $_POST["action"] );
    $user = null;
    $emailErr = '';

    if($action == "edit") { // Редактирование параметров

        $id = strip_tags( $_POST['id'] );
        // TODO: обработать ошибки запроса к БД
        $user = User::findById($id);
        // Если редактирует пользователь, то только свои данные
        if ($_SESSION['user']->role == 'пользователь') {
            if (!($_SESSION['user']->name == $user->name)) {
                $errMsg = 'доступ запрещен';
                require('error.php');
                exit(0);
            }
        }
        $action = "update";
    } else if ($action == "update") {
        // Получение параметров и валидация.
        $user = new User();
        $user->id = strip_tags( $_POST["id"] );
        $user->fio = strip_tags( $_POST["fio"] );
        $user->email = strip_tags( $_POST["email"] );
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "неверный формат email";
        }
        $user->role = strip_tags( $_POST["role"] );
        $user->name = strip_tags( $_POST["name"] );
        $user->password = strip_tags( $_POST["password"] );
        
        if(!$emailErr) {
           $user->save(); 
        }
    } else if ($action == "new") {
        $user = new User();
        $action = "add";
    } else if ($action == "add") {
        $user = new User();
        $user->fio = strip_tags( $_POST["fio"] );
        $user->email = strip_tags( $_POST["email"] );
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "неверный формат email";
        }
        $user->name = strip_tags( $_POST["name"] );
        $user->role = strip_tags( $_POST["role"] );
        $user->password = strip_tags( $_POST["password"] );

        if(!$emailErr) {
            $user->save();
        }
    } else {
        // TODO: Сделать информативную обработку ошибки
        $errMsg =  'Внутренняя ошибка ...';
        require 'error.php';
        exit(0);
    }
?>

<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<title>Пользоветель</title>
</head>
<body>
<div class="container">
<a href="users.php">список пользователей</a>
<h2>Пользователь</h2>
<form class="form-horizontal" action="user.php" method="POST">
<input type="hidden" name="action" 
       value="<?= htmlentities($action) ?>">
<input type="hidden" name="id" 
       value="<?= htmlentities($user->id) ?>">
<div class="form-group">
<label class="col-sm-2 control-label">Ф.И.О.</label>
<div class="col-sm-10">
<input type="text" class="form-control" name="fio" 
       value="<?= htmlentities($user->fio) ?>" >
</div>
</div>
<div class="form-group <?php if ($emailErr) echo 'has-error' ?>">
<label class="col-sm-2 control-label">email</label>
<div class="col-sm-10">
<input type="text" class="form-control" name="email" 
       value="<?= htmlentities($user->email) ?>" >
<span class="help-block">
    <?= htmlentities($emailErr)?></span>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label">Роль</label>
<div class="col-sm-10">
<select class="form-control" name="role" required>
<option value="администратор" <?php if($user->role =='администратор') echo 'selected'?>>администратор</option>
<option value="пользователь" <?php if($user->role =='пользователь') echo 'selected'?>>пользователь</option>
</select>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label">name</label>
<div class="col-sm-10">
<input type="text" class="form-control" name="name" 
       value="<?= htmlentities($user->name) ?>">
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label">password</label>
<div class="col-sm-10">
<input type="password" class="form-control" name="password" 
       value="<?= htmlentities($user->password) ?>">
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<button type="submit" class="btn btn-default">Сохранить</button>
</div>
</div>
</form>
</div>
</body>
</html>
