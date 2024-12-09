<?php
if(session_status() === PHP_SESSION_NONE)
        session_start();
    if(isset($_SESSION['user_id'])){
        echo '<p id="loggedAs">Zalogowano jako: '.$_SESSION['login'].'</p>';
        if($_SESSION['permissions'] == "admin"){
            echo '<a href="../pages/panel.php" id="panelLink">Panel administracyjny</a>';
        }
    }
