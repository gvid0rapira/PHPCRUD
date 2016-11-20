<?php
include 'dbaccess.php';

/**
 Четыре действия:
 1. new - заполнение параметров нового пользователя,
 2. edit - редактирование параметров существующего пользователя,
 3. add - сохранение нового пользователя в БД.
 4. update - обновление параметров пользователя в БД.
 */

    $action = $_REQUEST["action"];
    $id = $fio = $email = $name = $password = '';
    $emailErr = '';

    if($action == "edit") { // Редактирование параметров
        $id = $_REQUEST['id'];
        // TODO: проверить валидность $id;
        $mysqli = get_mysqli();
        $res = $mysqli->query("SELECT * FROM user WHERE id = " . $id);
        // TODO: проверить, что результат запроса не пуст. 
        $res->data_seek(0);
        $row = $res->fetch_assoc();
        $fio = $row['fio'];
        $email = $row['email'];
        $role = $row['role'];
        $name = $row['name'];
        $password = $row['password'];
        $action = "update";
    } else if ($action == "update") {
        // Получение параметров и валидация.
        $id = $_REQUEST["id"];
        $fio = $_REQUEST["fio"];
        $email = $_REQUEST["email"];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
        $role = $_REQUEST["role"];
        $name = $_REQUEST["name"];
        $password = $_REQUEST["password"];

        if(!$emailErr) {
            $sql = "UPDATE user SET fio = '" . $fio .
                              "', email = '" . $email .
                               "', role = '" . $role .
                               "', name = '" . $name .
                           "', password = '" . $password .
                             "' WHERE id = " . $id;
            echo "<br/> " . $sql . "<br/>";
            $mysqli = get_mysqli();
            if(!$mysqli->query($sql)) {
               echo("Ошибка обновления параметров пользователя.");
               error_log("Ошибка обновления параметров пользователя: " .
                    $mysqli->error); 
            }
        }
    } else if ($action == "new") {
        $id = '';
        $fio = '';
        $email = '';
        $role = '';
        $name = '';
        $password = '';
        $action = "add";
    } else if ($action == "add") {
        // Получение параметров и валидация.
        $id = $_REQUEST["id"];
        $fio = $_REQUEST["fio"];
        $email = $_REQUEST["email"];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
        $name = $_REQUEST["name"];
        $role = $_REQUEST["role"];
        $password = $_REQUEST["password"];

        if(!$emailErr) {
            $sql = "INSERT INTO user (fio, email, role, name, password) " .
                        "VALUES ('" . $fio .
                             "', '" . $email .
                             "', '" . $role .
                             "', '" . $name .
                             "', '" . $password .
                             "')";
            echo "<br/> " . $sql . "<br/>";
            $mysqli = get_mysqli();
            if(!$mysqli->query($sql)) {
               echo("Ошибка добавления нового пользователя.");
               error_log("Ошибка добавления нового пользователя: " .
                    $mysqli->error); 
            }
        }
    }
?>

<html>
<head>
<title>Пользоветель</title>
</head>
<body>
<form action="user.php" method="POST">
<input type="hidden" name="action" value="<?php echo $action?>">
<input type="hidden" name="id" value="<?php echo $id ?>">
<table>
<tr>
<td><label>Ф.И.О.</label></td><td><input type="text" name="fio" value="<?php echo $fio ?>" ></td>
</tr><tr>
<td><label>email</label></td><td><input type="text" name="email" value="<?php echo $email ?>" ></td>
</tr><tr>
<td colspan="2"><span class="error"><?php echo $emailErr;?></span></td>
</tr><tr>
<td><label>Роль</label></td><td>
<select name="role" required>
<option value="администратор" <?php if($role =='администратор') echo 'selected'?>>администратор</option>
<option value="пользователь" <?php if($role =='пользователь') echo 'selected'?>>пользователь</option>
</select>
</td>
</tr><tr>
<td><label>name</label></td><td><input type="text" name="name" value="<?php echo $name ?>" ></td>
</tr><tr>
<td><label>password</label></td><td><input type="password" name="password" value="<?php echo $password ?>" ></td>
</tr><tr>
<td colspan="2"><input name="saveBtn" type="submit" value="Сохранить"></td>
</tr>
</table>
</form>
</body>
</html>
