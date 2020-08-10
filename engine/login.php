<?php

function alreadyLoggedIn(){
    return isset($_SESSION["user"]);
}

function checkAuthWithCookie(){
    $result = false;

    if(isset($_COOKIE['id_user']) && isset($_COOKIE['cookie_hash'])){
        $link = getConnection();
        $sql = "SELECT id_user, user_name, user_password FROM user WHERE id_user='" . mysqli_real_escape_string($link, $_COOKIE['id_user']) . "'";
        $user_data = getRowResult($sql, $link);

        if(($user_data['user_password'] !== $_COOKIE['cookie_hash']) || ($user_data['id_user'] !== $_COOKIE['id_user'])){
            setcookie("id_user", "", time() - 3600 * 24 * 30 * 12, "/");
            setcookie("cookie_hash", "", time() - 3600 * 24 * 30 * 12, "/");
        } else {
            header("Location: /user/");
        }

        return result;
    }
}

function authWithCredentials(){
    $username = $_POST["login"];
    $password = $_POST["password"];

    $link = getConnection();

    $sql = "SELECT id_user, user_name, user_login, user_password FROM user WHERE user_login = '" . mysqli_real_escape_string($link, $username) . "'";
    $user_data = getRowResult($sql, $link);

    $isAuth = 0;

    if($user_data){
        if(checkPassword($password, $user_data['user_password'])){
            $isAuth = 1;
        }
    }

    if(isset($_POST['rememberme']) && $_POST['rememberme'] == 'on'){
        setcookie("id_user", $user_data['id_user'], time() + 86400, "/");
        setcookie("cookie_hash", $user_data['user_password'], time()+86400, "/");
    }

    $_SESSION['user'] = $user_data;

    return $isAuth;
}

function hashPassword($password){
    $salt = md5(uniqid(SALT2, true));
    $salt = substr(strtr(base64_encode($salt), '+', '.'), 0, 22);
    return crypt($password, '$2a$08$' . $salt);
}

function checkPassword($password, $hash){
    return crypt($password, $hash) === $hash;
}

function getRegister(){
    $link = getConnection();

    $username = mysqli_real_escape_string($link, prepareString($_POST['name']));
    $userlogin = mysqli_real_escape_string($link, prepareString($_POST['login']));
    $password = mysqli_real_escape_string($link, prepareString($_POST['password']));
    $password = hashPassword($password);

    $sql = "INSERT into user (`user_name`, `user_login`, `user_password`) VALUES ('$username', '$userlogin', '$password')";
    executeQuery($sql);
}

