<?php

require_once 'dbaccess.php';

class User {

    public $id = 0;
    public $fio = '';
    public $email = '';
    public $role = '';
    public $name = '';
    public $password = '';

    public static function findById($id) {

        $dbcon = getPDO();
        $stmt = $dbcon->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bindValue(1, $id);
        $user = null;
        if ($stmt->execute()) {
            $row = $stmt->fetch();
            $user = new User();
            $user->id = $row['id'];
            $user->fio = $row['fio'];
            $user->email = $row['email'];
            $user->role = $row['role'];
            $user->name = $row['name'];
            $user->password = $row['password'];
        }
        $stmt = null;
        $dbcon = null;
        return $user;
    }

    // Не используется 
    public static function findByName($name) {

        $mysqli = get_mysqli();
        $res = $mysqli->query("SELECT * FROM user WHERE name = '" . $name . "'");
        // TODO: Обработать ошибку запроса
        if(!$res) return null;

        $res->data_seek(0);
        $row = $res->fetch_assoc();

        $user = new User();
        $user->id = $row['id'];
        $user->fio = $row['fio'];
        $user->email = $row['email'];
        $user->role = $row['role'];
        $user->name = $row['name'];
        $user->password = $row['password'];

        $res->close();
        $mysqli->close();
        return $user;
    }

    
    public static function findAllOrderBy($field = 'fio', $desc = 0) {
        $dbcon = getPDO(); 
        $sql = "select * from user order by " . $field;
        if($desc) $sql = $sql . " desc";
        $users = array();
        foreach ($dbcon->query($sql) as $row) {
            $user = new user();
            $user->id = $row['id'];
            $user->fio = $row['fio'];
            $user->email = $row['email'];
            $user->role = $row['role'];
            $user->name = $row['name'];
            $user->password = $row['password'];
            $users[] = $user;
        }
        $dbcon  = null;
        return $users;
    }

    /**
     * $field - имя поля, по которому фильтровать.
     */
    public static function filter($field, $val) {
        $dbcon = getPDO();
        $stmt = $dbcon->prepare("select * from user where " . $field . " like ?");
        $stmt->bindValue(1, $val);
        $users = array();
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $user = new user();
                $user->id = $row['id'];
                $user->fio = $row['fio'];
                $user->email = $row['email'];
                $user->role = $row['role'];
                $user->name = $row['name'];
                $user->password = $row['password'];
                $users[] = $user;
            }
        } else {
            // TODO:
        }
        $stmt = null;
        $dbcon = null;
        return $users;
    }

    public function save() {
        $dbcon = getPDO();
        $update = $dbcon->prepare("UPDATE user SET fio = :fio, email = :email, " .
            "role = :role, name = :name, password = :password " .
            "WHERE id = :id");
        $update->bindValue(':fio', $this->fio);
        $update->bindValue(':email', $this->email);
        $update->bindValue(':role', $this->role);
        $update->bindValue(':name', $this->name);
        $update->bindValue(':password', $this->password);
        $update->bindValue(':id', $this->id);

        $insert = $dbcon->prepare("INSERT INTO user (fio, email, role, name, password) " .
            "VALUES (:fio, :email, :role, :name, :password)");
        $insert->bindValue(':fio', $this->fio);
        $insert->bindValue(':email', $this->email);
        $insert->bindValue(':role', $this->role);
        $insert->bindValue(':name', $this->name);
        $insert->bindValue(':password', $this->password);
        
        if($this->id > 0) { // Обновление
            if (!$update->execute()) {
                error_log("Ошибка обновления параметров пользователя: " .
                    $update->errorInfo()[2]);
            }
        } else { // Добавление
            if (!$insert->execute()) {
                error_log("Ошибка добавления нового пользователя: " .
                    $insert->errorInfo()[2]);
            }
        } 

        $update = null;
        $insert = null;
        $dbcon = null;
    }
    
    public static function deleteById($id) {
        $dbcon = getPDO();
        $stmt = $dbcon->prepare("DELETE FROM user WHERE id = ?");
        $stmt->bindValue(1, $id);
        if (!$stmt->execute()) {
            error_log("Ошибка удаления пользователя: " .
                $update->errorInfo()[2]);
        }
        $stmt = null;
        $dbcon = null;
    }
}
?>
