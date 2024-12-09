<?php
if(session_status() === PHP_SESSION_NONE)
    session_start();
?>
<!doctype html>
<html>
<head>
    <title>Panel administracyjny</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/articles.css">
</head>
<body>
<header>
    <h1>Panel administracyjny</h1>
</header>
<div class="sections">
    <div class="menu">
        <?php include "menu.php"; ?>
    </div>
    <div class="adminPanel">
        <?php
        if(isset($_SESSION['user_id']))
        {
            if($_SESSION['permissions'] == "admin"){
                include('../code/admin.php');
            }
            else{
                echo '<h2>Nie posiadasz uprawnień do tego panelu!</h2>';
            }
        }
        else{
            echo '<h2>Jeśli jesteś administratorem najpierw się zaloguj!</h2>';
        }
        ?>
    </div>
    <div class="side">
        <?php include "side.php";?>
    </div>
</div>
<footer>
    <?php include "footer.php";?>
</footer>
</body>
</html>