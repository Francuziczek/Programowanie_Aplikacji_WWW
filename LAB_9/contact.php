<body style="background-color: grey;>
<!-- Kod formularzy kontaktowych dla użytkownika -->
<?php
echo '<link rel="stylesheet" href="./css/style.css">';
function PokazKontakt()
    {
        echo '
            <h1>Kontakt</h1>
            <form action="contact.php?action=wyslij_mail" method="post">
                <p>Temat:</p>
                <input type="text" name="temat"><br>
                <p>Tresc:</p>
                <textarea name="tresc" rows="4" cols="25"></textarea><br>
                <p>Email:</p>
                <input type="email" name="email"><br>
                <input type="submit" value="Wyślij">
            </form>';
    }

function WyslijMailKontakt($odbiorca)
    {
        if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
            echo '</br>[Nie wszystkie pola zostaly wypelnione]';
            echo PokazKontakt();
        } else {
            $mail['subject'] = $_POST['temat'];
            $mail['body'] = $_POST['tresc'];
            $mail['sender'] = $_POST['email'];
            $mail['reciptient'] = $odbiorca;

            $header = "From: Formularz kontaktowy <" . $mail['sender'] . ">\n";
            $header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding: \n";
            $header .= "X-Sender: <" . $mail['sender'] . ">\n";
            $header .= "X-Mailer: PRapWWW mail 1.2\n";
            $header .= "X-Priority: 3\n";
            $header .= "Return-Path: <" . $mail['sender'] . ">\n";

            mail($mail['reciptient'], $mail['subject'], $mail['body'], $header);

            echo 'Wiadomość została wysłana';
        }
    }


if (isset($_GET['action']) && $_GET['action'] == 'wyslij_mail') {
    $odbiorca = 'domel2002@o2.pl';
    WyslijMailKontakt($odbiorca);
} else {
    PokazKontakt();
}
?>
</body>