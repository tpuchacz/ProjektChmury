<?php
if(session_status() === PHP_SESSION_NONE)
    session_start();
$error = "";
if(isset($_POST['register']))
{
    $uname = prep($_POST["loginRegister"]);
    $email = prep($_POST["email"]);
    $password = prep($_POST["password"]);
    $passwordRep = prep($_POST["passwordRep"]);

    if (!preg_match("/^[a-zA-Z0-9]{3,20}$/",$uname)) {
        $error = "Dozwolone tylko litery i cyfry w nazwie uzytkownika, oraz powinna miec przynajmniej 3 znaki";
    }
    else if($password == ""){
        $error = "Nie podano hasła!";
    }
    else if($password !== $passwordRep){
        $error = "Hasla sie nie zgadzaja!";
    }
    else if(strlen($password) < 5){
        $error = "Hasło musi zawierać co najmniej 5 znaków!";
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Niepoprawny format adresu email!";
    }
    else if(!CheckCaptcha()){
        $error = "Tekst nie pasuje do tego z obrazku!";
    }
    else{
        Register($uname, $email, $password);
    }
    unset($_POST['register']);
}
if(isset($_POST['login']))
{
    if(isset($_POST['loginLogin']) && isset($_POST['password']))
    {
        $login = prep($_POST["loginLogin"]);
        $password = prep($_POST["password"]);
        if($login != "" && $password != ""){
            if(CheckCaptcha()){
                if(Login($login, $password)){
                    echo "<meta http-equiv='refresh' content='0'>";
                }
            }
        }
        else
            $error = "Nie podano loginu lub hasła!";
    }
    else
    {
        $error = "Nieprawidłowy login lub hasło!";
    }
    unset($_POST['login']);
}

if(isset($_POST['powrot']))
{
    unset($_GET['str']);
}
else if(isset($_POST['dodajKomentarz']))
{
    if(isset($_SESSION['user_id']))
    {
        if(isset($_POST['komentarzTresc']) && strlen($_POST['komentarzTresc']) < 300){
            if(CheckCaptcha()) {
                AddComment($_SESSION['articles'][ $_GET['str']-1 ][0]);
            }
        }
        else{
            $error = 'Zbyt dużo znaków!';
        }
    }
    else
    {
        if(isset($_POST['komentarzNick']) && isset($_POST['komentarzTresc']) && isset($_POST['captcha']))
        {
            if(strlen($_POST['komentarzNick']) < 30 && strlen($_POST['komentarzTresc']) < 300){
                if(CheckCaptcha())
                {
                    AddComment($_SESSION['articles'][ $_GET['str']-1 ][0]);
                }
            }
            else
                $error = 'Zbyt dużo znaków!';
        }
        else
        {
            $error = 'Nie uzupełniono pól!';
        }
    }
}

function CheckCaptcha(){
    global $error;
    if(empty($_POST["captcha"])){
        $error = "*Musisz przepisać powyższy tekst";
        return false;
    }
    else
    {
        if(strtolower($_POST["captcha"]) != $_SESSION['captcha-code']){
            $error = "*Blednie przepisany tekst!";
            return false;
        }
    }
    return true;
}