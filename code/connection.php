<?php
if(session_status() === PHP_SESSION_NONE)
        session_start();
function GetConnection(){
	$conn = mysqli_init();
	mysqli_ssl_set($conn,NULL,NULL, "/home/site/wwwroot/ssl/DigiCertGlobalRootCA.crt.pem", NULL, NULL);
	$servername = getenv('AZURE_MYSQL_HOST');
	$username = getenv('AZURE_MYSQL_USERNAME');
	$password = getenv('AZURE_MYSQL_PASSWORD');
	$db = getenv('AZURE_MYSQL_DBNAME');
	if (!mysqli_real_connect($conn, $servername, $username, $password, $db, 3306))
	    die('Connection error: ' . mysqli_connect_error());
    	return $conn;
}

function FetchArticles(): array
{
    $articles = [];
    $conn = GetConnection();
    $sql = "SELECT * FROM articles";
    $i = 0;
    if ($result = $conn -> query($sql)) {
        while ($row = $result -> fetch_row()) {
            $articles[$i] = [$row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $i];
            $i++;
        }
        $result -> free_result();
    }
    $conn -> close();
    return $articles;
}

function LoadComments($article_id): array
{
    $conn = GetConnection();
    $comments = [];
    $sql = $conn -> prepare("SELECT comment, author_id, anon_author, author FROM comments WHERE article_id = ?");
    $sql -> bind_param("i", $article_id);
    $sql -> execute();
    $sql -> bind_result($comment, $author_id, $anon_author, $author);
    $i = 0;
    while($sql->fetch()){
        $comments[$i] = [$comment, $author_id, $anon_author, $author];
        $i++;
    }
    $sql -> close();
    $conn -> close();
    return $comments;
}

function AddComment($article_id){
    global $error;
    $conn = GetConnection();
    if(!isset($_SESSION['user_id']))
        $sql = $conn -> prepare("INSERT INTO comments(comment, article_id, anon_author) VALUES (?, ?, ?)");
    else
        $sql = $conn -> prepare("INSERT INTO comments(comment, article_id, author_id, author) VALUES (?, ?, ?, ?)");
    $tresc = prep($_POST['komentarzTresc']);
    if(!isset($_SESSION['user_id'])){
        $nick = prep($_POST['komentarzNick']);
        $sql -> bind_param("sis", $tresc, $article_id, $nick);
    }
    else{
        $nick = $_SESSION['user_id'];
        $author = $_SESSION['login'];
        $sql -> bind_param("siss", $tresc, $article_id, $nick, $author);
    }

    if($sql -> execute())
        $error = 'Poprawnie dodano komentarz!';
    else
        $error = 'Błąd w dodawaniu komentarza!';
    $sql -> close();
    $conn -> close();
}

function GetUsers(){
    $conn = GetConnection();
    $sql = $conn -> prepare("SELECT user_id, username, email, permissions FROM users");
    $sql -> execute();
    $sql -> bind_result($user_id, $username, $email, $permissions);
    $i = 0;
    while($sql->fetch()){
        $userList[$i] = [$user_id, $username, $email, $permissions];
        $i++;
    }
    $sql -> close();
    $conn -> close();
    return $userList;
}

function DeletePost($postNum){
    global $error;
    $conn = GetConnection();
    $sql = "DELETE FROM comments WHERE article_id = '".$postNum."'";
    if($conn -> query($sql)){
        echo "<meta http-equiv='refresh' content='0'>";
    }
    else
        $error = 'Błąd w usuwaniu posta!';
    $error .= $conn->error;

    $sql = "DELETE FROM articles WHERE article_id = '".$postNum."'";
    if($conn -> query($sql)){
        echo "<meta http-equiv='refresh' content='0'>";
    }
    else
        $error = 'Błąd w usuwaniu posta!';
        $error .= $conn->error;
    $conn -> close();
}

function AddPost($nazwaPliku, $tytul, $streszczenie, $tresc, $kategoria){
    global $error;
    $conn = GetConnection();
    $sql = $conn -> prepare("INSERT INTO articles(image_name, title, text, description, kategoria, author_id) VALUES (?, ?, ?,?,?,?)");
    $sql -> bind_param("sssssi", $nazwaPliku, $tytul, $streszczenie, $tresc, $kategoria, $_SESSION['user_id']);

    if($sql -> execute())
        $error = 'Poprawnie dodano post!';
    else
        $error = 'Błąd w dodawaniu postu!';
    $sql -> close();
    $conn -> close();
}

function EditPost($tytul, $tresc, $streszczenie, $kategoria, $articleId){
    global $error;
    $conn = GetConnection();
    $sql = $conn -> prepare("UPDATE articles SET title = ?, text = ?, description = ?, kategoria = ?  WHERE article_id = ?");
    $sql -> bind_param("ssssi", $tytul, $streszczenie, $tresc, $kategoria, $articleId);

    if($sql -> execute()){
        $error = 'Poprawnie edytowano post!';
        $succeed = true;
    }
    else{
        $error = 'Błąd w edytowaniu postu!';
        $succeed = false;
    }

    $sql -> close();
    $conn -> close();
    return $succeed;
}

function prep($data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}
