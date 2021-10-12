<?php
	session_start();
	require_once("fnc_user.php");
	require_once("fnc_general.php");
	require_once ("../../../config.php");
	$author_name = "Martin Rünk";
	$email = null;
	$password = null;
	$notice = null;

	
	//VORM
	//vaatan, mida post meetodil saadeti
	//var_dump($_POST);
	$today_html =  null;
	$today_adjective_error = null;
	$todays_adjective = null;
	$photo_select_html = null;
	$selected_photo_html = null;
	//kontrollin, kas klikite submit nupul
	if(isset($_POST["submit_todays_adjective"])){
		//echo "Klikiti nupul";
		if(!empty($_POST["todays_adjective_input"])){
			$today_html = "<p>Tänane päev on " . $_POST["todays_adjective_input"] .".</p>";
			$todays_adjective = $_POST["todays_adjective_input"];
		} else {
			$today_adjective_error = "Palun kirjutage tänase kohta omadusõna.";
		}
	}
	
	//JUHUSLIK FOTO
	//lisan lehele juhuslikud fotod
	$photo_dir = "../photos/";
	//loen kataloogi sisu
	//$all_files = scandir($photo_dir);
	$all_files = array_slice(scandir($photo_dir), 2);
	//echo $all_files;
	//var_dump($all_files);
	//kontrollin ja võtan  ainult fotod
	$allowed_photo_types = ["image/jpeg", "image/png", "image/bmp"];
	$all_photos = [];
	foreach ($all_files as $file){
		$file_info = getimagesize($photo_dir .$file);
		if (isset($file_info ["mime"])){
			if (in_array($file_info["mime"], $allowed_photo_types)){
				array_push($all_photos, $file);
			} //if in_array lõppeb
		} // if isset lõppet
	} //foreach lõppeb
	$file_count = count($all_photos);
	//$photo_num = mt_rand(0, $file_count-1); //kiirem kui rand();
	//echo $photo_num;
	//<img src="photos/pilt.jpg alt=Tallinna Ülikool">
	

	//FOTO VALIMISE VORM

	if (isset($_POST["submit_photo_select"])){
		if (!empty($_POST["photo_select"])){
			$photo_num = $_POST["photo_select"];
		}
		else {
			$photo_num = mt_rand(0, $file_count-1);
		}
	}
	else {
		$photo_num = mt_rand(0, $file_count-1);	
	}
	$photo_html = '<img src="' .$photo_dir .$all_photos[$photo_num] .'" alt="Tallinna Ülikool">';
	
	//FOTODE LOEND
	$photo_list_html = "\n<ul>\n";
	//tsükkel
	//for/$i=algväärtus; $i < algväärtus; $i muutumine){...}
	
	//<ul>
	//<li>pilt.jpg</li>
	//</ul>
	
	for ($i =0; $i < $file_count; $i ++) {
		$photo_list_html.= "<li>" . $all_photos [$i] . "</li> \n";
	}
	$photo_list_html.= "</ul>";
	
	//FOTO VALIK
	/* 	<select name="photo_select">
		<option value="0">tlu_astra_600x400_1.jpg</option> 
		<option value="1">tlu_astra_600x400_2.jpg</option> 
		<option value="2">tlu_hoov_600x400_1.jpg</option> 
		<option value="3">tlu_mare_600x400_1.jpg</option> 
		<option value="4">tlu_mare_600x400_2.jpg</option> 
		<option value="5">tlu_terra_600x400_1.jpg</option> 
		<option value="6">tlu_terra_600x400_2.jpg</option> 
		<option value="7">tlu_terra_600x400_3.jpg</option> 
	</select>  */


	$photo_select_html = '<select name="photo_select">' ."\n";
	$photo_select_html .= '<option value="' .$photo_num .'">' .$all_photos[$photo_num] ."</option> \n";	

	for($i = 0; $i < $file_count; $i ++){
		if ($i == $photo_num ){
			continue;
		}
		$photo_select_html .= '<option value="' .$i .'">' .$all_photos[$i] ."</option> \n";
	}
	$photo_select_html .= "</select> \n";
	
	//sisse logimine
	if(isset($_POST ["login_submit"])){
		//kontrollin meiliaadressi
		if (isset($_POST["email_input"]) and !empty($_POST["email_input"])){
			$email = filter_var($_POST["email_input"], FILTER_VALIDATE_EMAIL);
		}
		//kontrollin salasõna
		if (isset($_POST["password_input"]) and !empty($_POST["password_input"])){
			$password = test_input(filter_var($_POST["password_input"], FILTER_SANITIZE_STRING));
		}
		if (!empty($email) and !empty($password) and strlen($password) >= 8){
			$notice = sign_in($email, $password);	
		} else {
			$notice = "Kasutajanimi või salasõna on puudulikud";
		}	
	}
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $author_name; ?>, veebiprogrammeerimine</title>
</head>
<body>
	<h1><?php echo $author_name; ?>, veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli digitehnoloogiate instituudis</a>.</p>
	<p>Õppetöö toimus 2021. aasta sügisel.</p>
	<hr>
	<!--sisselogimine-->
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<input type="email" name="email_input" placeholder="kasutajatunnus ehk e-post" value="<?php echo $email; ?>">
	<input type="password" name="password_input" placeholder="salasõna">
	<input type="submit" name="login_submit" value="Logi sisse">
	<span><?php echo $notice; ?></span>
	
	<p>Loo endale <a href="add_user.php">kasutaja</a>!</p>
	<hr>
	<!--ekraanivorm-->
	<form method="post">
		<input type="text" name="todays_adjective_input" placeholder="Tänase ilma omadus" value="<?php echo $todays_adjective; ?>">
		<input type="submit" name="submit_todays_adjective" value="Saada ära">
		<span><?php echo $today_adjective_error; ?></span>
	</form>
	<?php echo $today_html; ?>
	<hr>
	
	<form method="POST">
		<label for="photo_select">Vali pilt:</label>
		<?php echo $photo_select_html; ?>
		<input type="submit" name="submit_photo_select" value="Saada ära">		
	</form>
	

	
	<?php 
		echo $photo_html;
	?>
	
	<p> <span>Foto: <?php echo $all_photos[$photo_num]; ?></span>
	
	<?php
	echo $photo_list_html;
	?>
</body>
</html>