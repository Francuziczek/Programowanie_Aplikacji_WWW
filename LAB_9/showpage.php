<?php
/* Kod pokazywania podstrony po wyborze jej na stronie głównej */
	function PokazPodstrone($id) {
		$id_clear = htmlspecialchars($id);
		$mysqli = new mysqli("localhost", "root", "", "moja_strona");
		
		$query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
		$result = mysqli_query($mysqli, $query);
		$row = mysqli_fetch_array($result);
		
		if(empty($row['id'])) {
			$web = '[nie_znaleziono_strony]';
		}
		else {
			$web = $row['page_content'];
		}
		return $web;
	}
?>