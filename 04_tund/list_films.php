<?php
	require_once ("../../../config.php");
	require_once ("fnc_film.php");
	//echo $server_host;
	$author_name = "Martin Rünk";
	$film_html = null;
	$film_html = read_all_films();
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
	<?php echo $film_html; ?>
	
</body>
</html>