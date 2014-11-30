<?php

class Login {

    public static function register($username, $password) {
        if (Login::userExists($username) == false) {
            $options = array('cost' => 12);
            $salt = Login::generateSalt();
            $hash = password_hash($password . $salt, PASSWORD_BCRYPT, $options);
            include 'config.php';
            $con = mysqli_connect($conf['login-url'], $conf['login-user'], $conf['login-password'], $conf['login-db']) or die("Connection problem.");
            $query = $con->prepare("INSERT INTO `" . $conf['login-table'] . "` (`username`, `password`, `salt`) VALUES (?, ?, ?);");
            $query->bind_param("sss", $username, $hash, $salt);
            $query->execute();
            return 1;
        }
        return 0;
    }

    public static function getPassword($username, $password) {
        include 'config.php';
        $con = mysqli_connect($conf['login-url'], $conf['login-user'], $conf['login-password'], $conf['login-db']) or die("Connection problem.");
        $query = $con->prepare("SELECT * FROM `" . $conf['login-table'] . "` WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $query->bind_result($dbuser, $dbpassword, $dbsalt);
        if ($row = $query->fetch()) {
            if (password_verify($password . $dbsalt, $dbpassword)) {
                return true;
            }
        }
        return false;
    }

    public static function doLogin($username, $password) {
        if (Login::userExists($username)) {
            if (Login::getPassword($username, $password)) {
                return true;
            }
        }
        return false;
    }

    public static function userExists($username) {
        include 'config.php';
        $con = mysqli_connect($conf['login-url'], $conf['login-user'], $conf['login-password'], $conf['login-db']) or die("Connection problem.");

        $s = "SELECT COUNT(*) AS num FROM `" . $conf['login-table'] . "` WHERE `username` = ?";
        $query = $con->prepare($s);
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            foreach ($row as $r) {
                if ($r > 0) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function generateSalt() {
        $length = 20;
        $sets = array();
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        $sets[] = '123456789';
        $sets[] = '!@#$%&*?{[()]}|<>^\'\\/~-_^+.,:;= ';
        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++) {
            $password .= $all[array_rand($all)];
        }

        $password = str_shuffle($password);
        return $password;
    }

    public static function updatePassword($username, $oldpassword, $password) {
        if (Login::getPassword($username, $oldpassword)) {
            $options = array('cost' => 12);
            $salt = Login::generateSalt();
            $hash = password_hash($password . $salt, PASSWORD_BCRYPT, $options);
            include 'config.php';
            $con = mysqli_connect($conf['login-url'], $conf['login-user'], $conf['login-password'], $conf['login-db']) or die("Connection problem.");
            $query = $con->prepare("UPDATE `" . $conf['login-table'] . "` SET `password`=?,`salt`=? WHERE `username`=?;");
            $query->bind_param("sss", $hash, $salt, $username);
            $query->execute();
            return true;
        }
        return false;
    }

}
