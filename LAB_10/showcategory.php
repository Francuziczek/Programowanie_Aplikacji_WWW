<?php
//funkcja pokazujaca kategorie sklepu
function PokazKategorie($matka = 0)
{
    $matka_clear = (int)$matka;
    $mysqli = new mysqli("localhost", "root", "", "moja_strona");

    $query = "SELECT * FROM sklep_kategorie WHERE matka='$matka_clear'";
    $result = mysqli_query($mysqli, $query);

    echo '<ul>';
    while ($row = mysqli_fetch_array($result)) {
        echo '<li>' . ' ' . htmlspecialchars($row['nazwa']) . ' <a href="?edit=' . $row['id'] . '">Edytuj</a>' . ' <a href="?delete=' . $row['id'] . '">Usuń</a>';

        if ($matka_clear == 0) {
            echo ' <a href="?add=' . $row['id'] . '">DodajKategorie</a>';
        }

        echo '</li>';
        PokazKategorie($row['id']);
    }
    echo '</ul>';
}
//Edytowanie kategorii produktu
function EdytujKategorie($id)
{
    $mysqli = new mysqli("localhost", "root", "", "moja_strona");

    if (isset($_GET['edit'])) {
        $editId = $_GET['edit'];
        $query = "SELECT * FROM sklep_kategorie WHERE id='$editId' LIMIT 1";
        $result = mysqli_query($mysqli, $query);
        $row = mysqli_fetch_array($result);

        echo '<form method="post" action="">
                <input type="hidden" name="edit_id" value="' . $row['id'] . '">
                <label for="new_name">Nowa nazwa:</label>
                <input type="text" name="new_name" value="' . htmlspecialchars($row['nazwa']) . '" required>
                <button type="submit">Zapisz zmiany</button>
              </form>';
    }
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $editId = $_POST['edit_id'];
    $newName = $_POST['new_name'];

    $query = "UPDATE sklep_kategorie SET nazwa='$newName' WHERE id='$editId'";
    mysqli_query($mysqli, $query);

    header("Location: showcategory.php");
    exit();
}
}
//funkcja usuwajaca kategorie sklepu i jej wszystkie dzieci
function UsunKategorie($id)
{
    $mysqli = new mysqli("localhost", "root", "", "moja_strona");

    if (isset($_GET['delete'])) {
        $deleteId = $_GET['delete'];

        $query = "SELECT id FROM sklep_kategorie WHERE matka='$deleteId'";
        $result = mysqli_query($mysqli, $query);
		
        while ($row = mysqli_fetch_array($result)) {
            UsunKategorie($row['id']);
        }
		
        $deleteQuery = "DELETE FROM sklep_kategorie WHERE id='$deleteId'";
        mysqli_query($mysqli, $deleteQuery);
		$mysqli->close();

        header("Location: showcategory.php");
        exit();
    }
}

function DodajKategorie()
{
    $mysqli = new mysqli("localhost", "root", "", "moja_strona");

    if (isset($_POST['nazwa'])) {
        $nazwa = $_POST['nazwa'];
        $matka = $_POST['matka'];

		$query = "SELECT id FROM sklep_kategorie WHERE nazwa='$matka'";
		$result = mysqli_query($mysqli, $query);
    
		if ($row = mysqli_fetch_assoc($result)) {
			$matka_clear = (int)$row['id'];
		}

        $nazwa_clear = mysqli_real_escape_string($mysqli, $nazwa);

        $query = "INSERT INTO sklep_kategorie (matka, nazwa) VALUES ('$matka_clear', '$nazwa_clear')";
        mysqli_query($mysqli, $query);

        // Przekierowanie po dodaniu kategorii
        header("Location: showcategory.php");
        exit();
    }

    // Wyświetlanie formularza
    echo '<form method="post" action="">
            <label for="nazwa">Nazwa:</label>
            <input type="text" name="nazwa" required>
            <label for="matka">Kategoria nadrzędna:</label>
            <input type="text" name="matka">
            <button type="submit">Dodaj kategorię</button>
          </form>';
}

if (isset($_GET['edit'])) {
    $editId = $_GET['edit'];
    EdytujKategorie($editId);
}
elseif (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    UsunKategorie($deleteId);
}
elseif (isset($_GET['add'])) {
    DodajKategorie();
	}
else{
	PokazKategorie();
}

?>
