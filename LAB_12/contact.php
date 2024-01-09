<body style="background-color: grey;>
<?php
echo '<link rel="stylesheet" href="./css/style.css">';

function PokazKontakt() {
    $wynik = '<div>
			<h1>KONTAKT:</h1><br>
			<form action="" method="post"
			<label for="temat">Temat:</label>
			<input type="text" name="temat" id="temat" required><br>
			<label for="email">Email:</label>
			<input type="text" name="email" id="email" required><br>
			<label for="tresc">Treść:</label>
			<input type="text" name="tresc" id="tresc" required><br>
			<input type="submit" name="wyslijwiadomosc" value="Wyślij">
		</form>
		</div>';
	return $wynik;
}

function WyslijMailKontakt($odbiorca) {
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
        echo '</br>Nie wszystkie pola zostały wypełnione';
        echo PokazKontakt();
    } else {
        $mail['subject'] = $_POST['temat'];
        $mail['body'] = $_POST['tresc'];
        $mail['sender'] = $_POST['email'];
        $mail['recipient'] = $odbiorca;

        $header = "From: Formularz kontaktowy <" . $mail['sender'] . ">\n";
        $header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding: \n";
        $header .= "X-Sender: <" . $mail['sender'] . ">\n";
        $header .= "X-Mailer: PRapWWW mail 1.2\n";
        $header .= "X-Priority: 3\n";
        $header .= "Return-Path: <" . $mail['sender'] . ">\n";

        mail($mail['recipient'], $mail['subject'], $mail['body'], $header);

        echo 'Wiadomość została wysłana';
    }
}

function PokazPrzypomnijHaslo() {
	echo '<h1>Przypomnij haslo:</h1><br>';
    $wynik = '<form action="" method="post">
			<label for="email_pass">Email:</label>
			<input type="email_pass" name="email_pass" required>
			<input type="submit" name="wyslijhaslo" value="Przypomnij hasło">
        </form>';
	return $wynik;
}

function PrzypomnijHaslo($odbiorca) {

    if (empty($_POST['email_pass'])) {
        echo '</br>Nie podano adresu email';
        echo PokazPrzypomnijHaslo();
    } else {
		$mail['subject'] = "Wiadomość o przypomnienie hasła";
        $mail['body'] = "Przypomnienie hasła";
        $mail['sender'] = $_POST['email_pass'];
        $mail['reciptient'] = $odbiorca;

        $header = "From: Formularz kontaktowy <" . $mail['sender'] . ">\n";
        $header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding: \n";
        $header .= "X-Sender: <" . $mail['sender'] . ">\n";
        $header .= "X-Mailer: PRapWWW mail 1.2\n";
        $header .= "X-Priority: 3\n";
        $header .= "Return-Path: <" . $mail['sender'] . ">\n";

        mail($mail['reciptient'], $mail['subject'], $mail['body'], $header);

        echo 'Wiadomość o przypomnienie hasła została wysłana';
		echo '<a href="lab1.php">Strona Główna</a>';
	}
}


if (isset($_POST['wyslijhaslo'])) {
    PrzypomnijHaslo('164367@student.uwm.edu.pl');
} elseif (isset($_POST['wyslijwiadomosc'])) {
    WyslijMailKontakt('164367@student.uwm.edu.pl');
} else {
    echo PokazKontakt();
    echo PokazPrzypomnijHaslo();
}

?>
</body>
<div style="item-align: top; position: fixed; bottom : 1px; right: 10px; padding: 10px;">
<a href="lab1.php">STRONA GŁÓWNA</a>
</div>