<?php
    include_once 'User.php';
    
    /**
     * Параметры запроса:
     * action - операция. Значения:
     *   1 не задано - отображение списка.
     *   2 delete - удаление указанного пользователя и отображение списка.
     * id - id пользователя, с которым проводится затребованная операция.
     * orderby - имя параметра пользователя, по которому будет сортироваться
     *           список сотрудников.
     * desc - обратное направление сортировки. Если значение установлено в 1цу,
     *        то список сортируется по заданному полю в обратном направлении. 
     */

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
    $fioOrderBy = 'fio0';
    $roleOrderBy = 'role0';
    $nameOrderBy = 'name0';
    if (!empty($_REQUEST['orderby'])) {
        $field = substr($_REQUEST['orderby'], 0, strlen($_REQUEST['orderby']) - 1);
        $desc = 0 + substr($_REQUEST['orderby'], -1);
        $users = User::findAllOrderBy($field, $desc);
        if ($field == 'fio') {
            $fioOrderBy = $field . ($desc ^ 1);
        } else if ($field == 'role') {
            $roleOrderBy = $field . ($desc ^ 1);
        } else if ($field == 'name') {
            $nameOrderBy = $field . ($desc ^ 1);
        }
    } else {
        $users = User::findAllOrderBy('fio');
    }
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
        function submitForm(action, actor, orderby) {
            document.getElementById("actionHid").value = action;
            document.getElementById("orderByHid").value = orderby;
            var form = document.getElementById("userList"); 
            form.action = actor;
            form.submit();
        }
    </script>
</head>
<body>
<h2>Список пользователей</h2>
<form id = "userList" action="user.php" method="POST">
<input type="hidden" id = "actionHid" name="action" value="edit">
<input type="hidden" id = "orderByHid" name="orderby" value="">
<table>
<tr>
<th>ID</th>
<th><button 
    onclick="submitForm('', 'users.php', '<?php echo $fioOrderBy ?>')" >
    Ф.И.О.</button></th>
<th>email</th>
<th><button 
    onclick="submitForm('', 'users.php', '<?php echo $roleOrderBy ?>')" >
    Роль</button></th>
<th><button 
    onclick="submitForm('', 'users.php', '<?php echo $nameOrderBy ?>')" >
    name</button></th>
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
<button onclick="submitForm('edit', 'user.php', '')" >Редактировать</button>
<?php if($_SESSION['user']->role == 'администратор') { ?>
<button onclick="submitForm('new', 'user.php', '')" >Добавить</button>
<button onclick="submitForm('delete', 'users.php', '')" >Удалить</button>
<?php } ?>
</td>
</tr>
</table>
</form>
</body>
</html>
