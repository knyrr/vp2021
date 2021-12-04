<?php
	require_once("use_session.php");
		
	require_once ("../../../config.php");
	require_once ("fnc_film.php");
	//echo $server_host;
	$film_html = null;
	$film_html = read_all_films();
	
	require_once("page_header.php");
?>
	<h1><?php echo $_SESSION["user_name"]; ?>, veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli digitehnoloogiate instituudis</a>.</p>
	<p>Õppetöö toimus 2021. aasta sügisel.</p>
	<hr>
	<ul>
	    <li><a href="?logout=1">Logi välja</a></li>
		<li><a href="home.php">Avaleht</a></li>
	</ul>
	<hr>
	<h2>Eesti filmid</h2>
	<?php echo $film_html; ?>
	
</body>
</html>