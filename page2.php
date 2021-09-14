<?php
	$author_name = "Martin Rünk";
	
	//VORM
	//vaatan, mida post meetodil saadeti
	//var_dump($_POST);
	$today_html =  null;
	$today_adjective_error = null;
	//kontrollin, kas klikite submit nupul
	if(isset($_POST["submit_todays_adjective"])){
		//echo "Klikiti nupul";
		if(!empty($_POST["todays_adjective_input"])){
			$today_html = "<p>Tänane päev on " . $_POST["todays_adjective_input"] ."</p>";
		} else {
			$today_adjective_error = "Palun kirjutage tänase kohta omadusõna.";
		}
	}
	
	//JUHUSLIK FOTO
	//lisan lehele juhuslikud fotod
	$photo_dir = "photos/";
	//loen kataloogi sisu
	//$all_files = scandir($photo_dir);
	$all_files = array_slice(scandir($photo_dir), 2);
	//echo $all_files;
	//var_dump($all_files);
	//kontrollin ja võtan  ainult fotod
	$allowed_photo_types = ["image/jpeg", "image/png"];
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
	$photo_num = mt_rand(0, $file_count-1); //kiirem kui rand
	//echo $photo_num;
	//<img src="photos/pilt.jpg alt=Tallinna Ülikool">
	$photo_html = '<img src="' .$photo_dir .$all_photos[$photo_num] .'" alt=Tallinna Ülikool">';
	

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
	<!--ekraanivorm-->
	<form method="post">
		<input type="text" name="todays_adjective_input" placeholder="Tänase ilma omadus">
		<input type="submit" name="submit_todays_adjective" value="Saada ära">
		<span><?php echo $today_adjective_error; ?></span>
	</form>
	<?php echo $today_html; ?>
	<hr>
	
	<?php echo $photo_html; ?>
</body>
</html>