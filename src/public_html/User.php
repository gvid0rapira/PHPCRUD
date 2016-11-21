<?php

include_once 'dbaccess.php';

class User {
    public $id = 0;
    public $fio = '';
    public $email = '';
    public $role = '';
    public $name = '';
    public $password = '';

    public static function findById($id) {

        $mysqli = get_mysqli();
        $res = $mysqli->query("SELECT * FROM user WHERE id = " . $id);
        // TODO: Обработать ошибку запроса
        if(!$res) return null;
        $res->data_seek(0);
        $row = $res->fetch_assoc();

        $user = new User();
        $user->id = $id;
        $user->fio = $row['fio'];
        $user->email = $row['email'];
        $user->role = $row['role'];
        $user->name = $row['name'];
        $user->password = $row['password'];

        $res->close();
        $mysqli->close();
        return $user;
    }
    
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
    
    public static function findAll() {
        $mysqli = get_mysqli(); 
        $res = $mysqli->query("SELECT * FROM user ORDER BY fio");
        $users = array();
        for($i = 0; $i < $res->num_rows; $i++) {
            $res->data_seek($i);
            $row = $res->fetch_assoc();
            $user = new User();
            $user->id = $row['id'];
            $user->fio = $row['fio'];
            $user->email = $row['email'];
            $user->role = $row['role'];
            $user->name = $row['name'];
            $user->password = $row['password'];
            $users[] = $user;
        }
        return $users;
    }

    public function save() {
        if($this->id > 0) { // Обновление
            $sql = "UPDATE user SET fio = '" . $this->fio .
                              "', email = '" . $this->email .
                               "', role = '" . $this->role .
                               "', name = '" . $this->name .
                           "', password = '" . $this->password .
                             "' WHERE id = " . $this->id;
            
        } else { // Добавление
            $sql = "INSERT INTO user (fio, email, role, name, password) " .
                        "VALUES ('" . $this->fio .
                             "', '" . $this->email .
                             "', '" . $this->role .
                             "', '" . $this->name .
                             "', '" . $this->password .
                             "')";
        } 

        $mysqli = get_mysqli();
        if(!$mysqli->query($sql)) {
           error_log("Ошибка сохранения параметров пользователя: " .
                $mysqli->error); 
        }
    }
    
    public static function deleteById($id) {
        $mysqli = get_mysqli();
        $sql = "DELETE FROM user WHERE id = " . $id;
        if(!$mysqli->query($sql)) {
           error_log("Ошибка удаления пользователя: " .
                $mysqli->error); 
        }
    }
}
?>
