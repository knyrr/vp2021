<?php
	require_once ("../../../config.php");
	require_once ("fnc_film.php");
	//echo $server_host;
	$author_name = "Martin Rünk";
	$film_store_notice = null;
	$film_store_notice_title = null;
	$film_store_notice_year = null;
	$film_store_notice_duration = null;
	$film_store_notice_genre = null;
	$film_store_notice_studio = null;
	$film_store_notice_director = null;
	
	//kas klikit submit nupul
    if(isset($_POST["film_submit"])){
        if(!empty($_POST["title_input"]) and !empty($_POST["genre_input"]) and !empty($_POST["studio_input"]) and !empty($_POST["director_input"])){
			$title_input = trim(htmlspecialchars($_POST['title_input']));
			$year_input = $_POST['year_input'];
			$year_input = filter_var($year_input, FILTER_VALIDATE_INT);
			$duration_input = $_POST['duration_input'];
			$duration_input = filter_var($duration_input, FILTER_VALIDATE_INT);
			$genre_input = trim(htmlspecialchars($_POST['genre_input']));
			$studio_input = trim(htmlspecialchars($_POST['studio_input']));
			$director_input = trim(htmlspecialchars($_POST['director_input']));
			$film_store_notice = store_film($title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input);
        } else {                 
			if(empty($_POST["title_input"])){
				$film_store_notice_title = "Sisesta pealkiri!";
			}
			if(empty($_POST["genre_input"])){
				$film_store_notice_genre = "Sisesta žanr!";
			}
			if(empty($_POST["studio_input"])){
				$film_store_notice_studio = "Sisesta tootja!";
			}
			if(empty($_POST["director_input"])){
				$film_store_notice_director = "Sisesta lavastaja!";
			}
			
		}
    }
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
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="title_input">Filmi pealkiri: </label> 
		<input type="text" name="title_input" id="title_input" placeholder="pealkiri" value="<?php echo isset($_POST['title_input']) ? $_POST['title_input'] : '' ?>"><span><?php echo $film_store_notice_title; ?></span>
		<br>
		<label for="year_input">Valmimisaasta: </label>
		<input type="number" name="year_input" id="year_input" value="<?php echo date("Y");?>" min="1912"><span><?php echo $film_store_notice_year; ?></span>
		<br>
		<label for="duration_input">Kestus minutites: </label>
		<input type="number" name="duration_input" id="duration_input" value="60" min="1"><span><?php echo $film_store_notice_duration; ?></span>
		<br>
		<label for="genre_input">Filmi žanr: </label> 
		<input type="text" name="genre_input" id="genre_input" placeholder="žanr" value="<?php echo isset($_POST['genre_input']) ? $_POST['genre_input'] : '' ?>"><span><?php echo $film_store_notice_genre; ?></span>
		<br>
		<label for="studio_input">Filmi tootja: </label> 
		<input type="text" name="studio_input" id="studio_input" placeholder="tootja" value="<?php echo isset($_POST['studio_input']) ? $_POST['studio_input'] : '' ?>"><span><?php echo $film_store_notice_studio; ?></span>
		<br>
		<label for="director_input">Filmi lavastaja: </label> 
		<input type="text" name="director_input" id="director_input" placeholder="lavastaja" value="<?php echo isset($_POST['director_input']) ? $_POST['director_input'] : '' ?>"><span><?php echo $film_store_notice_director; ?></span>
		<br>
		<input type="submit" name="film_submit" value="Salvesta">
	</form>
	<span><?php echo $film_store_notice; ?></span>
	
</body>
</html>