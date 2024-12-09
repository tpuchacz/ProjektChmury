<?php
if(session_status() === PHP_SESSION_NONE)
    session_start();
include_once("connection.php");
include_once("validations.php");
$articles = FetchArticles();
$_SESSION['articles'] = $articles;
if(!isset($_GET['str']))
{
    DisplayArticles($articles);
}
else
{
    if(is_numeric($_GET['str']) && $_GET['str'] <= count($articles))
    {
        DisplayDetails();
        DisplayComments(LoadComments($articles[ $_GET['str']-1 ][0]));
        CommentForm();
        echo '<form action="index.php" method="post" class="powrotForm">';
        echo '<input type="submit" name="powrot" value="Powrót">';
        echo '</form>';
        if(isset($_SESSION['user_id']))
        {
            if(isset($_POST['usun'])) {
                if ($_POST['matma'] != "") {
                    if ($_POST['matma'] == $_SESSION['suma']) {
                        DeletePost($articles[$_GET['str'] - 1][0]);
                    }
                }
            }
            if($articles[ $_GET['str']-1 ][6] == $_SESSION['user_id']){
                echo '<form action="" method="post" class="usunForm">';
                echo '<p>Usuwanie postu</p>';
                echo '<label for="matma">Podaj sume:</label>';
                echo '<input type="text" name="matma" id="matma">';
                $a = rand(1,10);
                $b = rand(1,10);
                $suma = $a + $b;
                $_SESSION['suma'] = $suma;
                echo $a." + ".$b. "= ?";
                echo '<input type="submit" name="usun" value="Usuń post">';
                echo '</form>';
                echo '<form action="pages/editor.php" method="post" class="editForm">';
                echo '<input type="hidden" name="tytul" value="'.$_SESSION['articles'][$_GET['str']-1][2].'" >';
                echo '<input type="hidden" name="kategoria" value="'.$_SESSION['articles'][$_GET['str']-1][6].'" >';
                echo '<input type="hidden" name="streszczenie" value="'.$_SESSION['articles'][$_GET['str']-1][3].'" >';
                echo '<input type="hidden" name="tresc" value="'.$_SESSION['articles'][$_GET['str']-1][4].'" >';
                echo '<input type="hidden" name="editing" value="'.$_SESSION['articles'][$_GET['str']-1][0].'">';
                echo '<input type="submit" name="edytuj" value="Edytuj">';
                echo '</form>';
            }
        }
    }
    else
    {
        unset($_GET['str']);
        DisplayArticles($articles);
    }
}

function DisplayArticles(array $articles){
    for($i = 0; $i < count($articles); $i++){
        echo "<div class=\"article\">";
        echo "<a href=\"?str=".($articles[$i][7]+1)."\">";
        echo "<img src=\"images\\".$articles[$i][1].".jpg\" width=\"600\"></img>";
        echo "</a>";
        echo "<p id=\"article-title\">".$articles[$i][2]."</p>";
        echo "<p>".$articles[$i][3]."</p>";
        echo "</div>";
    }
}

function DisplayDetails(){
    global $articles;
    echo "<div class=\"details\">";
    echo "<img src=\"images\\".$articles[ $_GET['str']-1 ][1].".jpg\" width=\"600\"></img>";
    echo "<p id=\"article-title\">".$articles[ $_GET['str']-1 ][2]."</p>";
    echo "<span>".$articles[ $_GET['str']-1 ][4]."</span>";
    echo "</div>";
    echo "<hr>";
}

function CommentForm(){
    echo '<form action="" method="post" class="komentarzForm">';
    if(!isset($_SESSION['login'])){
        echo '<label for="komentarzNick">Pseudonim:</label><br>';
        echo '<input type="text" name="komentarzNick" id="komentarzNick" required><br><br>';
    }
    echo '<label for="komentarzTresc">Komentarz:</label><br>';
    echo '<textarea name="komentarzTresc" id ="komentarzTresc" required></textarea><br><br>';
    echo '<label for="captcha">Przepisz poniższy tekst:</label>';
    echo '<input type="text" name="captcha" id="captchaInForm">';
    include_once "users.php";
    Captcha(true);
    echo '<br><input type="submit" name="dodajKomentarz" value="Dodaj komentarz">';
    echo '</form>';
}

function DisplayComments($comments){
    for($i = 0; $i < count($comments); $i++){
        echo "<div class=\"comment\">";
        if(is_null($comments[$i][1]))
            echo "<p id=\"comment-author\">Anonimowy: ".$comments[$i][2]."</p>";
        else
            echo "<p id=\"comment-author\">Użytkownik: ".$comments[$i][3]."</p>";
        echo "<p id=\"comment-text\">".$comments[$i][0]."</p>";
        echo "</div>";
    }
}