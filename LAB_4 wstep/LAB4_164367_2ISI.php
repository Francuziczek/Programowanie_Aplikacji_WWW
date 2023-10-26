<?php
    $nr_indeksu = '164367';
    $nrGrupy = '2ISI';

echo 'Dominik Franczak' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br/>';


echo '<br/>a) Metoda include(), require_once() :  <br/>';

include("include.php");

echo 'Include -->'.$tekst. '<br/>';

define('_ROOT_', dirname(__FILE__));
require_once(_ROOT_.'/require_once.php');
echo 'Require_once -->'.$tekst2. '<br/>';

echo '<br/>b) Warunki if, else, elseif, switch :  <br/>';

$temp = -1;

if ($temp > 0) {
    echo 'Temperatura powyzej zera';
} elseif ($temp < 0) {
    echo 'Temperatura ponizej zera';
} else {
    echo 'Temperatura to zero';
}
echo '<br/> <br/>Wywolanie switcha:<br/>';

$cyfra = 2;

switch($cyfra)
{
    case '0':
        echo 'zero';
        break;
    case '1':
        echo 'jeden';
        break;
    case '2':
        echo 'dwa';
        break;
    case '3':
        echo 'trzy';
        break;
    case '4':
        echo 'cztery';
        break;
    case '5':
        echo 'piec';
        break;
    case '6':
        echo 'szesc';
        break;
    case '7':
        echo 'siedem';
        break;
    case '8':
        echo 'osiem';
        break;
    case '9':
        echo 'dziwiec';
        break;
    default:
        echo'TO NIE CYFRA!';
}

echo '<br/><br/>c) Pętla while() i for() : <br/><br/>';
echo 'Wywolanie while:<br/>';

$liczba = 1;
while($liczba <= 10) {
    echo "$liczba, ";
    $liczba++;
}

echo '<br/><br/>Wywolanie fora:<br/>';

for ($i = 1; $i <= 10; $i++) {
    echo "$i, ";
}

echo '<br/><br/>d) Typy zmiennych $_GET, $_POST, $_SESSION <br/>';
echo 'Wywolanie $_GET:<br/>';
$_GET["name"] = 'Dominik';
echo 'Hello ' . htmlspecialchars($_GET["name"]) . '!';

echo '<br/><br/>Wywolanie $_POST:<br/>';
$_POST["name"] = 'Dominik';
echo 'Hello ' . htmlspecialchars($_POST["name"]) . '!';

echo '<br/><br/>Wywolanie $_SESSION:<br/>';
session_start();
$_SESSION["name"] = "Dominik";
echo "Wartosc session ustawiona.";
?>