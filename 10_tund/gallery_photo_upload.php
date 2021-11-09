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
	require_once ("classes/Photoupload.class.php"); //võib olla ka sulgudeta

	$photo_upload_notice = null;
	$photo_error = null;

    $normal_photo_max_width = 600;
    $normal_photo_max_height = 400;
	$thumbnail_width = $thumbnail_height = 100;
	$watermark_file = "../pics/vp_logo_color_w100_overlay.png";

	$file_type = null;
	$file_name = null;
	$alt_text = null;
	$privacy = 1;

	$photo_filename_prefix = "vp_";
	$photo_upload_size_limit = 1024 * 1024; //=MB (kb*kb)
	$photo_size_ratio = 1;
	
   if(isset($_POST["photo_submit"])){
        if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
            //var_dump($_FILES["photo_input"]);
			//kas on pilt ja mis tüüpi?
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
                //var_dump($image_check);
            } else {
                $photo_error = "Valitud fail ei ole pilt!";
            }
            

			
            //Kas on lubatud suurusega?
            if(empty($photo_error) and $_FILES["photo_input"]["size"] > $photo_upload_size_limit){
                $photo_error .= "Valitud fail on liiga suur!";
            }
            
            //kas alt tekst on
            if(isset($_POST["alt_input"]) and !empty($_POST["alt_input"])){
                $alt_text = test_input(filter_var($_POST["alt_input"], FILTER_SANITIZE_STRING));
/*                 if(empty($alt_text)){
                    $photo_error .= "Alternatiivtekst on lisamata!";
                } */
            }
			
			//kas on privaatsus
			if(isset($_POST["privacy_input"]) and !empty($_POST["privacy_input"])){
				$privacy = filter_var($_POST["privacy_input"], FILTER_VALIDATE_INT);
			}
			if(empty($privacy)){
				$photo_error . " Privaatsus on määramata!";
			}
			
            
            if(empty($photo_error)){
				
				//hakkan klassi kasutama
				$photo_upload = new Photoupload($_FILES["photo_input"], $file_type);
                
				//teen ajatempli
                $time_stamp = microtime(1) * 10000;
                
                //moodustan failinime, kasutame eesliidet
                $file_name = $photo_filename_prefix .$time_stamp ."." .$file_type;
                                               
                //suuruse muutmine
                //$my_new_temp_image = resize_photo($my_temp_image, $normal_photo_max_width, $normal_photo_max_height);              
                $photo_upload->resize_photo($normal_photo_max_width, $normal_photo_max_height);
				
				//lisan vesimärgi
				//$my_new_temp_image = add_watermark($my_new_temp_image, $watermark_file);
                $photo_upload->add_watermark($watermark_file);
				
				
                //salvestan
                //$photo_upload_notice = "Vähendatud pildi " .save_image($my_new_temp_image, $file_type, $photo_normal_upload_dir .$file_name);
                $photo_upload_notice .= "Vähendatud pildi " .$photo_upload->save_image($photo_normal_upload_dir .$file_name);
				//imagedestroy($my_new_temp_image);
				
				//teen pisipildi
				// $my_new_temp_image = resize_photo($my_temp_image, $thumbnail_width, $thumbnail_height, false);
                // $photo_upload_notice .= " Pisipildi " .save_image($my_new_temp_image, $file_type, $photo_thumbnail_upload_dir .$file_name);
                // imagedestroy($my_new_temp_image);
                $photo_upload->resize_photo($thumbnail_width, $thumbnail_height);
				$photo_upload_notice .= " Pisipildi " .$photo_upload->save_image($photo_thumbnail_upload_dir .$file_name);
				
                //imagedestroy($my_temp_image);
                
                //kopeerime pildi originaalkujul, originaalnimega vajalikku kataloogi
                $photo_upload_notice .= $photo_upload->move_original($photo_orig_upload_dir .$file_name);
				
				// if(move_uploaded_file($_FILES["photo_input"]["tmp_name"], $photo_orig_upload_dir .$file_name)){
                    // $photo_upload_notice .= " Originaalfoto laeti üles!";
                    //$photo_upload_notice = store_person_photo($file_name, $_POST["person_for_photo_input"]);
                // } else {
                    // $photo_upload_notice .= " Foto üleslaadimine ei õnnestunud!";
                // }
				
				$photo_upload_notice .= " " .store_photo_data($file_name, $alt_text, $privacy);
				unset($photo_upload);
				$alt_text = null;
				$privacy = 1;
            }
        } else {
            $photo_error = "Pildifaili pole valitud!";
        }
        
        if(empty($photo_upload_notice)){
			$photo_upload_notice = $photo_error;
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

