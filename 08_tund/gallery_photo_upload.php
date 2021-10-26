<?php
	session_start();
	//sisselogimise kontroll
	if(!isset($_SESSION["user_id"])){
		header("Location: page.php");
	}
	//väljalogimise kontroll	
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
	}
	
	require_once ("../../../config.php");
	require_once ("fnc_photo_upload.php");
	require_once ("fnc_general.php");

	$photo_upload_notice = null;
	$photo_upload_orig_dir = "../upload_photos_orig/"; 
	$photo_upload_normal_dir = "../upload_photos_normal/"; 
	$photo_upload_thumb_dir = "../upload_photos_thumb/";
	$file_name_prefix = "vp_";
	$file_size_limit = 1024 * 1024; //=MB (kb*kb)
	$photo_max_width = 600;
	$photo_max_height = 400;
	$photo_size_ratio = 1;
	$watermark_file = "../pics/vp_logo_color_w100_overlay.png";
	$file_type = null;
	$file_name = null;
	$alt_text = null;
	$privacy = 1;
	
	if(isset($_POST["photo_submit"])){
		//var_dump($_FILES);
		if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
			$image_check = getimagesize($_FILES["photo_input"]["tmp_name"]);
			if($image_check !== false){
				if($image_check["mime"] == "image/jpeg"){
					$file_type = "jpg";
				}
				if($image_check["mime"] == "image/png"){
					$file_type = "png";
				}
				if($image_check["mime"] == "image/gif"){
					$file_type = "gif";
				}
			} else {
				$photo_upload_notice = "Valitud fail ei ole pilt";
			}
			//var_dump($image_check);
			//kas on lubatud suurusega
			if(empty($photo_upload_notice) and $_FILES["photo_input"]["size"] > $file_size_limit){
				$photo_upload_notice = "Valitud fail on liiga suur";
			}
			if(empty($photo_upload_notice)){
				//teen ajatempli
				$time_stamp = microtime(1) * 10000;
				//moodustan failinime
				$file_name = $file_name_prefix .$time_stamp ."." .$file_type;				
				//hakkan pildi suurust muutma
				//loon image objekti
				if($file_type == "jpg"){
				$my_temp_image = imagecreatefromjpeg($_FILES["photo_input"]["tmp_name"]);
				}
				if($file_type == "png"){
				$my_temp_image = imagecreatefrompng($_FILES["photo_input"]["tmp_name"]);
				}
				if($file_type == "gif"){
				$my_temp_image = imagecreatefromgif($_FILES["photo_input"]["tmp_name"]);
				}
				//foto originaalmõõdud
				$image_width = imagesx($my_temp_image);
				$image_height = imagesy($my_temp_image);
				if($image_width / $photo_max_width > $image_height / $photo_max_height){
					$photo_size_ratio = $image_width / $photo_max_width;
				} else {
					$photo_size_ratio = $image_height / $photo_max_height;
				}
				//uued mõõdud
				$image_new_width = round($image_width / $photo_size_ratio);
				$image_new_height = round($image_height / $photo_size_ratio);
				//loon muudetud mõõtudega image objekti
				$my_new_temp_image = imagecreatetruecolor($image_new_width, $image_new_height);
				//kopeerin vajalikud pikslik suurelt kujutiselt väiksele
				imagecopyresampled($my_new_temp_image, $my_temp_image, 0, 0, 0, 0, $image_new_width, $image_new_height, $image_width, $image_height);
				//lisan vesimärgi
				$watermark = imagecreatefrompng($watermark_file);
				$watermark_width = imagesx($watermark);
				$watermark_height = imagesy($watermark);
				$watermark_x = $image_new_width - $watermark_width - 10;
				$watermark_y = $image_new_height - $watermark_height - 10;
				imagecopy($my_new_temp_image, $watermark, $watermark_x, $watermark_y, 0, 0, $watermark_width, $watermark_height);
				imagedestroy($watermark);
				//salvestan
				$photo_upload_notice = save_image($my_new_temp_image, $file_type, $photo_upload_normal_dir .$file_name);
				imagedestroy($my_new_temp_image);
				//võin teha veel mingi suurusega variandi
				imagedestroy($my_new_temp_image);

				move_uploaded_file($_FILES["photo_input"]["tmp_name"], $photo_upload_orig_dir .$file_name);				
			}
		} else {
			$photo_upload_notice = "Pildifaili ei ole valitud";
		}
	} 
	
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
	
	<h2>Fotode üleslaadimine</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
			
		<label for="photo_input">Vali foto fail:</label>
		<input type="file" name="photo_input" id="photo_input">
		<br>
		<label for="alt_input">Alternatiivtekst (alt):</label>
		<input type="text" name="alt_input" id="alt_input" placeholder="pildi alternatiivtekst" value="<?php echo $alt_text; ?>">
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_1" value="1" <?php if($privacy == 1){echo " checked";} ?>>
		<label for="privacy_input_1">Privaatne (ainult mina näen)</label>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_2" value="2" <?php if($privacy == 2){echo " checked";} ?>>
		<label for="privacy_input_2">Sisseloginud kasutajale</label>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_3" value="3" <?php if($privacy == 3){echo " checked";} ?>>
		<label for="privacy_input_3">Avalik (kõik näevad)</label>
		<br>		
		<input type="submit" name="photo_submit" value="Laadi pilt üles">
	</form>		
	<span><?php echo $photo_upload_notice; ?></span>


</body>
</html>

