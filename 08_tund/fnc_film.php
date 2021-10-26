<?php
	$database = "if21_martin_ry";

	function read_all_films(){
		//var_dump ($GLOBALS);
		//loome andmebaasiühenduse mysqli server,kasutaja, parool, andmebaas
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]); //$conn on klassi mysqli uus objekt, conn=connection
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
		return	$film_html;	
	}
	
	function store_film($title_input,$year_input,$duration_input,$genre_input,$studio_input,$director_input){
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		//SQL: insert into film (pealkiri, aast, kestus, zanr, tootja, lavastaja) values("SUVI"), 1976, 83, "Tallinnfilm", "Arvo Kruusement")
		$stmt = $conn->prepare("insert into film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) values(?,?,?,?,?,?)");
		echo $conn->error;
		//seon SQL käsuga pärisandmed
		// i integer d decimal s strig
		$stmt->bind_param("siisss", $title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input);
		//käsu täitmine
		$success = null;
		if($stmt->execute()){
			$success = "Salvestamine õnnestus";
		} else {
			$success = "Salvestamisel tekkis viga:" .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $success;
	}