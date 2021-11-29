<?php
	require_once("use_session.php");
	
	require_once ("../../../config.php");
	require_once ("fnc_photo_upload.php");
	require_once ("fnc_news.php");
	require_once ("fnc_general.php");
	require_once ("classes/Photoupload.class.php"); //võib olla ka sulgudeta

	$news_notice = null;
	$expire = new DateTime("now");
	$expire->add(new DateInterval("P7D")); //seitse päeva
	$expire_date = date_format($expire, "Y-m-d");

    $normal_photo_max_width = 600;
    $normal_photo_max_height = 400;
	$thumbnail_width = $thumbnail_height = 100;

	$photo_filename_prefix = "vprnews_";
	$photo_upload_size_limit = 1024 * 1024; //=MB (kb*kb)
	$allowed_photo_types = ["image/jpeg", "image/png", "image/gif"];
	
	$title_notice = null;
	$content_notice = null;
	$date_notice = null;
	$image_notice = null;

	$title_input = null;
	$content_input	= null;
	$date_input = $expire_date;
	$photo_error = null;
	$photo_id = null;
	
	if(isset($_POST["news_submit"])){
		
		if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
			//fail on, klass kontrollib kohe, kas on foto		
			$photo_upload = new Photoupload($_FILES["photo_input"]);
			if(empty($photo_upload->error)){
				//kas on lubatud tüüpi
				$photo_error .= $photo_upload->check_alowed_type($allowed_photo_types);
				if(empty($photo_upload->error)){
					//kas on lubatud suurusega
					$photo_error .= $photo_upload->check_size($photo_upload_size_limit);
					//kui seni vigu pole, laeme üles
					if(empty($photo_error)){
						//failinime
						$photo_upload->create_filename($photo_filename_prefix);
						//normaalmõõdus foto
						$photo_upload->resize_photo($normal_photo_max_width, $normal_photo_max_height);
						$photo_upload_notice = "Vähendatud pildi " .$photo_upload->save_image($photo_normal_upload_dir .$photo_upload->file_name);
						//teen pisipildi
						$photo_upload->resize_photo($thumbnail_width, $thumbnail_height);
						$photo_upload_notice .= " Pisipildi " .$photo_upload->save_image($photo_thumbnail_upload_dir .$photo_upload->file_name);
						//kopeerime pildi originaalkujul, originaalnimega vajalikku kataloogi
						$photo_upload_notice .= $photo_upload->move_original_photo($photo_orig_upload_dir .$photo_upload->file_name);
						//kirjutame andmetabelisse
						$photo_id = store_news_photo_data($photo_upload->file_name);
					}
				}
			}
			unset($photo_upload);
		}
        
        if(!empty($photo_id)){
			$image_notice = "Foto on lisatud";
			$news_notice .= "Foto on lisatud. ";
		}
		
		
		
		
		
		//uudisele võib aga ei pea lisama pilti. kui pilt on valitud, tasub see kõige esimesena serverisse salvestada ja andebaasilisada. just lisatud kirje id saab kätte 
		//$added_id = $conn->insert_id;
		//siis saate uudise enda koos foto idga salvestada; (uudiste andmetabel, uudise fotode andmtabel)
		//uudiste sise kontrollimisek kindlasti kasutada meie check intpu funtsiooni. seal on htmlspecialchars(uudis) mis kodeerib htmi märgid ringi
		//uudise näidamiseks on neid tagasi vaja. selleks htmlspecialchars_decode(uudis anmdebaasist)
		//näitamise võrrelda tänase päevaga aegumist
		//$today = date("Y-m-d");
		//sql lauses where expire >= ?
		
		//Uudise sisu kontrollimiseks kindlasti kasutada meie test_input funktsiooni (fnc_general.php).
        //seal on htmlspecialchars(uudis), mis kodeerib html märgid ringi (  < --> &lt;  )
        //uudise näitamisel on neid tagasi vaja, selleks htmlspecialchars_decode(uudis andmebaasist)
        //
		if(!empty($_POST["title_input"])){
			$title_input = test_input(filter_var($_POST['title_input'], FILTER_SANITIZE_STRING));
		} else {
			$title_notice = "Sisesta pealkiri!";
		}
		if(!empty($_POST["news_input"])){
			$content_input = test_input(filter_var($_POST['news_input'], FILTER_SANITIZE_STRING));
		} else {
			$content_notice = "Sisesta sisu!";
		}
		if(!empty($_POST["expire_input"])){
			$date_input = test_input(filter_var($_POST['expire_input'], FILTER_SANITIZE_STRING));
		} else {
			$date_notice = "Sisesta aegumiskuupäev!";
		}

		if (empty($notice_title) and empty($notice_content) and empty($notice_date) and empty($notice_image)){
			$news_notice .= store_news($title_input, $content_input, $date_input, $photo_id);
		} else {
			$news_notice .= "Uudise salvestamine õnnestus";
		}
		
	$title_input = null;
	$content_input	= null;
		
    }

	//$to_head = '<script src="javascript/checkFileSize.js" defer></script>' ."\n";
	$to_head = '<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>' ."\n";
  
	require_once ("page_header.php");
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
	
	<h2>Uudise lisamine</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		
		<label for="title_input">Uudise pealkiri:</label>
		<input type="text" name="title_input" id="title_input" placeholder="Uudise pealkiri" value="<?php echo $title_input; ?>"><span><?php echo $title_notice; ?></span>
		<br>
		
		<label for="news_input">Uudise sisu:</label>
		<textarea id="news_input" name="news_input"><?php echo $content_input;?></textarea>
		<script>CKEDITOR.replace('news_input');</script><span><?php echo $content_notice; ?></span>
		<br>
		
		<label for="expire_input">Uudise aegumiskuupäev:</label>
		<input type="date" id="expire_input" name="expire_input" value="<?php echo $expire_date;?>"><span>
		<br>
		
		<label for="photo_input">Vali foto fail:</label>
		<input type="file" name="photo_input" id="photo_input"><span><?php echo $image_notice; ?></span>
		<br>
				
		<input type="submit" name="news_submit" id="news_submit" value="Salvesta uudis">
	</form>		
	<span><?php echo $news_notice; ?></span>


</body>
</html>

