<?php
    if(session_status() === PHP_SESSION_NONE)
        session_start();
    include_once "connection.php";
    $error = $captchaText = "";

    function Register($uname, $email, $password){
        global $error;
        $conn = GetConnection();
        $sql = $conn -> prepare("INSERT INTO users(username, email, hash) VALUES (?, ?, ?)");
        $uname = prep($uname);
        $email = prep($email);
        $password = prep($password);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql -> bind_param('sss', $uname, $email, $hash);
        if(CheckIfUserExists($uname)){
            $error = "Użytkownik o takim loginie już istnieje!";
        }
        else
        {
            if($sql -> execute())
                $error = 'Utworzono konto!';
            else
                $error = 'Błąd w tworzeniu konta!';
        }
        $sql -> close();
        $conn -> close();
    }

    function Login($uname, $password){
        global $error;
        $conn = GetConnection();
        $sql = $conn -> prepare("SELECT hash, permissions, user_id FROM users WHERE username = ?");
        $sql -> bind_param("s", $uname);
        $sql -> execute();
        $sql -> bind_result($hash, $perms, $user_id);
        if($sql->fetch()){
            if(password_verify($password,$hash)){
                $_SESSION['login'] = $uname;
                $_SESSION['permissions'] = $perms;
                $_SESSION['user_id'] = $user_id;
                $zalogowano = true;
            }
            else{
                $error = "Nieprawidłowe hasło!";
                $zalogowano = false;
            }
        }
        else
        {
            $error = "Użytkownik o takim loginie nie istnieje!";
            $zalogowano = false;
        }

        $sql -> close();
        $conn -> close();
        return $zalogowano;
    }

    function CheckIfUserExists($uname){
        $conn = GetConnection();
        $sql = $conn -> prepare("SELECT username FROM users WHERE username LIKE ?");
        $sql -> bind_param('s', $uname);
        $sql-> execute();
        if($sql->fetch()){
            $exists = true;
        }
        else{
            $exists = false;
        }
        $sql -> close();
        $conn -> close();
        return $exists;
    }

    function Captcha($isIndex){
        if($isIndex)
            $path = "captcha/";
        else
            $path = "../captcha/";
        $tab = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'l'];
        global $captchaText;
        $captchaText = "";
        $style = 'background-image: url("%s"); width:50px; height:50px';
        echo '<div class="captcha">';
        for($i = 0; $i < 5; $i++){
            $r = rand(0,10);
            echo '<div id="captcha'.$i.'"></div>';
            echo '<style>#captcha'.$i.'{background-image: url("'.$path.$tab[$r].'.png"); width:50px; height:50px;}</style>';
            //echo '<img src="'.$path.$tab[$r].'.png" width="50"></img>';
            $captchaText .= $tab[$r];
        }
        echo "</div>";
        $_SESSION['captcha-code'] = $captchaText;
    }