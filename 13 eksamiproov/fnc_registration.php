<?php
	$database = "if21_martin_ry";
	
	function register_for_party($firstname, $lastname, $studentcode){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id FROM vpr_party WHERE studentcode = ? and cancelled is null");
		$stmt->bind_param("i", $studentcode);
		$stmt->bind_result($studentcode_from_db);
		echo $conn->error;
		$stmt->execute();		
		if($stmt->fetch()){
			$notice = "Selle üliõpilaskoodiga on peol osalemine kinnitatud";
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO vpr_party (firstname, lastname, studentcode) VALUES (?,?,?)");
			echo $conn->error;
			$stmt->bind_param("ssi", $firstname, $lastname, $studentcode);
			if($stmt->execute()){
				$notice = "Peole registreerumine õnnestus";
			} else {
				$notice = "Peole registreerumisel tekkis viga: " .$stmt->error ;
			}			
		}	
		$stmt->close();
		$conn->close();
		return $notice;
	}		
		
	function test_input($data) {
		$data = htmlspecialchars($data);
		$data = stripslashes($data);
		$data = trim($data);
		return $data;
	}
	
	function read_all_registered_people(){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT firstname, lastname, studentcode, payment FROM vpr_party where cancelled is null order by lastname, firstname");
		echo $conn->error;
		$stmt->bind_result($firstname_from_db, $lastname_from_db, $studentcode_from_db, $payment_from_db);
		$stmt->execute();
		while ($stmt->fetch()){
			$notice .= "<p>" .$firstname_from_db ." " .$lastname_from_db ." (" .$studentcode_from_db .") - ";
			if($payment_from_db==1){
				$notice .= "osalustasu on makstud";
			} else {
				$notice .= "osalustasu on MAKSMATA";
			}
			$notice .= "</p>";
		}
		if(empty($notice)){
			$notice = "Keegi ei ole veel osalemist kinnitanud.";
		}
		$stmt->close();
		$conn->close();
		return $notice;	
	}
	
 	function read_all_unpaid_people($selected){
        $notice = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        //<option value="x" selected>Film</option>
        $stmt = $conn->prepare("SELECT id, firstname, lastname FROM vpr_party where cancelled is null and payment is null order by lastname, firstname");
        $stmt->bind_result($id_from_db, $firstname_from_db, $lastname_from_db);
        $stmt->execute();
        while($stmt->fetch()){
           $notice .= '<option value="' .$id_from_db .'"'; 
           if($selected == $id_from_db){
                $notice .= " selected";
            }
            $notice .= ">" .$firstname_from_db ." " .$lastname_from_db ."</option> \n";
        }
        $stmt->close();
        $conn->close();
        return $notice;
    } 
	
	function mark_as_paid($id, $payment_status = 1){
        $notice = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
		$stmt = $conn->prepare("UPDATE vpr_party SET payment = ? WHERE id = ?");
		echo $conn->error;
		$stmt->bind_param("ii", $payment_status, $id); 
        if($stmt->execute()){
			$notice = "Makse andmebaasi lisamine õnnestus";
		} else {
			$notice = "Makse andmebaasi lisamisel tekkis viga: " .$stmt->error ;	
		}
        $stmt->close();
        $conn->close();
        return $notice;
    }
	
	function count_all_registred_people(){
		$notice = null;
		$count = 0;
		//$payment_status = 1;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id FROM vpr_party where cancelled is null");
		echo $conn->error;
		$stmt->bind_result($id_from_db);
		$stmt->execute();
		while($stmt->fetch()){	
			$count = $count + 1;				
		}
		$notice = $count;
		$stmt->close();
		$conn->close();
		return $notice;			
	}
	
	function count_all_confirmed_people(){
		$notice = null;
		$count = 0;
		$payment_status = 1;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id FROM vpr_party where cancelled is null and payment = ?");
		$stmt->bind_param("i", $payment_status); 		
		echo $conn->error;
		$stmt->bind_result($id_from_db);
		$stmt->execute();
		while($stmt->fetch()){	
			$count = $count + 1;				
		}
		$notice = $count;
		$stmt->close();
		$conn->close();
		return $notice;			
	}
	
	function cancel_registration($studentcode){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id FROM vpr_party WHERE studentcode = ?");
		$stmt->bind_param("i", $studentcode);
		$stmt->bind_result($studentcode_from_db);
		echo $conn->error;
		$stmt->execute();		
		if($stmt->fetch()){
			$stmt->close();
			$stmt = $conn->prepare("UPDATE vpr_party SET cancelled = NOW() WHERE studentcode = ?");
			echo $conn->error;
			$stmt->bind_param("i", $studentcode);
			if($stmt->execute()){
				$notice = "Registreering on tühistatud";
			} else {
				$notice = "Registreeringu tühistamisel tekkis tõrge: " .$stmt->error ;
			}			
		} else {
			$notice = "Selle üliõpilaskoodiga registreering puudub"; 
		}	
		$stmt->close();
		$conn->close();
		return $notice;
	}