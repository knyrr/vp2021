<?php
	require_once("use_session.php");
	require_once ("../../../config.php");
	require_once ("fnc_movie.php");
	require_once ("fnc_general.php");

	//filmi muutujad
	$title_input = null;
	$year_input = date("Y");
	$duration_input = 60;
	$description_input = null;
	$film_store_notice = null;
	$film_store_notice_title = null;
	$film_store_notice_year = null;
	$film_store_notice_duration = null;
	$film_store_notice_description = null;
	
	//isiku muutujad
    $birth_month = null;
    $birth_year = null;
    $birth_day = null;
    $birth_date = null;
	$first_name = null;
	$last_name = null;

	$month_names_et = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];
	$person_store_notice_first_name = null;
	$person_store_notice_last_name = null;
	$person_store_notice_birth_date= null;
	$person_store_notice_birth_day = null;
	$person_store_notice_birth_month = null;
	$person_store_notice_birth_year = null;
	$person_store_notice = null;
	
	//filmiteabe esitamine
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
        if(!empty($_POST["description_input"])){
			$description_input = test_input(filter_var($_POST['description_input'], FILTER_SANITIZE_STRING));
		}   
		if (empty($film_store_notice_title) and empty($film_store_notice_year) and empty($film_store_notice_duration)){
			$film_store_notice = store_movie($title_input, $year_input, $duration_input, $description_input);
		} else {
			$film_store_notice = "Ebaõnnestus";
		}			
	}

	//isikuteabe esitamine
    if(isset($_POST["person_submit"])){
        if(!empty($_POST["first_name_input"])){
			$first_name = test_input(filter_var($_POST['first_name_input'], FILTER_SANITIZE_STRING));
		} else {
			$person_store_notice_first_name = "Sisesta eesnimi!";
		}
		if(!empty($_POST["last_name_input"])){
			$last_name = test_input(filter_var($_POST['last_name_input'], FILTER_SANITIZE_STRING));
		} else {
			$person_store_notice_last_name = "Sisesta perekonnanimi!";
		}

		//SÜNNIKUUPÄEVA OSA
		//PÄEV
		if(isset($_POST["birth_day_input"]) and !empty($_POST["birth_day_input"])){
			$birth_day = filter_var($_POST["birth_day_input"], FILTER_VALIDATE_INT);
			if($birth_day < 1 and $birth_day > 31){
				$person_store_notice_birth_day = "Palun vali sünnipäev!";
			}
		} else {
			$person_store_notice_birth_day = "Palun vali sünnipäev!";
		}			
		//KUU
		if(isset($_POST["birth_month_input"]) and !empty($_POST["birth_month_input"])){
			$birth_month = filter_var($_POST["birth_month_input"], FILTER_VALIDATE_INT);
			if($birth_month < 1 and $birth_month > 12){
				$person_store_notice_birth_month = "Palun vali sünnikuu!";
			}
		} else {
			$person_store_notice_birth_month = "Palun vali sünnikuu!";
		}
		//AASTA
		if(isset($_POST["birth_year_input"]) and !empty($_POST["birth_year_input"])){
			$birth_year = filter_var($_POST["birth_year_input"], FILTER_VALIDATE_INT);
			if($birth_year < date("Y") - 150 and $birth_year > date("Y") - 0){
				$person_store_notice_birth_year = "Palun vali sünniaasta!";
			}
		} else {
			$person_store_notice_birth_year = "Palun vali sünniaasta!";
		}			
		//kontrollin kuupäeva õigust ja paneme kuupäeva kokku
		if(empty($person_store_notice_birth_day) and empty($person_store_notice_birth_month) and empty($person_store_notice_birth_year)){
			if(checkdate($birth_month, $birth_day, $birth_year)){ //true vs false
				$temp_date = new DateTime($birth_year ."-" .$birth_month ."-" .$birth_day);	//objekt-orienteeritud klass - siin palju lisainfot sees
				$birth_date = $temp_date->format("Y-m-d");
				//echo $birth_date;
			} else {
				$person_store_notice_birth_date= "Valitud kuupäev ei ole õige!";		
			}		
		}   
		
		if (empty($person_store_notice_first_name) and empty($person_store_notice_last_name) and empty($birth_date_error)){
			$person_store_notice = store_person($first_name, $last_name, $birth_date);
		} else {
			$person_store_notice = "Isiku lisamine ebaõnnestus";
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
	<h2>Filmi lisamine</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="title_input">Pealkiri: </label> 
		<input type="text" name="title_input" id="title_input" placeholder="pealkiri" value="<?php echo $title_input; ?>"><span><?php echo $film_store_notice_title; ?></span>
		<br>
		<label for="year_input">Valmimisaasta: </label>
		<input type="number" name="year_input" id="year_input" value="<?php echo $year_input;?>" min="1912"><span><?php echo $film_store_notice_year; ?></span>
		<br>
		<label for="duration_input">Kestus minutites: </label>
		<input type="number" name="duration_input" id="duration_input" value="<?php echo $duration_input; ?>" min="1"><span><?php echo $film_store_notice_duration; ?></span>
		<br>
		<label for="description_input">Lühikirjeldus: </label> 
		<textarea name="description_input" id="description_input" rows="3" cols="80" placeholder="kirjeldus"?><?php echo $description_input; ?></textarea>
		<br>
		<input type="submit" name="film_submit" value="Salvesta">
	</form>
	<span><?php echo $film_store_notice; ?></span>
	
		<h2>Isiku lisamine</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="first_name_input">Eesnimi: </label> 
		<input type="text" name="first_name_input" id="first_name_input" placeholder="eesnimi" value="<?php echo $first_name; ?>"><span><?php echo $person_store_notice_first_name; ?></span>
		<br>
		<label for="last_name_input">Perekonnanimi: </label> 
		<input type="text" name="last_name_input" id="last_name_input" placeholder="perekonnanimi" value="<?php echo $last_name; ?>"><span><?php echo $person_store_notice_last_name; ?></span>
		<br>
		<label for="birth_day_input">Sünnikuupäev: </label>
		  <?php
			//sünnikuupäev
			echo '<select name="birth_day_input" id="birth_day_input">' ."\n";
			echo "\t \t" .'<option value="" selected disabled>päev</option>' ."\n";
			for($i = 1; $i < 32; $i ++){
				echo "\t \t" .'<option value="' .$i .'"';
				if($i == $birth_day){
					echo " selected";
				}
				echo ">" .$i ."</option> \n";
			}
			echo "\t </select> \n";
		  ?>
		<label for="birth_month_input">Sünnikuu: </label>
		  <?php
			echo '<select name="birth_month_input" id="birth_month_input">' ."\n";
			echo "\t \t" .'<option value="" selected disabled>kuu</option>' ."\n";
			for ($i = 1; $i < 13; $i ++){
				echo "\t \t" .'<option value="' .$i .'"';
				if ($i == $birth_month){
					echo " selected ";
				}
				echo ">" .$month_names_et[$i - 1] ."</option> \n";
			}
			echo "</select> \n";
		  ?>
		  <label for="birth_year_input">Sünniaasta: </label>
		  <?php
			echo '<select name="birth_year_input" id="birth_year_input">' ."\n";
			echo "\t \t" .'<option value="" selected disabled>aasta</option>' ."\n";
			for ($i = date("Y") - 0; $i >= date("Y") - 150; $i --){
				echo "\t \t" .'<option value="' .$i .'"';
				if ($i == $birth_year){
					echo " selected ";
				}
				echo ">" .$i ."</option> \n";
			}
			echo "</select> \n";
		  ?>

		  <span><?php echo $person_store_notice_birth_date." " .$person_store_notice_birth_day ." " .$person_store_notice_birth_month ." " .$person_store_notice_birth_year; ?></span>
		  
		  <br>
	
		<input type="submit" name="person_submit" value="Salvesta">
	</form>
	<span><?php echo $person_store_notice; ?></span>
	
</body>
</html>