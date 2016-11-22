<?php
    include_once 'User.php';
    
    /**
     * Параметры запроса:
     * action - операция. Значения:
     *   1 не задано - отображение списка.
     *   2 delete - удаление указанного пользователя и отображение списка.
     * id - id пользователя, с которым проводится затребованная операция.
     * orderby - значение имеет вид <field><f>, где
     * 		 field - имя поля списка пользователей, по которому будет
     *           сортироваться список,
     *       f - флаг порядка сортировки (0 - прямой порядок, 1 - обратный).
     */

    // Аутентификация
    session_start();
    if(empty($_SESSION['user'])) {
        require 'login.php';
        exit(0);
    }
    $users = array();
    $fioOrderBy = 'fio0';
    $roleOrderBy = 'role0';
    $nameOrderBy = 'name0';
    
    $action = $_REQUEST['action'];
    if( $action == "delete" ) { //---------------------: Удаление
        User::deleteById($_REQUEST['id']);
        $users = User::findAllOrderBy('fio');
    } else if( $action == "filter" ) { //--------------: Фильтрация
        $filter_val = '';
        $filter_fld = $_REQUEST['filter_fld'];
        if( $filter_fld == 'fio') {
            $filter_val = $_REQUEST['fio_filter'] . "%";
        } else if ( $filter_fld == 'role') {
            $filter_val = $_REQUEST['role_filter'] . "%";
        } else if ( $filter_fld == 'name') {
            $filter_val = $_REQUEST['name_filter'] . "%";
        } else {
            // Неизвестное имя поля. Выдать весь список.
            $filter_fld = '';
        }
        if( empty($filter_fld) ) {
            $users = User::findAllOrderBy('fio');
        } else {
            $users = User::filter($filter_fld, $filter_val);
        }
    } else if( $action == "sort" ) { //----------------: Сортировка
        
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
    } else { //----------------------------------------: По умолчанию
        $users = User::findAllOrderBy('fio');
    }
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
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

    function submitFilter(field) {
        document.getElementById("actionHid").value = 'filter';
        document.getElementById("filterFldHid").value = field;
        var form = document.getElementById("userList"); 
        form.action = 'users.php';
        form.submit();
    }
</script>
</head>
<body>
<?php if (isset($_SESSION['user'])) echo 'Вы вошли как ' . $_SESSION['user']->name;  ?>
<a href="logout.php"> log out</a>
<h2>Список пользователей</h2>
<form id = "userList" action="user.php" method="GET">
<input type="hidden" id = "actionHid" name="action" value="">
<input type="hidden" id = "orderByHid" name="orderby" value="">
<input type="hidden" id = "filterFldHid" name="filter_fld" value="">
<table class="table">
<tr>
<th>ID</th>
<th><button 
    onclick="submitForm('sort', 'users.php', '<?php echo $fioOrderBy ?>')" >
    Ф.И.О.</button><input type="text" name="fio_filter" style="width: 80px" >
    <button onclick="submitFilter('fio')">F</button></th>
<th>email</th>
<th><button 
    onclick="submitForm('sort', 'users.php', '<?php echo $roleOrderBy ?>')" >
    Роль</button><input type="text" name="role_filter" style="width: 80px" >
    <button onclick="submitFilter('role')">F</button></th>
<th><button 
    onclick="submitForm('sort', 'users.php', '<?php echo $nameOrderBy ?>')" >
    name</button><input type="text" name="name_filter" style="width: 80px" >
    <button onclick="submitFilter('name')">F</button></th>
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
