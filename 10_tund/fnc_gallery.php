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
			$photo_html .= '<p>Lisatud: ' .format_date_est_no($created_from_db) .'</p>';
			$photo_html .=	"</div> \n";	
		}
		if(empty($photo_html)){
			$photo_html = "<p>Kahjuks avalikke fotosid üles laaditud pole.</p>";
		}
		$stmt->close();
		$conn->close();
		return $photo_html;
	}
	
    function read_own_photo_thumbs($page_limit, $page){
        $gallery_html = null;
        $skip = ($page - 1) * $page_limit;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT id, filename, created, alttext FROM vpr_photos WHERE userid = ? AND deleted IS NULL ORDER BY id DESC LIMIT ?,?");
        echo $conn->error;
        $stmt->bind_param("iii", $_SESSION["user_id"], $skip, $page_limit);
        $stmt->bind_result($id_from_db, $filename_from_db, $created_from_db, $alttext_from_db);
        $stmt->execute();
        while($stmt->fetch()){
            //<div class="thumbgallery">
            //<img src="kataloog.file" alt="tekst">
            //</div>
            $gallery_html .= '<div class="thumbgallery">' ."\n";
            $gallery_html .= '<a href="edit_gallery_photo.php?photo=' .$id_from_db .'">';
            $gallery_html .= '<img src="' .$GLOBALS["photo_thumbnail_upload_dir"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $gallery_html .= "Üleslaetud foto";
            } else {
                $gallery_html .= $alttext_from_db;
            }
            $gallery_html .= '" class="thumbs">' ."\n";
            $gallery_html .= "</a> \n";
			$gallery_html .= "<p>Lisatud: " .format_date_est_no($created_from_db) ."</p> \n";
            $gallery_html .= "</div> \n";
        }
        if(empty($gallery_html)){
            $photo_html = "<p>Kahjuks avalikke fotosid üles laetud pole!</p> \n";
        }
        
        $stmt->close();
		$conn->close();
		return $gallery_html;
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
	
	function read_own_photo($photo){
		$photo_data = [];
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT filename, alttext, privacy FROM vpr_photos WHERE id = ? AND userid = ? AND deleted IS NULL");
		echo $conn->error;
        $stmt->bind_param("ii", $photo, $_SESSION["user_id"]);
        $stmt->bind_result($filename_from_db, $alttext_from_db, $privacy_from_db);
		$stmt->execute();
		if($stmt->fetch()){
            array_push($photo_data, true);
			array_push($photo_data, $filename_from_db);
			array_push($photo_data, $alttext_from_db);
			array_push($photo_data, $privacy_from_db);
        } else {
			array_push($photo_data, false);
		}
		$stmt->close();
		$conn->close();
		return $photo_data;
	}
	
	function photo_data_update($photo, $alttext, $privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("UPDATE vpr_photos SET alttext = ?, privacy = ? WHERE id = ? AND userid = ?");
		echo $conn->error;
        $stmt->bind_param("siii", $alttext, $privacy, $photo, $_SESSION["user_id"]);
        if($stmt->execute()){
			$notice = "Andmete muutmine õnnestus!";
		} else {
			$notice = "Andmete muutmisel tekkis tõrge!";
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	//viga: vaikimisi kirjutab altteksti ja privaatsuse millegipärast üle. tuleks panna siia sisse, et õiged alles jääksid
	function delete_photo($photo){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("UPDATE vpr_photos SET deleted = NOW() WHERE id = ? AND userid = ?");
		echo $conn->error;
        $stmt->bind_param("ii", $photo, $_SESSION["user_id"]);
        if($stmt->execute()){
			$notice = "ok";
		} else {
			$notice = "Foto kustutamisel tekkis tõrge!";
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}