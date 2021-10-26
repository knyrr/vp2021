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
	
	require_once ("../../../config.php");
	require_once ("fnc_film.php");
	require_once ("fnc_general.php");
	//echo $server_host;
	
	$title_input = null;
	$year_input = date("Y");
	$duration_input = 60;
	$genre_input = null;
	$studio_input = null;
	$director_input = null;
	$film_store_notice = null;
	$film_store_notice_title = null;
	$film_store_notice_year = null;
	$film_store_notice_duration = null;
	$film_store_notice_genre = null;
	$film_store_notice_studio = null;
	$film_store_notice_director = null;
	
	//kas klikit submit nupul
    if(isset($_POST["film_submit"])){
        if(!empty($_POST["title_input"])){
			$title_input = test_input(filter_var($_POST['title_input'], FILTER_SANITIZE_STRING));
		} else {
			$film_store_notice_title = "Sisesta pealkiri!";
		}
		if(!empty($_POST["year_input"])){
			$year_input = test_input(filter_var($_POST['year_input'], FILTER_VALIDATE_INT));
		} else {
			$film_store_notice_year = "Sisesta aasta!";
		}
		if(!empty($_POST["duration_input"])){
			$duration_input = test_input(filter_var($_POST['duration_input'], FILTER_VALIDATE_INT));
		} else {
			$film_store_notice_duration = "Sisesta kestus!";
		}
        if(!empty($_POST["genre_input"])){
			$genre_input = test_input(filter_var($_POST['genre_input'], FILTER_SANITIZE_STRING));
		} else {
			$film_store_notice_genre = "Sisesta žanr!";
		}		
        if(!empty($_POST["studio_input"])){
			$studio_input = test_input(filter_var($_POST['studio_input'], FILTER_SANITIZE_STRING));
		} else {
			$film_store_notice_studio = "Sisesta pealkiri!";
		}		
        if(!empty($_POST["director_input"])){
			$director_input = test_input(filter_var($_POST['director_input'], FILTER_SANITIZE_STRING));
		} else {
			$film_store_notice_director = "Sisesta lavastaja!";
		}		
		if (empty($film_store_notice_title) and empty($film_store_notice_year) and empty($film_store_notice_duration) and empty($film_store_notice_genre) and empty($film_store_notice_studio) and empty($film_store_notice_director)){
			$film_store_notice = store_film($title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input);
		} else {
			$film_store_notice = "Ebaõnnestus";
		}
			
	}
	
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
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="title_input">Filmi pealkiri: </label> 
		<input type="text" name="title_input" id="title_input" placeholder="pealkiri" value="<?php echo $title_input; ?>"><span><?php echo $film_store_notice_title; ?></span>
		<br>
		<label for="year_input">Valmimisaasta: </label>
		<input type="number" name="year_input" id="year_input" value="<?php echo date("Y");?>" min="1912"><span><?php echo $film_store_notice_year; ?></span>
		<br>
		<label for="duration_input">Kestus minutites: </label>
		<input type="number" name="duration_input" id="duration_input" value="60" min="1"><span><?php echo $film_store_notice_duration; ?></span>
		<br>
		<label for="genre_input">Filmi žanr: </label> 
		<input type="text" name="genre_input" id="genre_input" placeholder="žanr" value="<?php echo $genre_input;?>"><span><?php echo $film_store_notice_genre; ?></span>
		<br>
		<label for="studio_input">Filmi tootja: </label> 
		<input type="text" name="studio_input" id="studio_input" placeholder="tootja" value="<?php echo $studio_input; ?>"><span><?php echo $film_store_notice_studio; ?></span>
		<br>
		<label for="director_input">Filmi lavastaja: </label> 
		<input type="text" name="director_input" id="director_input" placeholder="lavastaja" value="<?php echo $director_input; ?>"><span><?php echo $film_store_notice_director; ?></span>
		<br>
		<input type="submit" name="film_submit" value="Salvesta">
	</form>
	<span><?php echo $film_store_notice; ?></span>
	
</body>
</html>