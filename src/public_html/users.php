<?php
include 'dbaccess.php';
?>
<html>
<head>
	<title>Пользователи</title>
    <script>
        /**
         * parameters:
         * action - значение параметра запроса "action". 
         * actor  - значение атрибута "action" формы.
         */
        function submitForm(action, actor) {
            console.log(action);
            console.log(document.getElementById("actionTxt").value);
            document.getElementById("actionTxt").value = action;
            console.log(document.getElementById("actionTxt").value);
            var form = document.getElementById("userList"); 
            form.action = actor;
            form.submit();
        }
    </script>
</head>
<body>
<?php  
    $action = $_REQUEST['action'];
    $id = $_REQUEST['id'];
    $mysqli = get_mysqli();
    if( $action == "delete" ) {
        $sql = "DELETE FROM user WHERE id = " . $id; 
        if(!$mysqli->query($sql)) {
           echo("Ошибка ошибка удаления пользователя.");
           error_log("Ошибка удаления пользователя: " .
                $mysqli->error); 
        }
        $action = "";
    }
    
    $res = $mysqli->query("SELECT * FROM user");
?>
<form id = "userList" action="user.php" method="POST">
<input type="hidden" id = "actionTxt" name="action" value="edit">
<table>
<tr>
<th>ID</th>
<th>ФИО</th>
<th>email</th>
<th>Роль</th>
<th>name</th>
<th>password</th>
</tr>
<?php
    for ($row_no = $res->num_rows - 1; $row_no >= 0; $row_no--) {
        $res->data_seek($row_no);
        $row = $res->fetch_assoc();?>
<tr>
<td><input type="radio" name="id" value="<?php echo $row['id'] ?>" >
</td>
<td>
<?php echo $row['fio'] ?>
</td>
<td>
<?php echo $row['email'] ?>
</td>
<td>
<?php echo $row['role'] ?>
</td>
<td>
<?php echo $row['name'] ?>
</td>
<td>
<?php echo $row['password'] ?>
</td>
</tr>
<?php
    }
?>
<tr>
<td colspan="2">
<button onclick="submitForm('edit', 'user.php')" >Редактировать</button>
<button onclick="submitForm('new', 'user.php')" >Добавить</button>
<button onclick="submitForm('delete', 'users.php')" >Удалить</button>
</td>
</tr>
</table>
</form>
</body>
</html>
