<?php
	$database = "if21_martin_ry";

	function store_news($title, $content, $date, $photoid=null){
		$notice = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
		$stmt = $conn->prepare("insert into vpr_news (userid, title, content, expire, photoid) values(?,?,?,?,?)");
		echo $conn->error;
		$stmt->bind_param("isssi", $_SESSION["user_id"],$title, $content, $date, $photoid);
		if($stmt->execute()){
			$notice = "Uudis edukalt salvestatud";
		} else {
			$notice = "Uudise andmebaasi salvestamisel tekkis viga:" .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;						
	}
	
	function show_news(){
		$html = null;
		$date = new DateTime("now");
		$current_date = date_format($date, "Y-m-d");
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT news.userid, news.title, news.content, news.added, news.photoid, users.firstname, users.lastname, photos.filename FROM vpr_news as news JOIN vpr_users as users on users.id=news.userid LEFT JOIN vpr_newsphotos as photos ON photos.id = news.photoid  WHERE news.expire > ?");
		$stmt->bind_param("s", $current_date);
        $stmt->bind_result($userid_from_db, $title_from_db, $content_from_db, $added_from_db, $photoid_from_db, $firstname_from_db, $lastname_from_db, $filename_from_db);
        $stmt->execute();
        while($stmt->fetch()){
		//<h3>Uudise pealkiri</h3>
		//<p>Nimi, lisamise aeg</p>
		//<p>Uudise sisu</p> Uudise sisu on nagunii html-elementidena (vajab seda htmlspecialchars_decode() kodeerimist).
		//<img src="XX" alt="Uudis"> Kui uudisega on sisestatud foto, siis tuleb ka seda näidata.
			$html .= "<h3>" .htmlspecialchars_decode($title_from_db) ."</h3> \n";
			$html .= "<p>" .$firstname_from_db ." " .$lastname_from_db .", " .format_date_est_no($added_from_db)."</p> \n";
			$html .= "<p>" .htmlspecialchars_decode($content_from_db) ."</p> \n";
			if(!empty($photoid_from_db)){
				$html .= '<img src="' .$GLOBALS["photo_thumbnail_upload_dir"] .$filename_from_db .'">' ."\n"; 	
			}			
		}	
		$stmt->close();
		$conn->close();
		return $html;	
	}