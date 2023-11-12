<!DOCTYPE html>
<html>
<head>
	<title>Moje hobby to rolnictwo</title>
	<script src="js/kolorujtlo.js" type = "text/javascript"></script>
	<script src="js/timedate.js" type = "text/javascript"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<link rel="stylesheet" href="css/style.css">
	<style>
	body{
		background-image: url(./obrazy/tlo_glowne.jpg);
		background-size: cover;
        background-repeat: no-repeat;
	}
	</style>
</head>

<body onload="startclock()">﻿
	
	<na>
		<a href="lab1.php">Strona Główna</a>
		<a href="?idp=podstrona1">Ciagniki</a>
		<a href="?idp=podstrona2">Kombajny</a>
		<a href="?idp=podstrona3">Maszyny</a>
		<a href="?idp=podstrona4">Zwierzeta</a>
		<a href="?idp=podstrona5">Uprawy</a>
		<a href="?idp=podstrona6">Filmy</a>
		
	</na>
	
<?php
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	/* po tym komentarzu będzie kod do dynamicznego ładowania stron */
	if($_GET['idp'] == '' && file_exists('html/glowna.html'))$strona = 'html/glowna.html';
	if($_GET['idp'] == 'podstrona1' && file_exists('html/Ciagniki.html'))$strona = 'html/Ciagniki.html';
	if($_GET['idp'] == 'podstrona2' && file_exists('html/Kombajny.html'))$strona = 'html/Kombajny.html';
	if($_GET['idp'] == 'podstrona3' && file_exists('html/Maszyny.html'))$strona = 'html/Maszyny.html';
	if($_GET['idp'] == 'podstrona4' && file_exists('html/Zwierzeta.html'))$strona = 'html/Zwierzeta.html';
	if($_GET['idp'] == 'podstrona5' && file_exists('html/Uprawy.html'))$strona = 'html/Uprawy.html';
	if($_GET['idp'] == 'podstrona6' && file_exists('html/Filmy.html')) $strona = 'html/Filmy.html';
	
	include($strona);
?>
<p style="text-align: center; padding: 10px;">
<?php
    $nr_indeksu = '164367';
    $nrGrupy = '2 ISI';
	echo 'Dominik Franczak '.$nr_indeksu.' grupa '.$nrGrupy.'.  Strona wykonana na bazie informacji i zdjęć z internetu';
?>
</p>
</body>
</html>


