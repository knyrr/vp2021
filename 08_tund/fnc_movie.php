<?php
	$database = "if21_martin_ry";

	
    function read_all_persons_for_option($selected){
        $options_html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT * FROM person");
        echo $conn->error;
        $stmt->bind_result($id_from_db, $first_name_from_db, $last_name_from_db, $birth_date_from_db);
        $stmt->execute();
        //<option value="x" selected>Eesnimi Perekonnanimi (sünniaeg)</option>
        while($stmt->fetch()){
            $options_html .= '<option value="' .$id_from_db .'"';
            if($id_from_db == $selected){
                $options_html .= " selected";
            }
            $options_html .= ">" .$first_name_from_db ." " .$last_name_from_db ." (" .$birth_date_from_db .")</option> \n";
        }
        $stmt->close();
        $conn->close();
        return $options_html;
    }
    
    function read_all_movies_for_option($selected){
        $options_html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        //<option value="x" selected>Film</option>
        $stmt = $conn->prepare("SELECT id, title, production_year FROM movie");
        $stmt->bind_result($id_from_db, $title_from_db, $production_year_from_db);
        $stmt->execute();
        while($stmt->fetch()){
           $options_html .= '<option value="' .$id_from_db .'"'; 
           if($selected == $id_from_db){
                $options_html .= " selected";
            }
            $options_html .= ">" .$title_from_db ." (" .$production_year_from_db .")</option> \n";
        }
        $stmt->close();
        $conn->close();
        return $options_html;
    }
    
    function read_all_positions_for_option($selected){
        $options_html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        //<option value="x" selected>Film</option>
        $stmt = $conn->prepare("SELECT id, position_name FROM position");
        $stmt->bind_result($id_from_db, $position_name_from_db);
        $stmt->execute();
        while($stmt->fetch()){
           $options_html .= '<option value="' .$id_from_db .'"'; 
           if($selected == $id_from_db){
                $options_html .= " selected";
            }
            $options_html .= ">" .$position_name_from_db ."</option> \n";
        }
        $stmt->close();
        $conn->close();
        return $options_html;
    }
    
    function read_all_genres_for_option($selected){
        $options_html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        //<option value="x" selected>Film</option>
        $stmt = $conn->prepare("SELECT id, genre_name FROM genre");
        $stmt->bind_result($id_from_db, $genre_name_from_db);
        $stmt->execute();
        while($stmt->fetch()){
           $options_html .= '<option value="' .$id_from_db .'"'; 
           if($selected == $id_from_db){
                $options_html .= " selected";
            }
            $options_html .= ">" .$genre_name_from_db ."</option> \n";
        }
        $stmt->close();
        $conn->close();
        return $options_html;
    }	
	
    function store_person_in_movie($selected_person, $selected_movie, $selected_position, $role){
        $notice = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        //<option value="x" selected>Film</option>
        $stmt = $conn->prepare("SELECT id FROM person_in_movie WHERE person_id = ? AND movie_id = ? AND position_id = ? AND role = ?");
        $stmt->bind_param("iiis", $selected_person, $selected_movie, $selected_position, $role);
        $stmt->bind_result($id_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            //selline on olemas
            $notice = "Selline seos on juba olemas!";
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO person_in_movie (person_id, movie_id, position_id, role) VALUES (?, ?, ?, ?)"); 
            $stmt->bind_param("iiis", $selected_person, $selected_movie, $selected_position, $role);
            if($stmt->execute()){
                $notice = "Uus seos edukalt salvestatud!";
            } else {
                $notice = "Uue seose salvestamisle tekkis viga: " .$stmt->error;
            }
        }
        $stmt->close();
        $conn->close();
        return $notice;
    }
 
    function store_genre_for_movie($selected_movie_for_genre, $selected_genre){
        $notice = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT id FROM movie_genre WHERE movie_id = ? AND genre_id = ?");
		echo $conn->error;
        $stmt->bind_param("ii", $selected_movie_for_genre, $selected_genre);
        $stmt->bind_result($id_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            //selline on olemas
            $notice = "Selline seos on juba olemas!";
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO movie_genre (movie_id, genre_id) VALUES (?, ?)"); 
			echo $conn->error;
            $stmt->bind_param("ii", $selected_movie_for_genre, $selected_genre);
            if($stmt->execute()){
                $notice = "Uus seos edukalt salvestatud!";
            } else {
                $notice = "Uue seose salvestamisle tekkis viga: " .$stmt->error;
            }
        }
        $stmt->close();
        $conn->close();
        return $notice;
    }
 
	function store_person_photo($file_name, $person_id) {
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("insert into picture (picture_file_name, person_id) values(?,?)");
		echo $conn->error;
		$stmt->bind_param("si", $file_name, $person_id);
		if($stmt->execute()){
			$notice = "Uus foto edukalt salvestatud";
		} else {
			$notice = "Uue foto andmebaasi salvestamisel tekkis viga:" .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;		
	}
	
	function store_movie($title_input, $year_input, $duration_input, $description_input){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id FROM movie WHERE title = ? AND production_year = ?");
		echo $conn->error;
        $stmt->bind_param("si", $title_input, $year_input);
        $stmt->bind_result($id_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $notice = "Selline film on juba olemas!";
        } else {
            $stmt->close();
			$stmt = $conn->prepare("insert into movie (title, production_year, duration, description) values(?,?,?,?)");
			echo $conn->error;
			$stmt->bind_param("siis", $title_input, $year_input, $duration_input, $description_input);
			if($stmt->execute()){
				$notice = "Filmi salvestamine õnnestus";
			} else {
				$notice = "Filmi salvestamisel tekkis viga:" .$stmt->error;
			}
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function store_person($first_name, $last_name, $birth_date){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id FROM person WHERE first_name = ? AND last_name = ? and birth_date = ?");
		echo $conn->error;
        $stmt->bind_param("sss", $first_name, $last_name, $birth_date);
        $stmt->bind_result($id_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $notice = "See isik  on juba andmebaasis olemas!";
        } else {
            $stmt->close();
			$stmt = $conn->prepare("insert into person (first_name, last_name, birth_date) values(?,?,?)");
			echo $conn->error;
			$stmt->bind_param("sss", $first_name, $last_name, $birth_date);
			if($stmt->execute()){
				$notice = "Isiku salvestamine õnnestus";
			} else {
				$notice = "Isiku salvestamisel tekkis viga:" .$stmt->error;
			}
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function read_actors_in_films(){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT concat(first_name, ' ', last_name) as full_name, birth_date, role, title, production_year, duration FROM person JOIN person_in_movie ON person.id =  person_in_movie.person_id JOIN movie ON movie.id = person_in_movie.movie_id where person_in_movie.role is not null order by last_name, first_name;");
		echo $conn->error;
		$stmt->bind_result($name_from_db, $birth_date_from_db, $role_from_db, $title_from_db, $production_year_from_db, $duration_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$notice .= "\n <table> \n <tr> \n <th>Nimi</th> \n <th>Sünniaeg</th> \n <th>Roll</th> \n <th>Film</th> \n <th>Valmimisaasta</th> \n <th>Pikkus</th> \n</tr> \n";
			while($stmt->fetch()){	
				$notice .= "<td>" .$name_from_db ."</td>";			
				$notice .= "<td>" .format_date_est($birth_date_from_db) ."</td>";
				$notice .= "<td>" .$role_from_db ."</td>";
				$notice .= "<td>" .$title_from_db ."</td>";
				$notice .= "<td>" .$production_year_from_db ."</td>";
				$notice .= "<td>" .convert_to_hours_mins($duration_from_db) ."</td> \n </tr> \n";				
			}
			$notice .= "</table>";
		}
		$stmt->close();
		$conn->close();
		return	$notice;	
	}
	
    //--------- Vana osa ---------------------------------------------
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