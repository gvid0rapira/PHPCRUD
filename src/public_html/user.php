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
            $emailErr = "Invalid email format";
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
<a href="users.php">список пользователей</a>
<h2>Пользователь</h2>
<form action="user.php" method="POST">
<input type="hidden" name="action" value="<?php echo htmlentities( $action ) ?>">
<input type="hidden" name="id" value="<?php echo htmlentities( $id ) ?>">
<table>
<tr>
<td><label>Ф.И.О.</label></td><td><input type="text" name="fio" value="<?php echo htmlentities( $user->fio ) ?>" ></td>
</tr><tr>
<td><label>email</label></td><td><input type="text" name="email" value="<?php echo htmlentities( $user->email ) ?>" ></td>
</tr><tr>
<td colspan="2"><span class="error"><?php echo htmlentities( $emailErr );?></span></td>
</tr><tr>
<td><label>Роль</label></td><td>
<select name="role" required>
<option value="администратор" <?php if($user->role =='администратор') echo 'selected'?>>администратор</option>
<option value="пользователь" <?php if($user->role =='пользователь') echo 'selected'?>>пользователь</option>
</select>
</td>
</tr><tr>
<td><label>name</label></td><td><input type="text" name="name" value="<?php echo htmlentities( $user->name ) ?>" ></td>
</tr><tr>
<td><label>password</label></td><td><input type="password" name="password" value="<?php echo htmlentities( $user->password ) ?>" ></td>
</tr><tr>
<td colspan="2"><input name="saveBtn" type="submit" value="Сохранить"></td>
</tr>
</table>
</form>
</body>
</html>
