<!DOCTYPE html>
<html>
<head>
	<title>Moje hobby to rolnictwo</title>
	<script src="js/kolorujtlo.js" type = "text/javascript"></script>
	<script src="js/timedate.js" type = "text/javascript"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<link rel="stylesheet" href="./css/style.css" />
	<style>
	body{
		background-image: url(./obrazy/tlo_glowne.jpg);
		background-size: cover;
        background-repeat: no-repeat;
	}
	</style>
</head>

<body onload="startclock()">﻿

	<div class="head">
		<a href="lab1.php">Strona Główna</a>
		<a href="?idp=2">Ciagniki</a>
		<a href="?idp=3">Kombajny</a>
		<a href="?idp=4">Maszyny</a>
		<a href="?idp=5">Zwierzeta</a>
		<a href="?idp=6">Uprawy</a>
		<a href="?idp=7">Filmy</a>
	</div>
	<div style="item-align: top; position: fixed; bottom : 1px; right: 1px; padding: 10px;">
		<a href="./admin/admin.php">ADMIN</a>
	</div>

<?php
	include 'cfg.php';
	include 'showpage.php';
        if (isset($_GET['idp'])) {
            echo PokazPodstrone($_GET['idp']);
        } else {
			echo PokazPodstrone(1);
		}   
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