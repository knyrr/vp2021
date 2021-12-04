<?php
	require_once("use_session.php");
	
	require_once ("../../../config.php");
	require_once ("fnc_user.php");
	require_once ("fnc_general.php");

	$notice = null;
	$description = read_user_description();
	$description_store_notice = null;
	$bg_color_store_notice = null;
	$text_color_store_notice = null;

	
	//Kas klikiti profile_submit nupul
	if(isset($_POST["profile_submit"])){
		$description = test_input(filter_var($_POST["description_input"], FILTER_SANITIZE_STRING));
		$notice = store_profile($description, $_POST["bg_color_input"], $_POST["text_color_input"]);		
	}

	require_once("page_header.php");
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
	
	
	<h2>Kasutajaprofiil</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="description_input">Minu lühikirjeldus</label>
		<br>
		<textarea name="description_input" id="description_input" rows="10" cols="80" placeholder="Minu lühikirjeldus ..."><?php echo $description;?></textarea>
		<br>
		<label for="bg_color_input">Taustavärv</label>
		<input type="color" name="bg_color_input" id="bg_color_input" value="<?php echo $_SESSION["bg_color"];?>">
		<br>
		<label for="text_color_input">Tekstivärv</label>
		<input type="color" name="text_color_input" id="text_color_input" value="<?php echo $_SESSION["text_color"];?>">
		<br>		
		<input type="submit" name="profile_submit" value="Salvesta">
		
	</form>
	<span><?php echo $notice; ?></span>
</body>
</html>