<?php
	require_once ("../../../config.php");
	//echo $server_host;
	$author_name = "Martin Rünk";
	$database = "if21_martin_ry";
	//loome andmebaasiühenduse mysqli server,kasutaja, parool, andmebaas
	$conn = new mysqli($server_host, $server_user_name, $server_password, $database); //$conn on klassi mysqli uus objekt, conn=connection
	$conn->set_charset("utf8");
	//valmistan ette SQL päringu: select * from film
	$stmt = $conn->prepare("select * from film"); //stms=statement
	echo $conn->error;
	//seon tulemused muutujatega
	$stmt->bind_result($title_from_db,$year_from_db, $duration_from_db, $genre_from_db, $studio_from_db, $director_from_db); //järjekord on oluline
	//täidan käsu
	$film_html = null;
	$stmt->execute();
	//võtan kirjeid kuni jätkub - tsükkel
	while($stmt->fetch()){
		//<h3>Filmi nimi</h3>
		//<ul>
		//<li>Valmimisaasta: 1976</li>
		//<li>...</li>
		//</ul>
		$film_html .= "\n <h3>" .$title_from_db ."</h3> \n";
		$film_html .= "\n <ul> \n";
		$film_html .= "\n <li> Valmimisaasta: " .$year_from_db ."</li> \n";
		$film_html .= "\n <li> Kestus: " .$duration_from_db ." min</li> \n";
		$film_html .= "\n <li> Žanr: " .$genre_from_db ."</li> \n";
		$film_html .= "\n <li> Tootja: " .$studio_from_db ."</li> \n";
		$film_html .= "\n <li> Lavastaja: " .$director_from_db ."</li> \n";
		$film_html .= "</ul> \n";
	}
	//sulgen käsu
	$stmt->close();
	//sulgen andmebaasiühenduse
	$conn->close();
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $author_name; ?>, veebiprogrammeerimine</title>
</head>
<body>
	<h1><?php echo $author_name; ?>, veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli digitehnoloogiate instituudis</a>.</p>
	<p>Õppetöö toimus 2021. aasta sügisel.</p>
	<hr>
	<h2>Eesti filmid</h2>
	<?php echo $film_html ?>
	
</body>
</html>