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
            echo ' <a href="?add=' . $row['id'] . '">Dodaj Kategorie</a>';
        }
		else{
			echo ' <a href="?add_product=' . $row['id'] . '">Dodaj Produkt</a>';
		}
		
        $mysqli_produkt = new mysqli("localhost", "root", "", "moja_strona");

        $query_produkt = "SELECT * FROM lista_produkt WHERE kategoria='$row[id]'";
        $result_produkt = mysqli_query($mysqli_produkt, $query_produkt);

        echo '<ul>';
        while ($row_produkt = mysqli_fetch_array($result_produkt)) {
            echo '<li>' . htmlspecialchars($row_produkt['tytul']) .' '. ' <a href="?edit_product=' . $row_produkt['id'] . '">Edytuj</a>' . ' <a href="?delete_product=' . $row_produkt['id'] . '">Usuń</a>' . '</li>';
        }
        echo '</ul>';

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
//funkcja usuwajaca kategorie sklepu
function UsunKategorie($id)
{
    $mysqli = new mysqli("localhost", "root", "", "moja_strona");

    if (isset($_GET['delete'])) {
        $deleteId = $_GET['delete'];
		
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


function EdytujProdukt($id)
{
    $mysqli = new mysqli("localhost", "root", "", "moja_strona");

    if (isset($_GET['edit_product'])) {
        $editId = $_GET['edit_product'];
        $query = "SELECT * FROM lista_produkt WHERE id='$editId' LIMIT 1";
        $result = mysqli_query($mysqli, $query);
        $row = mysqli_fetch_array($result);

		if ($row) {
        echo '<form method="post" action="">
                <input type="hidden" name="edit_id" value="' . $row['id'] . '">
				
                <label for="tytul">Nowa nazwa:</label>
                <input type="text" name="tytul" value="' . htmlspecialchars($row['tytul']) . '" required><br/><br/>
				
                <label for="opis">Opis:</label>
                <input type="text" name="opis" value="' . htmlspecialchars($row['opis']) . '" required><br/><br/>
				
                <label for="data_utworzenia">Data Utworzenia:</label>
                <input type="date" name="data_utworzenia" value="' . htmlspecialchars($row['data_utworzenia']) . '" required><br/><br/>
				
                <label for="data_modyfikacji">Data Modyfikacji:</label>
                <input type="date" name="data_modyfikacji" value="' . htmlspecialchars($row['data_modyfikacji']) . '" required><br/><br/>
				
                <label for="data_wygasniecia">Data Wygaśnięcia:</label>
                <input type="date" name="data_wygasniecia" value="' . htmlspecialchars($row['data_wygasniecia']) . '" required><br/><br/>
				
                <label for="cena_netto">Cena Netto:</label>
                <input type="float" name="cena_netto" value="' . $row['cena_netto'] . '" required><br/><br/>
				
                <label for="podatek_VAT">Podatek VAT:</label>
                <input type="int" name="podatek_VAT" value="' . $row['podatek_VAT'] . '" required><br/><br/>
				
                <label for="ilosc_magazynowa">Ilość Magazynowa:</label>
                <input type="int" name="ilosc_magazynowa" value="' . $row['ilosc_magazynowa'] . '" required><br/><br/>
				
                <label for="status_dostepnosci">Status Dostępności:</label>
                <input type="int" name="status_dostepnosci" value="' . $row['status_dostepnosci'] . '" required><br/><br/>
				
                <label for="kategoria">Kategoria:</label>
                <input type="text" name="kategoria" value="' . htmlspecialchars($row['kategoria']) . '" required><br/><br/>
				
                <label for="gabaryt_produktu">Gabaryt Produktu:</label>
                <input type="int" name="gabaryt_produktu" value="' . $row['gabaryt_produktu'] . '" required><br/><br/>
				
                <button type="submit">Zapisz zmiany</button>
              </form>';
		} else {
            echo "Błąd: Nie znaleziono produktu o ID '$editId'";
		  }
    }
	 if (isset($_POST['edit_id'])) {
        $editId = $_POST['edit_id'];
        $tytul = $_POST['tytul'];
        $opis = $_POST['opis'];
        $data_utworzenia = $_POST['data_utworzenia'];
        $data_modyfikacji = $_POST['data_modyfikacji'];
        $data_wygasniecia = $_POST['data_wygasniecia'];
        $cena_netto = $_POST['cena_netto'];
        $podatek_VAT = $_POST['podatek_VAT'];
        $ilosc_magazynowa = $_POST['ilosc_magazynowa'];
        $status_dostepnosci = $_POST['status_dostepnosci'];
        $kategoria = $_POST['kategoria'];
        $gabaryt_produktu = $_POST['gabaryt_produktu'];

        $query = "UPDATE lista_produkt SET
                    tytul='$tytul',
                    opis='$opis',
                    data_utworzenia='$data_utworzenia',
                    data_modyfikacji='$data_modyfikacji',
                    data_wygasniecia='$data_wygasniecia',
                    cena_netto='$cena_netto',
                    podatek_VAT='$podatek_VAT',
                    ilosc_magazynowa='$ilosc_magazynowa',
                    status_dostepnosci='$status_dostepnosci',
                    kategoria='$kategoria',
                    gabaryt_produktu='$gabaryt_produktu'
                  WHERE id='$editId'";

        mysqli_query($mysqli, $query);
	
	// Przekierowanie po dodaniu kategorii
    header("Location: showcategory.php");
    exit();
	}
}

function UsunProdukt($id)
{
    $mysqli = new mysqli("localhost", "root", "", "moja_strona");

    if (isset($_GET['delete_product'])) {
        $deleteId = $_GET['delete_product'];

        $deleteQuery = "DELETE FROM lista_produkt WHERE id='$deleteId'";
        mysqli_query($mysqli, $deleteQuery);
        $mysqli->close();

        // Przekierowanie po usunięciu produktu
        header("Location: showcategory.php");
        exit();
    }
}

function DodajProdukt()
{
    $mysqli = new mysqli("localhost", "root", "", "moja_strona");

    if (isset($_POST['tytul'])) {
        $tytul = $_POST['tytul'];
        $opis = $_POST['opis'];
        $data_utworzenia = $_POST['data_utworzenia'];
        $data_modyfikacji = $_POST['data_modyfikacji'];
        $data_wygasniecia = $_POST['data_wygasniecia'];
        $cena_netto = $_POST['cena_netto'];
        $podatek_VAT = $_POST['podatek_VAT'];
        $ilosc_magazynowa = $_POST['ilosc_magazynowa'];
        $status_dostepnosci = $_POST['status_dostepnosci'];
        $kategoria = $_POST['kategoria'];
        $gabaryt_produktu = $_POST['gabaryt_produktu'];

		$query = "SELECT id FROM sklep_kategorie WHERE nazwa = '$kategoria'";
        $result = mysqli_query($mysqli, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            $kategoria_clear = (int)$row['id'];
        }
		
        $tytul_clear = mysqli_real_escape_string($mysqli, $tytul);

        $query = "INSERT INTO lista_produkt (
            tytul, opis, data_utworzenia, data_modyfikacji, data_wygasniecia,
            cena_netto, podatek_VAT, ilosc_magazynowa, status_dostepnosci, kategoria, gabaryt_produktu
        ) VALUES (
            '$tytul_clear', '$opis', '$data_utworzenia', '$data_modyfikacji', '$data_wygasniecia',
            '$cena_netto', '$podatek_VAT', '$ilosc_magazynowa', '$status_dostepnosci', '$kategoria_clear', '$gabaryt_produktu'
        )";
        mysqli_query($mysqli, $query);

        // Przekierowanie po dodaniu kategorii
        header("Location: showcategory.php");
        exit();
    }

    // Wyświetlanie formularza
    echo '<form method="post" action="">
            <label for="tytul">Nazwa:</label>
            <input type="text" name="tytul" required><br/><br/>
			
			<label for="opis">Opis:</label>
            <input type="text" name="opis" required><br/><br/>
			
			<label for="data_utworzenia">Data Utworzenia:</label>
            <input type="date" name="data_utworzenia" required><br/><br/>
			
			<label for="data_modyfikacji">Data Modyfikacji:</label>
            <input type="date" name="data_modyfikacji" required><br/><br/>
			
			<label for="data_wygasniecia">Data Wygasniecia:</label>
            <input type="date" name="data_wygasniecia" required><br/><br/>
			
			<label for="cena_netto">Cena Netto:</label>
            <input type="float" name="cena_netto" required><br/><br/>
			
			<label for="podatek_VAT">Podatek VAT:</label>
            <input type="int" name="podatek_VAT" required><br/><br/>
			
			<label for="ilosc_magazynowa">Ilosc Magazynowa:</label>
            <input type="int" name="ilosc_magazynowa" required><br/><br/>
			
			<label for="status_dostepnosci">Status Dostepnosci:</label>
            <input type="int" name="status_dostepnosci" required><br/><br/>
			
            <label for="kategoria">Kategoria:</label>
            <input type="text" name="kategoria"><br/><br/>
			
			<label for="gabaryt_produktu">Gabaryt Produktu:</label>
            <input type="int" name="gabaryt_produktu" required><br/><br/>
			
            <button type="submit">Dodaj produkt</button>
          </form>';
}


if (isset($_GET['edit'])) {
    $editKat = $_GET['edit'];
    EdytujKategorie($editKat);
}
elseif (isset($_GET['edit_product'])) {
    $editProd = $_GET['edit_product'];
    EdytujProdukt($editProd);
}
elseif (isset($_GET['delete'])) {
    $deleteKat = $_GET['delete'];
    UsunKategorie($deleteKat);
}
elseif (isset($_GET['delete_product'])) {
    $deleteProd = $_GET['delete_product'];
    UsunProdukt($deleteProd);
}
elseif (isset($_GET['add'])) {
    DodajKategorie();
	}
elseif (isset($_GET['add_product'])) {
    DodajProdukt();
	}
else{
	PokazKategorie();
}

?>
<div style="item-align: top; position: fixed; bottom : 1px; right: 1px; padding: 10px;">
<a href="lab1.php">STRONA GŁÓWNA</a>
<a href="./admin/admin.php">ADMIN</a>
</div>