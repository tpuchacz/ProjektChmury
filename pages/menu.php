<?php
if(session_status() === PHP_SESSION_NONE)
    session_start();
if(isset($_POST['logout']))
    {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0'>";
    }
?>
<ul>
    <?php
        echo '<li><a href="/index.php">Strona główna</a></li>';
        if(!isset($_SESSION['login'])){
            echo '<li><a href="../pages/login.php">Logowanie</a></li>';
            echo '<li><a href="../pages/register.php">Rejestracja</a></li>';
        }
        else{
            if($_SESSION['permissions'] == "author"){
                echo '<li><a href="../pages/editor.php">Dodaj post</a></li>';
            }
            echo '<form method="post" action=""><input type="submit" name="logout" value="Wyloguj się"></form>';
        }
    ?>
</ul>
