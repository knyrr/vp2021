<?php
	session_start();
	//sisselogimise kontroll
	if(!isset($_SESSION["user_id"])){
		header("Location: page.php");
	}
	//väljalogimise kontroll	
	if(isset($_GET["logout"])){
			session_destroy();
	header("Location: page.php");
	}
	
	//proovin klassi
	//require_once("classes/Test.class.php");
	//$test_object = new Test(27);
	//echo $test_object->secret_number;
	//echo " Avalik number on: " .$test_object->public_number;
	//$test_object->reveal();
	//unset($test_object);
	
	require_once("page_header.php");
	
?>

	<h1><?php echo $_SESSION["user_name"]; ?>, veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli digitehnoloogiate instituudis</a>.</p>
	<p>Õppetöö toimus 2021. aasta sügisel.</p>
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
    </ul>
	<hr>
</body>
</html>