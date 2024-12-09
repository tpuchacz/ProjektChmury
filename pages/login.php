<?php
if(session_status() === PHP_SESSION_NONE)
        session_start();
?>

<!doctype html>
<html lang="pl-PL">
<head>
    <link rel="stylesheet" href="../css/main.css">
    <title>Rejestracja</title>
</head>
<header>
    <h1>Blog samochodowy</h1>
</header>
<div class="sections">
    <div class="menu">
        <?php include('menu.php'); ?>
    </div>
    <div class="articles">
        <?php include "../code/users.php";?>
        <?php include "../code/validations.php"; ?>
        <form action="" method="post" id="loginForm">
            <label for="loginLogin">Login:</label>
            <input type="text" name="loginLogin" id="loginLogin">
            <label for="password">Hasło:</label>
            <input type="password" name="password" id="password">
            <label for="captcha">Przepisz poniższy tekst:</label>
            <input type="text" name="captcha" id="captchaInForm">
            <?php Captcha(false); ?>
            <input type="submit" name="login" id="formSubmitButton" value="Zaloguj się">
        </form>
        <p id="info" style="color:#9e0c02;"><?php echo $error; ?></p>
    </div>
    <div class="side">
        <?php include "side.php";?>
    </div>
</div>
<footer>
    <?php include "footer.php";?>
</footer>
</html>
