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
				//$_SESSION["bg_color"] = "#AAAAAA"; //valge #FFFFFF
				//$_SESSION["text_color"] = "#0000AA"; //must #000000
				$stmt->close();
				
				//kasutajaprofiili kontrollimine
				$stmt = $conn->prepare("SELECT userid, description, bgcolor, txtcolor FROM vpr_userprofiles WHERE userid = ?");
				$stmt->bind_param("i", $_SESSION["user_id"]);
				$stmt->bind_result($userid_from_db, $description_from_db, $bgcolor_from_db, $txtcolor_from_db);				
				echo $conn->error;
				$stmt->execute();
				if ($stmt->fetch()){	
					if(empty($bgcolor_from_db)){
						$_SESSION["bg_color"] = "#FFFFFF";			
					} else {
						$_SESSION["bg_color"] = $bgcolor_from_db;
					}
					if(empty($txtcolor_from_db)){
						$_SESSION["text_color"] = "#000000";			
					} else {
						$_SESSION["text_color"] = $txtcolor_from_db;
					}
					if(empty($description_from_db)){
						$_SESSION["description"] = null;			
					} else {
						$_SESSION["description"] = $description_from_db;
					}
				} 
				else {
					$_SESSION["bg_color"] = "#FFFFFF";
					$_SESSION["text_color"] = "#000000";						
				}
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
	
	//kasutajaprofiili salvestamine
	function store_profile($description, $bg_color, $text_color){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		//kontrollin, kas kasutaja on olemas
		$stmt = $conn->prepare("SELECT userid FROM vpr_userprofiles WHERE userid = ?");
		$stmt->bind_param("i", $_SESSION["user_id"]);
		$stmt->bind_result($userid_from_db);
		echo $conn->error;
		$stmt->execute();		
		if ($stmt->fetch()){
			$stmt->close();
			//SQL: UPDATE vpr_userprofiles SET description = ? bgcolor = ? txtcolor = ? WHERE userid = ?
			$stmt = $conn->prepare("UPDATE vpr_userprofiles SET description = ?, bgcolor = ?, txtcolor = ? WHERE userid = ?");
			$stmt->bind_param("sssi", $description, $bg_color, $text_color, $userid_from_db);
			echo $conn->error;
			if($stmt->execute()){
				$notice = "Andmete uuendamine õnnestus";
			} else {
				$notice = "Andmete uuendamisel tekkis viga:" .$stmt->error;
			}
			$stmt->execute();
		} else {
			$stmt->close();
			//SQL: insert into vpr_userprofiles (userid, description, bgcolor, txtcolor) values("XX"), "XX", "XX")
			$stmt = $conn->prepare("insert into vpr_userprofiles (userid, description, bgcolor, txtcolor) values(?,?,?,?)");
			//seon SQL käsuga pärisandmed
			// i integer d decimal s strig
			$stmt->bind_param("ssss", $_SESSION["user_id"], $description, $bg_color, $text_color);
			echo $conn->error;
			//käsu täitmine
			if($stmt->execute()){
				$notice = "Salvestamine õnnestus";
			} else {
				$notice = "Salvestamisel tekkis viga:" .$stmt->error;
			}
		} 
		$stmt->close();
		$conn->close();
		return $notice;
	}