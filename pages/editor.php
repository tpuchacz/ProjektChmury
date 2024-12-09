<?php
if(session_status() === PHP_SESSION_NONE)
    session_start();
include_once("../code/connection.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edytor</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
<header>
    <h1>Blog samochodowy</h1>
</header>
<div class="sections">
    <div class="menu">
        <?php include "../pages/menu.php"; ?>
    </div>
    <div class="articles">
        <?php
        echo $_POST['editing'];
        $tytul = $tresc = $plik = $kategoria = $streszczenie = $error = "";
        if(isset($_POST["tytul"])){
            $tytul = $_POST["tytul"];
        }
        if(isset($_POST["tresc"])){
            $tresc = $_POST["tresc"];
        }
        if(isset($_POST["streszczenie"])){
            $streszczenie = $_POST["streszczenie"];
        }
        if(isset($_POST['kategoria'])){
            $kategoria = $_POST['kategoria'];
        }
        if(isset($_POST['edytuj'])){
            $_SESSION['editing'] = true;
        }
        if($_SESSION['editing'])
        {
            if(isset($_POST['edytuj2']))
            {
                if($tytul != "" && strlen($tytul) < 50) {
                    $tytul = prep($tytul);
                } else if ($tresc != "" && strlen($tresc) < 1000) {
                    $tresc = prep($tresc);
                } else if ($streszczenie != "" && strlen($streszczenie) < 100) {
                    $streszczenie = prep($streszczenie);
                } else if ($kategoria != "" && strlen($kategoria) < 30) {
                    $kategoria = prep($kategoria);
                }
                if(EditPost($tytul, $tresc, $streszczenie, $kategoria, $_POST['editing'])){
                    $_SESSION['editing'] = false;
                }
            }
        }
        else {
            if (isset($_POST['wyslij'])) {
                if (isset($_FILES['zdjecie']) && !empty($_FILES['zdjecie'])) {
                    $path = "../images/";
                    $path = $path . basename($_FILES['zdjecie']['name']);

                    if (move_uploaded_file($_FILES['zdjecie']['tmp_name'], $path)) {
                        if ($_POST["tytul"] != "" && strlen($_POST["tytul"]) < 50) {
                            $tytul = prep($_POST["tytul"]);
                        } else if ($_POST["tresc"] != "" && strlen($_POST["tresc"]) < 1000) {
                            $tresc = prep($_POST["tresc"]);
                        } else if ($_POST["streszczenie"] != "" && strlen($_POST["streszczenie"]) < 100) {
                            $streszczenie = prep($_POST["streszczenie"]);
                        } else if ($_POST["kategoria"] != "" && strlen($_POST["kategoria"]) < 30) {
                            $kategoria = prep($_POST["kategoria"]);
                        }
                        $filename = pathinfo($path, PATHINFO_FILENAME);
                        AddPost($filename, $tytul, $streszczenie, $tresc, $kategoria);
                    } else {
                        $error = "Błąd przy dodawaniu pliku!";
                    }
                }
            }
        }
        ?>
        <form method="post" action="" id="edytor" enctype="multipart/form-data">
            <label for="zdjecie">Wgraj zdjęcie:</label>
            <input type="file" id="zdjecie" name="zdjecie" accept="image/jpeg"/><br><br>
            <label for="tytul">Tytuł: </label>
            <input type="text" id="tytul" name="tytul" value="<?php echo $tytul;?>" required><br><br>
            <label for="kategoria">Kategoria: </label>
            <input type="text" id="kategoria" name="kategoria" value="<?php echo $kategoria;?>" required><br><br>
            <label for="streszczenie">Streszczenie: </label>
            <textarea id="streszczenie"  rows="2" cols="30" name="streszczenie" required><?php echo $streszczenie;?></textarea><br><br>
            <textarea rows="10" cols="50" id="tresc" name="tresc" required><?php echo $tresc;?></textarea><br><br>
            <input type="submit" name="podglad" value="Podgląd" class="button">
            <?php
            if(!isset($_POST["editing"]) && isset($_POST["tytul"]) && isset($_POST["tresc"]) && isset($_POST["streszczenie"]) && isset($_POST["kategoria"])){
                echo '<input type="submit" name="wyslij" value="Wyślij" id="podglad" class="button">';
            }
            else if(isset($_POST["editing"]) && isset($_POST["tytul"]) && isset($_POST["tresc"]) && isset($_POST["streszczenie"]) && isset($_POST["kategoria"])){
                echo '<input type="submit" name="edytuj2" value="Edytuj" id="podglad" class="button">';
            }
            ?>

        </form>
        <p id="info"><?php echo $error;?></p>
        <p style="align-self: center; margin:auto;">
            Legenda:<br>
            '[b][/b]' => Pogrubienie,<br><br>
            '[u][/u]' => Podkreślenie,<br><br>
            '[i][/i]' => Pochyłe,<br><br>
            '[s][/s]' => Podkreślenie,
        </p>
        <?php
        if(isset($_POST['podglad'])){
            include_once("editorPreview.php");
            if(isset($_POST["tytul"]) && isset($_POST["tresc"]) && isset($_POST["streszczenie"]) && isset($_POST["kategoria"])){
                DisplayPreview($_POST['tytul'], $_POST['streszczenie'], $_POST['tresc']);
            }
        }
        ?>
    </div>
    <div class="side">
        <?php include "../pages/side.php";?>
    </div>
</div>
<footer>
    <?php include "../pages/footer.php";?>
</footer>
</body>
</html>