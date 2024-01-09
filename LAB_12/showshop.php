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
        echo '<li>' . ' ' . htmlspecialchars($row['nazwa']);

        $mysqli_produkt = new mysqli("localhost", "root", "", "moja_strona");

        $query_produkt = "SELECT * FROM lista_produkt WHERE kategoria='$row[id]'";
        $result_produkt = mysqli_query($mysqli_produkt, $query_produkt);

        echo '<ul>';
        while ($row_produkt = mysqli_fetch_array($result_produkt)) {
			if($row_produkt['ilosc_magazynowa'] >0){
				echo '<li>' . htmlspecialchars($row_produkt['tytul']) .' [ilosc = '. htmlspecialchars($row_produkt['ilosc_magazynowa']) .'->'.htmlspecialchars($row_produkt['cena_netto']). '/szt netto] <a href="?add=' . $row_produkt['id'] . '">Dodaj do koszyka</a>' . '</li>';
			}else{
				echo '<li>' . htmlspecialchars($row_produkt['tytul']) .' [ '.htmlspecialchars($row_produkt['cena_netto']) . '/szt netto]' . '  Przepraszamy, ten produkt jest obecnie niedostepny.' .'</li>';
			}
		}
        echo '</ul>';

        echo '</li>';

        PokazKategorie($row['id']);
    }
    echo '</ul>';
}

function DodajDoKoszyka($produktId) {
    session_start();

    $mysqli = new mysqli("localhost", "root", "", "moja_strona");

    // Sprawdzenie, czy produkt istnieje
    $querySprawdzProdukt = "SELECT * FROM lista_produkt WHERE id='$produktId'";
    $resultSprawdzProdukt = mysqli_query($mysqli, $querySprawdzProdukt);

    if ($resultSprawdzProdukt && mysqli_num_rows($resultSprawdzProdukt) > 0) {
        $rowProdukt = mysqli_fetch_assoc($resultSprawdzProdukt);
        $iloscMagazynowa = $rowProdukt['ilosc_magazynowa'];

        if ($iloscMagazynowa > 0) {
            // Sprawdzenie, czy produkt już istnieje w koszyku
            if (isset($_SESSION['koszyk'][$produktId])) {
                // Aktualizacja ilości w koszyku
                $_SESSION['koszyk'][$produktId]['ilosc'] += 1;
            } else {
                // Dodanie nowego produktu do koszyka
                $_SESSION['koszyk'][$produktId] = array(
                    'ilosc' => 1,
                    'cenaBrutto' => $rowProdukt['cena_netto'] + ($rowProdukt['cena_netto'] * $rowProdukt['podatek_VAT'])/100
                );
            }

            // Zaktualizowanie ilości dostępnej w bazie danych
            $nowaIloscMagazynowa = $iloscMagazynowa - 1;
            $queryAktualizujIlosc = "UPDATE lista_produkt SET ilosc_magazynowa='$nowaIloscMagazynowa' WHERE id='$produktId'";
            $resultAktualizujIlosc = mysqli_query($mysqli, $queryAktualizujIlosc);

        }
    } else {
        echo "Produkt nie istnieje.";
    }
    $mysqli->close();
}

function PokazKoszyk() {
	
	session_start();

    $mysqli = new mysqli("localhost", "root", "", "moja_strona");

    echo '<h2>Zawartość koszyka:</h2>';
	
    if (!isset($_SESSION['koszyk']) || empty($_SESSION['koszyk'])) {
        echo "Twój koszyk jest pusty.";
		return;
    }	
    echo '<ul>';
		$doZaplaty = 0;
    foreach ($_SESSION['koszyk'] as $produktId => $produkt) {
        // Pobranie informacji o produkcie
        $queryProdukt = "SELECT * FROM lista_produkt WHERE id='$produktId'";
        $resultProdukt = mysqli_query($mysqli, $queryProdukt);
		
        if ($resultProdukt && mysqli_num_rows($resultProdukt) > 0) {
            $rowProdukt = mysqli_fetch_assoc($resultProdukt);
            $ilosc = $produkt['ilosc'];
			$brutto = $produkt['cenaBrutto'];
			$calkowita =  $ilosc * $brutto;
			$doZaplaty += $calkowita; 
			
            echo '<li>' . ' ' . htmlspecialchars($rowProdukt['tytul']);
            echo ' [Ilość: ' . $ilosc . ']';
            echo ' Cena brutto: ' . $brutto;
            echo ' Cena calkowita: ' . $calkowita;
            echo ' <a href="?remove=' . $produktId . '">Usuń z koszyka</a>';
            echo '</li>';

        } else {
            echo "Blad podczas dodawania produktu do koszyka.";
        }
    }
	echo '<p>Calkowita suma do zaplaty: ' . $doZaplaty . ' zl</p>';
    echo '</ul>';
    $mysqli->close();

    echo '<p><a href="?clear">Wyczysc koszyk</a></p>';
}

function UsunZKoszyka($produktId) {
    // Inicjalizacja sesji
    session_start();

    // Połączenie z bazą danych
    $mysqli = new mysqli("localhost", "root", "", "moja_strona");

    // Jeżeli produkt istnieje w koszyku, usuń go
    if (isset($_SESSION['koszyk'][$produktId])) {
        // Pobranie informacji o produkcie
        $queryProdukt = "SELECT * FROM lista_produkt WHERE id='$produktId'";
        $resultProdukt = mysqli_query($mysqli, $queryProdukt);

        if ($resultProdukt && mysqli_num_rows($resultProdukt) > 0) {
            $rowProdukt = mysqli_fetch_assoc($resultProdukt);
            
            // Zwiększenie ilości dostępnej w sklepie
            $nowaIloscMagazynowa = $rowProdukt['ilosc_magazynowa'] + $_SESSION['koszyk'][$produktId]['ilosc'];
            $queryAktualizujIlosc = "UPDATE lista_produkt SET ilosc_magazynowa='$nowaIloscMagazynowa' WHERE id='$produktId'";
            $resultAktualizujIlosc = mysqli_query($mysqli, $queryAktualizujIlosc);
            // Usunięcie produktu z koszyka
            unset($_SESSION['koszyk'][$produktId]);
        } else {
            echo "Nie mozna usunac produktu.";
        }
    }
    $mysqli->close();
}

function WyczyscKoszyk() {
    session_start();
	
    // Pobierz wszystkie produkty z koszyka i usuń je
    foreach ($_SESSION['koszyk'] as $produktId => $produkt) {
        UsunZKoszyka($produktId);
    }
    unset($_SESSION['koszyk']);
}


PokazKoszyk();
if (isset($_GET['add'])) {
	$produktId = $_GET['add'];
    DodajDoKoszyka($produktId);
	header("Location: showshop.php");
}elseif (isset($_GET['remove'])) {
	$produktId = $_GET['remove'];
    UsunZKoszyka($produktId);
	header("Location: showshop.php");
}elseif (isset($_GET['clear'])) {
    WyczyscKoszyk();
	header("Location: showshop.php");
	}
else{
	PokazKategorie();
}

?>
<div style="item-align: top; position: fixed; bottom : 1px; right: 1px; padding: 10px;">
<a href="lab1.php">STRONA GŁÓWNA</a>
</div>