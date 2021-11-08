<?php
	$database = "if21_martin_ry";
	

	
    function show_latest_public_photo(){
		$photo_html = null;
		$privacy = 3;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT filename, alttext FROM vpr_photos WHERE id = (SELECT MAX(id) from vpr_photos WHERE privacy = ? AND deleted IS NULL)");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filename_from_db, $alttext_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			//<img src="kataloog/filename" alt="alttekst"> 
			$photo_html = '<img src="' .$GLOBALS["photo_normal_upload_dir"] .$filename_from_db .'" alt="';
			if(empty($alttext_from_db)){
				$photo_html .= "Üleslaaditud foto";
			} else {
				$photo_html .= $alttext_from_db;
			}
			$photo_html .= '">' ."\n";
		} else {
			$photo_html = "<p>Kahjuks avalikke fotosid üles laaditud pole.</p>";
		}
		$stmt->close();
		$conn->close();
		return $photo_html;
	}
	
	function read_public_photo_thumbs($privacy, $page, $limit){
		$photo_html = null;
		$skip = ($page - 1) * $limit;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		//$stmt = $conn->prepare("SELECT filename, alttext FROM vpr_photos WHERE privacy >= ? AND deleted IS NULL");
		//$stmt = $conn->prepare("SELECT filename, alttext FROM vpr_photos WHERE privacy >= ? AND deleted IS NULL ORDER BY id DESC");
		$stmt = $conn->prepare("SELECT userid, filename, created, alttext FROM vpr_photos WHERE privacy >= ? AND deleted IS NULL ORDER BY id DESC LIMIT ?, ?");		
		echo $conn->error;
		$stmt->bind_param("iii", $privacy, $skip, $limit);
		$stmt->bind_result($userid_from_db, $filename_from_db, $created_from_db, $alttext_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			//<div>
			//<img src="kataloog/filename" alt="alttekst">
			//...
			//</div>
			$photo_html .=	'<div class="thumbgallery">' ."\n";		
			$photo_html .= '<img src="' .$GLOBALS["photo_thumbnail_upload_dir"] .$filename_from_db .'" alt="';
			if(empty($alttext_from_db)){
				$photo_html .= "Üleslaaditud foto";
			} else {
				$photo_html .= $alttext_from_db;
			}
			$photo_html .= '" class="thumbs">' ."\n";
			$photo_html .= '<p>Loodud: ' .format_date_est_no($created_from_db) .'</p>';
			$photo_html .= '<p>Üleslaadija: ' .$userid_from_db .read_photo_uploader($userid_from_db) .'</p>';
			$photo_html .=	"</div> \n";	
		}
		if(empty($photo_html)){
			$photo_html = "<p>Kahjuks avalikke fotosid üles laaditud pole.</p>";
		}
		$stmt->close();
		$conn->close();
		return $photo_html;
	}
	
	function read_own_photo_thumbs($page, $limit){
		$photo_html = null;
		$skip = ($page - 1) * $limit;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, filename, alttext FROM vpr_photos WHERE userid = ? AND deleted IS NULL ORDER BY id DESC LIMIT ?, ?");		
		echo $conn->error;
		$stmt->bind_param("iii", $_SESSION["user_id"], $skip, $limit);
		$stmt->bind_result($id_from_db, $filename_from_db, $alttext_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			//<div>
			//<a href="edit_own_photo.php?page=x">
			//<img src="kataloog/filename" alt="alttekst">
			//</a>
			//</div>
			//UPDATE vpr_photo SET deleted = NOW() WHERE id = ? //kustutamine
			$photo_html .= '<div class="thumbgallery">' ."\n";		
			$photo_html .= '<a href="edit_gallery_photo.php?page=' .$id_from_db .'">';
			$photo_html .= '<img src="' .$GLOBALS["photo_thumbnail_upload_dir"] .$filename_from_db .'" alt="';
			if(empty($alttext_from_db)){
				$photo_html .= "Üleslaaditud foto";
			} else {
				$photo_html .= $alttext_from_db;
			}
			$photo_html .= '" class="thumbs">' ."\n";
			$photo_html .=	"</a>";	
			$photo_html .=	"</div> \n";	
		}
		if(empty($photo_html)){
			$photo_html = "<p>Kahjuks avalikke fotosid üles laaditud pole.</p>";
		}
		$stmt->close();
		$conn->close();
		return $photo_html;
	}
	
	function count_public_photos($privacy){
		$photo_count = 0;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT COUNT(id) FROM vpr_photos WHERE privacy >= ? AND deleted IS NULL");		
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($count_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$photo_count = $count_from_db;
		}
		$stmt->close();
		$conn->close();
		return $photo_count;		
		
	}
	
	function count_own_photos(){
		$photo_count = 0;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT COUNT(id) FROM vpr_photos WHERE userid = ? AND deleted IS NULL");	
		echo $conn->error;
		$stmt->bind_param("i", $_SESSION["user_id"]);
		$stmt->bind_result($count_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$photo_count = $count_from_db;
		}
		$stmt->close();
		$conn->close();
		return $photo_count;		
		
	}
	
	function show_own_photo($id_from_get){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, filename, alttext, privacy, deleted FROM vpr_photos WHERE id = ? AND userid = ? AND deleted IS NULL");	
		echo $conn->error;
		$stmt->bind_param("ii", $id_from_get, $_SESSION["user_id"]);
		$stmt->bind_result($id_from_db, $filename_from_db, $alttext_from_db, $privacy_from_db, $deleted_date_from_db);		
		$stmt->execute();
		if($stmt->fetch()){
			if($id_from_get == $id_from_db){
				$notice = '<img src="' .$GLOBALS["photo_normal_upload_dir"] .$filename_from_db .'" alt="' .$alttext_from_db .'">' ."\n" ;
				$notice .= '<form method="POST" action="';
				//$notice .= 'htmlspecialchars($_SERVER["PHP_SELF"])';
				$notice .= '" enctype="multipart/form-data">' ."\n";
				$notice .= '<label for="alt_input">Alternatiivtekst:</label>' ."\n";
				$notice .= '<input type="text" name="alt_input" id="alt_input" placeholder="pildi alternatiivtekst" value="';
				$notice .= $alttext_from_db .'" >' ."\n";
				$notice .= "<br> \n";
				
				$notice .= '<input type="radio" name="privacy_input" id="privacy_input_1" value="1"';
				if($privacy_from_db == 1){
					$notice .= ' checked';
				}
				$notice .= '>' ."\n";
				$notice .= '<label for="privacy_input_1">Privaatne (ainult mina näen)</label>' ."\n";
				$notice .= "<br> \n";
				$notice .= '<input type="radio" name="privacy_input" id="privacy_input_2" value="2"';
				if($privacy_from_db == 2){
					$notice .= ' checked';
				}
				$notice .= '>' ."\n";				
				$notice .= '<label for="privacy_input_2">Sisseloginud kasutajale</label>' ."\n";	
				$notice .= "<br> \n";
				$notice .= '<input type="radio" name="privacy_input" id="privacy_input_3" value="3"';
				if($privacy_from_db == 3){
					$notice .= ' checked';
				}
				$notice .= '>' ."\n";
				$notice .= '<label for="privacy_input_3">Avalik (kõik näevad)</label>' ."\n";
				$notice .= "<br> \n";
				$notice .= '<input type="submit" name="photo_update_submit" value="Uuenda pildi andmeid">';
				$notice .= "<br> \n";				
				$notice .= '<input type="submit" name="photo_delete_submit" value="Kustuta">';

			} else {
				$notice = "<p>Soovitud foto andmeid muuta ei saa.</p>";
			}
		} else {
			$notice = "Viga";
		}
		$stmt->close();
		$conn->close();
		return $notice;		
		
	}
	
	function update_own_photo_data($id_from_get, $alttext, $privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT alttext, privacy FROM vpr_photos WHERE id = ? AND deleted IS NULL");	
		echo $conn->error;
		$stmt->bind_param("i", $id_from_get);
		$stmt->bind_result($alttext_from_db, $privacy_from_db);		
		$stmt->execute();		
		$stmt->close();
		if($alttext != $alttext_from_db){
			$stmt = $conn->prepare("update vpr_photos set alttext = ? WHERE id = ?");
			echo $conn->error;
			$stmt->bind_param("si", $alttext, $id_from_get);
			if($stmt->execute()){
				$notice .= "Alternatiivtekst uuendatud.";	
			}
			$stmt->close();
		}
		if($privacy != $privacy_from_db){
			$stmt = $conn->prepare("update vpr_photos set privacy = ? WHERE id = ?");
			echo $conn->error;
			$stmt->bind_param("ii", $privacy, $id_from_get);
			if($stmt->execute()){
				$notice .= "Privaatsustase uuendatud.";	
			}
			$stmt->close();
		}
		$conn->close();
		return $notice;	
	
	}
	
	function delete_photo($id_from_get){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");		
		$stmt = $conn->prepare("update vpr_photos set deleted = now() WHERE id = ?");
		echo $conn->error;
		$stmt->bind_param("i", $id_from_get);
		if($stmt->execute()){
			$notice .= "Foto on kustutatud.";	
		}
		$stmt->close();		
		$conn->close();
		return $notice;	

	}
	
