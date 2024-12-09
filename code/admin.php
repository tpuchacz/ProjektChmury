<?php
if(session_status() === PHP_SESSION_NONE)
        session_start();
?>
<input type="text" id="searchUser" onkeyup="Search()" placeholder="Wyszukaj użytkownika" title="Type in a name">

<script>
    function Search() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }
</script>

<?php
    include('../code/connection.php');
    $userList = GetUsers();

    if(isset($_POST['user'])){
        foreach ($userList as $user){
            if($user[0] == $_POST['user']){{
                DrawEditPanel($user);
                $oldUsername = $user[1];
            }
            }
        }
    }

    if(isset($_POST['editUserData'])){
        if($_POST['username'] != "" && $_POST['email'] != ""){
            $username = prep($_POST['username']);
            $email = prep($_POST['email']);
            $permissions = prep($_POST['permissions']);
            EditUserData($username, $email, $permissions, $oldUsername);
        }
        else{
            echo '<p id="info">Nie można podawać pustych wartości!</p>';
        }
    }

    echo '<form method="post" action="" id="userList">';
    echo '<ul style="overflow-y: auto;">';
    echo '<li><p id=listLegend">Nazwa | Email | Uprawnienia</p></li>';
    foreach ($userList as $user) {
        if($user[0] != $_SESSION['user_id']){
            echo '<li><input type="radio" value="'. $user[0]. '" name="user" class="listMargin">';
            echo '<label for="'.$user[1].'">'. $user[1]. "|" . $user[2] . "|" . $user[3] .'</li>';
        }
    }
    echo '</ul>';
    echo '<br><input type="submit" value="Wybierz">';
    echo '</form>';

    function DrawEditPanel($user){
        echo '<form method="post" action="" id="editPanel">';
        echo '<input type="hidden" name="user" value="'. $user[0]. '">';
        echo '<label for="username">Nazwa użytkownika</label>';
        echo '<input type="text" id="username" name="username" value="'. $user[1]. '">';
        echo '<label for="email">Email</label>';
        echo '<input type="text" id="email" name="email" value="'. $user[2]. '">';
        echo '<label for="permissions">Uprawnienia</label>';
        echo '<select name="permissions" id="permissions">';
        echo '<option value="user">Użytkownik</option>';
        echo '<option value="author">Autor</option>';
        echo '<option value="admin">Administrator</option>';
        echo '</select>';
        echo '<input type="submit" name="editUserData" value="Zmień dane">';
        echo '</form>';
    }

    function EditUserData($username, $email, $perms, $oldUsername){
        $conn = GetConnection();
        $sql = $conn -> prepare("UPDATE users SET username = ?, email = ?, permissions = ? WHERE username LIKE ?");
        $sql -> bind_param("ssss", $username, $email, $perms, $oldUsername);
        if($sql -> execute()){
            echo "<meta http-equiv='refresh' content='0'>";
        }
        else
            echo '<p id="info">Błąd w zmienianiu danych!</p>';
        $sql -> close();
        $conn -> close();
    }
