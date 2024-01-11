<?php
require('cfg.php');
echo '<link rel="stylesheet" href="../css/style.css">';

// Formularz logowania dla admina
function FormularzLogowania()
{   
    $wynik = '
        <div class="logowanie">
            <h1 class="heading">Panel CMS:</h1>
            <div class="logowanie">
                <form method="post" name="LoginForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                    <table class="logowanie">
                        <tr><td class="logowanie"></td></tr>
                        <tr><td class="log4_t">[login]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
                        <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
                        <tr><td>&nbsp; </td><td><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td></tr>
                    </table>
                </form>
            </div>
        </div>
    ';
    return $wynik;
}

// Funkcja wyswietlajaca liste podstron
function ListaPodstron() {
    $mysqli = new mysqli("localhost", "root", "", "moja_strona");
    $query = "SELECT * FROM page_list ORDER BY id ASC LIMIT 100";
    $result = mysqli_query($mysqli, $query);
    echo '<form method="post">';
    echo '<table>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Tytuł podstrony</th>';
    echo '<th>Opcje</th>';
    echo '</tr>';
    while ($row = mysqli_fetch_array($result)) {
		if($row['status'] == 1){
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['page_title'] . '</td>';
        echo '<td>';
        echo ' <a href="?edit=' . $row['id'] . '">Edytuj</a>' . ' <a href="?delete=' . $row['id'] . '">Usuń</a>';
        echo '</td>';
        echo '</tr>';
		}
    }
    echo '</table>';
    echo '<tr>';
    echo ' <a href="?add=">Dodaj Podstrone</a>';
    echo '</tr>';
    echo '</table>';
	echo ' <a href="?logout=">Wyloguj</a>';
    echo '</form >';
}

// Funkcja edytujaca
function EdytujPodstrone($id)
{
    $mysqli = new mysqli("localhost", "root", "", "moja_strona");
    if (isset($_GET['edit'])) {
        $editId = $id;
        $query = "SELECT * FROM page_list WHERE id=$editId LIMIT 1";
        $result = mysqli_query($mysqli, $query);
        $row = mysqli_fetch_array($result);

        echo '<form method="post" action="">
                <input type="int" name="id" value="' . $row['id'] . '">
                Tytuł: <input type="text" name="tytul" value="' . htmlspecialchars($row['page_title']) . '"><br>
                Treść: <input type="textarea" name="zawartosc" value="' . htmlspecialchars($row['page_content']) . '"><br>
				<label>Aktywna: <input type="checkbox" name="status" checked></label><br>
                <input type="submit" value="Zapisz">
            </form>';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $editId = $_POST['id'];
            $tytul1 = $_POST['tytul'];
            $zawartosc1 = $_POST['zawartosc'];

            $query = "UPDATE page_list SET page_title='$tytul1', page_content='$zawartosc1' WHERE id='$editId' LIMIT 1";
            mysqli_query($mysqli, $query);
			header("Location: admin.php");
            exit();
        }
    }
	echo '<a href="./admin.php">Powrót</a>';
}

// Funkcja dodajaca
function DodajNowaPodstrone()
{
    $mysqli = new mysqli("localhost", "root", "", "moja_strona");

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tytul'])) {
        $tytul = $_POST['tytul'];
        $zawartosc = $_POST['zawartosc'];
        $status1 = $_POST['status'];

        $tytul_clear = mysqli_real_escape_string($mysqli, $tytul);
        $zawartosc_clear = mysqli_real_escape_string($mysqli, $zawartosc);

        $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$tytul_clear', '$zawartosc_clear', '$status1')";
        mysqli_query($mysqli, $query);

        header("Location: admin.php");
        exit();
    }
	echo '<form method="post" action="">
            <label for="tytul">Tytuł:</label>
            <input type="text" name="tytul" required><br/><br/>
			
			<label for="zawartosc">Zawartosc:</label>
            <input type="textarea" name="zawartosc" required><br/><br/>
			
			<label for="status">Status</label>
            <input type="int" name="status" required><br/><br/>
			
            <button type="submit">Dodaj Podstrone</button>
          </form>';
	echo '<a href="./admin.php">Powrót</a>';
}

// Funkcja usuwajaca
function UsunPodstrone($id)
{
    $mysqli = new mysqli("localhost", "root", "", "moja_strona");

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $deleteId = $_POST['id'];

        $deleteQuery = "DELETE FROM page_list WHERE id='$deleteId'";
        mysqli_query($mysqli, $deleteQuery);
        $mysqli->close();
		
        header("Location: admin.php");
        exit();
    }

    $query = "SELECT * FROM page_list WHERE id=$id";
    $result = mysqli_query($mysqli, $query);
    $row = mysqli_fetch_assoc($result);

    echo '<form method="post">';
    echo '<input type="hidden" name="id" value="' . $id . '">';
    echo '<p>Czy na pewno chcesz usunąć tę podstronę?</p>';
    echo '<input type="submit" value="Tak">';
    echo '</form>';
	echo '<a href="./admin.php">Powrót</a>';
}

// Funkcja sprawdzajaca logowanie admina
function SprawdzCzyZalogowany()
{
    session_start();
	
    // Sprawdza czy admin jest zalogowany
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        return true;
    }
	return false;
}

if (!SprawdzCzyZalogowany()) {
    if (isset($_POST['x1_submit'])) {
        if ($_POST['login_email'] == $login && $_POST['login_pass'] == $pass) {
            $_SESSION['logged_in'] = true;
            Zalogowany();
        } else {
            echo 'Zaloguj się ponownie.</br></br>';
            echo FormularzLogowania();
        }
    } else {
		
        echo FormularzLogowania();
    }
} else {
   Zalogowany();
}

// Funkcje po zalogowaniu i sprawdzeniu poprawnosci
function Zalogowany(){
	 if (isset($_GET['add'])) {
		DodajNowaPodstrone();
	} elseif (isset($_GET['edit'])) {
		$id = $_GET['edit'];
		EdytujPodstrone($id);
	} elseif (isset($_GET['delete'])) {
		$id = $_GET['delete'];
		UsunPodstrone($id);
	} elseif (isset($_GET['logout'])) {
		session_unset();
		session_destroy();
		header("Location: admin.php");
		exit();
	} else {
		ListaPodstron();
		echo '<a href="showcategory.php">Sklep</a>';
	}
}

?>
<div style="item-align: top; position: fixed; bottom : 1px; right: 10px; padding: 10px;">
<a href="../lab1.php">STRONA GŁÓWNA</a>
</div>