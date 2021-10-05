<?php
	$database = "if21_martin_ry";
	
	function sign_up($firstname, $surname, $email, $gender, $birth_date, $password){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		
		$stmt = $conn->prepare("SELECT id FROM vpr_users WHERE email = ?");
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id_from_db);
		echo $conn->error;
		$stmt->execute();		
		if ($stmt->fetch()){
			$notice = "Selle meiliaadressiga on juba kasutaja olemas";
		} else {
			//sulgen eelmise käsu!! aga conn jääb püsima
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO vpr_users (firstname, lastname, birthdate, gender, email, password) VALUES (?,?,?,?,?,?)");
			echo $conn->error;
			//krüpteerin salasõna
			$option = ["cost"=>12];//krüptimise kordade arv, maks 12
			$pwd_hash = password_hash($password, PASSWORD_BCRYPT, $option); //60 tähemärki
			$stmt->bind_param("sssiss", $firstname, $surname, $birth_date, $gender, $email, $pwd_hash);
			if($stmt->execute()){ //true vs false
				$notice = "Uus kasutaja edukalt loodud";
			} else {
				$notice = "Uue kasutaja loomisel tekkis viga: " .$stmt->error ;
			}
		}
		$stmt->close();
		$conn->close();
		return	$notice;			
	}
	
	function sign_in($email, $password){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, firstname, lastname, password FROM vpr_users WHERE email = ?");
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id_from_db, $firstname_from_db, $lastname_from_db, $password_from_db);
		echo $conn->error;
		$stmt->execute();		
		if ($stmt->fetch()){	
			//kasutaja on olemas, kontrollime parooli
			if(password_verify($password, $password_from_db)){
				//ongi õige
				$_SESSION["user_id"] = $id_from_db;
				$_SESSION["user_name"] = $firstname_from_db ." " .$lastname_from_db;
				$_SESSION["first_name"] = $firstname_from_db;
				$_SESSION["last_name"] = $lastname_from_db;				
				// siin edaspidi sisselogimisel pärime sql-iga kasutajaprofiili. kui see on olemas, siis loeme sealt tausta- ja tekstivärvid. muiud kaustame minegid vaikevärve
				$_SESSION["bg_color"] = "#AAAAAA"; //valge #FFFFFF
				$_SESSION["text_color"] = "#0000AA"; //must #000000
				$stmt->close();
				$conn->close();				
				header("Location: home.php");
				exit();
			} else {
				$notice = "Kasutajanimi või salasõna oli vale"; 
			}
		} else {
			$notice = "Kasutajanimi või salasõna oli vale";
		}
		$stmt->close();
		$conn->close();
		return	$notice;		
	}