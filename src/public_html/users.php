<?php
    include_once 'User.php';

    // Аутентификация
    session_start();
    if(empty($_SESSION['user'])) {
        require 'login.php';
        exit(0);
    }

    $action = $_REQUEST['action'];
    if( $action == "delete" ) {
        User::deleteById($_REQUEST['id']);
        $action = "";
    }
    
    $users = User::findAll();
?>

<html>
<head>
<?php if (isset($_SESSION['user'])) echo 'Вы вошли как ' . $_SESSION['user']->name;  ?>
<a href="logout.php"> log out</a>
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
<h2>Список пользователей</h2>
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

<?php foreach($users as $user) { ?>
<tr>
<td><input type="radio" name="id" value="<?php echo $user->id ?>" >
</td>
<td>
<a href="userprofile.php?id=<?php echo $user->id ?>">
<?php echo $user->fio ?></a>
</td>
<td>
<?php echo $user->email ?>
</td>
<td>
<?php echo $user->role ?>
</td>
<td>
<?php echo $user->name ?>
</td>
<td>
<?php echo $user->password ?>
</td>
</tr>
<?php } ?>

<tr>
<td colspan="2">
<button onclick="submitForm('edit', 'user.php')" >Редактировать</button>
<?php if($_SESSION['user']->role == 'администратор') { ?>
<button onclick="submitForm('new', 'user.php')" >Добавить</button>
<button onclick="submitForm('delete', 'users.php')" >Удалить</button>
<?php } ?>
</td>
</tr>
</table>
</form>
</body>
</html>
