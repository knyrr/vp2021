<?php
	session_start();
	$author_name = $_SESSION["user_name"];
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
	require_once ("fnc_user.php");
	require_once ("fnc_general.php");

	

	$notice = null;
	$description = null; //tulevikus laetakse siia andmetablist kirjeldus
	require_once("page_header.php");
?>
	<h1><?php echo $author_name; ?>, veebiprogrammeerimine</h1>
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
		<label for="bg_text_input">Tekstivärv</label>
		<input type="color" name="bg_text_input" id="bg_text_input" value="<?php echo $_SESSION["text_color"];?>">
		<br>		
		<input type="submit" name="profile_submit" value="Salvesta">
	</form>
	<span><?php echo $notice; ?></span>
	
</body>
</html>