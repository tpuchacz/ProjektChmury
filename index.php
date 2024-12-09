<?php
if(session_status() === PHP_SESSION_NONE)
    session_start();
?>
<!doctype html>
<html>
    <head>
        <title>Blog samochodowy</title>
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/articles.css">
    </head>
    <body>
    <header>
        <h1>Blog samochodowy</h1>
    </header>
    <div class="sections">
        <div class="menu">
            <?php include "pages/menu.php"; ?>
        </div>
        <div class="articles">
            <?php include "code/articles.php"; ?>
            <p id="info" style="color:#9e0c02;"><?php echo $error; ?></p>
        </div>
        <div class="side">
            <?php include "pages/side.php";?>
        </div>
    </div>
    <footer>
        <?php include "pages/footer.php";?>
    </footer>
    </body>
</html>