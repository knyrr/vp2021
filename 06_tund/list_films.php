<?php
	session_start();
	$author_name = $_SESSION["user_name"];
	//sisselogimise kontroll
	if(!isset($_SESSION["user_id"])){
		header("Location: page.php");
	}
	//väljalogimise kontroll	
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
	}
		
	require_once ("../../../config.php");
	require_once ("fnc_film.php");
	//echo $server_host;
	$film_html = null;
	$film_html = read_all_films();
	
	require_once("page_header.php");
?>
	<p><a href="?logout=1">Logi välja</a></p>
	<h1><?php echo $author_name; ?>, veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli digitehnoloogiate instituudis</a>.</p>
	<p>Õppetöö toimus 2021. aasta sügisel.</p>
	<hr>
	<h2>Eesti filmid</h2>
	<?php echo $film_html; ?>
	
</body>
</html>