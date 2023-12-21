<style>
		background-color: #c0c0c0;
</style>
<!-- Formularze logowania i edycji z poziomu admina -->
<?php
require('../cfg.php');
echo '<link rel="stylesheet" href="../css/style.css">';
function FormularzLogowania()
{	
    $wynik = '
        <div class="logowanie">
            <h1 class="heading">Panel CMS:</h1>
            <div class="logowanie">
                <form method="post" name="LoginForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                    <table class="logowanie">
                        <tr><td class="logowanie"></td></tr>
                        <tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
                        <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
                        <tr><td>&nbsp; </td><td><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td></tr>
                    </table>
                </form>
            </div>
        </div>
    ';
    return $wynik;
}

if (isset($_POST['x1_submit'])) {
    if ($_POST['login_email'] == $login && $_POST['login_pass'] == $pass)
    {
        $_SESSION['logged_in'] = true;
        ListaPodstron();
    }
    else
    {
        echo 'Zaloguj sie ponownie.</br></br>';
        echo '<a href="admin.php"</a>';
    }
} 
else {
	echo FormularzLogowania();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add"])) {
        DodajNowaPodstrone();
    }
    if (isset($_POST["edit"])) {
        $id = $_POST["edit"];
        EdytujPodstrone($id);
    }
    if (isset($_POST["del"])) {
        $id = $_POST["del"];
        UsunPodstrone($id);
    }
}


function ListaPodstron() {
        global $link;

    $query = "SELECT * FROM page_list ORDER BY id ASC LIMIT 100";
    $result = mysqli_query($link, $query);
	echo '<form method="post">';
    echo '<table>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Tytuł podstrony</th>';
    echo '<th>Opcje</th>';
    echo '</tr>';
    while($row = mysqli_fetch_array($result)){
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['page_title'] . '</td>';
        echo '<td>';
        echo '<button type="submit" name="edit" value="'.$row['id'].'">Edytuj</button>';
        echo '<button type="submit" name="del" value="'.$row['id'].'">Usuń</button>';
        echo '</td>';
        echo '</tr>';
    }	
    echo '</table>';
	echo '<tr>';
		echo '<button type="submit" name="add">Dodaj Nową Podstrone</button>';
	echo '</tr>';
	echo '</table>';
	echo '</form >';
}

function EdytujPodstrone($id){
    global $link;
    $query = "SELECT * FROM page_list WHERE id=$id";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);
	
	echo '<form method="post"';
    echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
    echo 'Tytuł: <input type="text" name="title" value="' . $row['page_title'] . '"><br>';
    echo 'Treść: <textarea name="content">' . $row['page_content'] . '</textarea><br>';
    echo '<label>Aktywna: <input type="checkbox" name="active" ' . ($row['status'] ? 'checked' : '') . '></label><br>';
    echo '<input type="submit" value="Zapisz">';
    echo '</form>';
		
}

function DodajNowaPodstrone() {
    echo '<form method="post">';
    echo 'Tytuł: <input type="text" name="tytul" value="Tytuł Nowej Podstrony"><br>';
    echo 'Treść: <textarea name="tresc">Treść Nowej Podstrony</textarea><br>';
    echo '<label>Aktywna: <input type="checkbox" name="aktywna" checked></label><br>';
    echo '<input type="submit" value="Dodaj">';
    echo '</form>';
    }

function UsunPodstrone($id) {
	global $link;
    $query = "SELECT * FROM page_list WHERE id=$id LIMIT 1";
    mysqli_query($link, $query);
    }


?>
<div style="item-align: top; position: fixed; bottom : 1px; right: 1px; padding: 10px;">
<a href="../lab1.php">POWRÓT</a>
</div>