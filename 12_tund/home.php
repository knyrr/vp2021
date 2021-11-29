<?php
	require_once("use_session.php");
	
	require_once ("../../../config.php");
	require_once ("fnc_photo_upload.php");
	require_once ("fnc_news.php");
	require_once ("fnc_general.php");
	
	//proovin klassi
	//require_once("classes/Test.class.php");
	//$test_object = new Test(27);
	//echo $test_object->secret_number;
	//echo " Avalik number on: " .$test_object->public_number;
	//$test_object->reveal();
	//unset($test_object);
	
	//küpsiste näide: setcookie(name, value, expire, path, domain, secure, httponly);
	//86000 = 60 s * 60 min * 24 h ööpäev sekundites. time() on aeg sekundites
	setcookie("vpvisitor", $_SESSION["first_name"] ." " .$_SESSION["last_name"], time() + (86400 * 9), "/~marrun/vp2021/", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]), true);
	$last_visitor = null;
	if(isset($_COOKIE["vpvisitor"])){
		$last_visitor = "<p>Viimati külastas selles arvutis seda lehte " .$_COOKIE["vpvisitor"] .".</p> \n";
	} else {
		$last_visitor = "<p>Küpsiseid ei leitud. Viimane kasutaja pole teada. </p> \n";
	}
	//var_dump($_COOKIE);
	//küpsise muutmine on lihtsalt uue väärtusega üle kirjutamine
	//küpsise kustutamiseks kirjutatakse ta üle aegumistähtajaga, mis on minevikus
	//näiteks time() - 3600
	
	//$limit = 5;
	
	require_once("page_header.php");
	
?>

	<h1><?php echo $_SESSION["user_name"]; ?>, veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli digitehnoloogiate instituudis</a>.</p>
	<p>Õppetöö toimus 2021. aasta sügisel.</p>
	<hr>
	<?php echo $last_visitor; ?>
	<hr>
	<ul>
        <li><a href="?logout=1">Logi välja</a></li>
		<li><a href="list_films.php">Filmide nimekirja vaatamine</a> versioon 1</li>
		<li><a href="add_films.php">Filmide lisamine andmebaasi</a> versioon 1</li>
		<li><a href="user_profile.php">Kasutajaprofiil</a></li>
		<li><a href="movie_relations.php">Filmi info seoste loomine</a></li>
		<li><a href="add_movie_info.php">Filmiteabe lisamine andmebaasi</a> versioon 2</li>
		<li><a href="movie_info.php">Filmiteabe kuvamine</a></li>
		<li><a href="gallery_photo_upload.php">Fotode üleslaadimine</a></li>
		<li><a href="gallery_public.php">Sisseloginud kasutajatele nähtavate fotode galerii</a></li>
		<li><a href="gallery_own.php">Ainult mulle nähtavate fotode galerii</a></li>
		<li><a href="add_news.php">Uudise lisamine</a></li>
    </ul>
	<hr>
	<p><?php echo show_news(); ?></p>
	<hr>	
	
	
	
</body>
</html>