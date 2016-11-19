<?php
include 'dbaccess.php'
?>
<html>
<head>
<title>Пользоветель</title>
</head>
<body>
<?php

/**
 Четыре действия:
 1. new - заполнение параметров нового пользователя,
 2. edit - редактирование параметров существующего пользователя,
 3. add - сохранение нового пользователя в БД.
 4. update - обновление параметров пользователя в БД.
 */

    $action = $_REQUEST["action"];
    $id = $_REQUEST["id"];
    $fio = $_REQUEST["fio"];
    $email = $_REQUEST["email"];
    $name = $_REQUEST["name"];
    $password = $_REQUEST["password"];

    if($action == "edit") { // Редактирование параметров
        // TODO: проверить валидность $id;
        $mysqli = get_mysqli();
        $res = $mysqli->query("SELECT * FROM user WHERE id = " . $id);
        // TODO: проверить, что результат запроса не пуст. 
        $res->data_seek(0);
        $row = $res->fetch_assoc();
        $fio = $row['fio'];
        $email = $row['email'];
        $name = $row['name'];
        $password = $row['password'];
        $action = "update";
    } else if ($action == "update") {
        $sql = "UPDATE user SET fio = '" . $fio .
                          "', email = '" . $email .
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
    } else if ($action == "new") {
        $id = '';
        $fio = '';
        $email = '';
        $name = '';
        $password = '';
        $action = "add";
    } else if ($action == "add") {
        $sql = "INSERT INTO user (fio, email, name, password) " .
                    "VALUES ('" . $fio .
                         "', '" . $email .
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
?>
<form action="user.php" method="POST">
<input type="hidden" name="action" value="<?php echo $action?>">
<input type="hidden" name="id" value="<?php echo $id ?>">
<table>
<tr>
<td><label>Ф.И.О.</label></td><td><input type="text" name="fio" value="<?php echo $fio ?>" ></td>
</tr><tr>
<td><label>email</label></td><td><input type="text" name="email" value="<?php echo $email ?>" ></td>
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
