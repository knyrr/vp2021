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
	require_once ("fnc_general.php");
	require_once ("fnc_gallery.php");

	if(isset($_GET["page"])){
		$id_from_get = $_GET["page"];
	}

	$alttext = null;
	$privacy = null;
	$update_notice = null;
	$delete_notice = null;
	
    if(isset($_POST["photo_update_submit"])){
		if(isset($_POST["alt_input"]) and !empty($_POST["alt_input"])){
			$alttext = test_input(filter_var($_POST["alt_input"], FILTER_SANITIZE_STRING));
		}
		if(isset($_POST["privacy_input"]) and !empty($_POST["privacy_input"])){
			$privacy = filter_var($_POST["privacy_input"], FILTER_VALIDATE_INT);
		}
		$update_notice = update_own_photo_data($id_from_get, $alttext, $privacy);
	}
	
	if(isset($_POST["photo_delete_submit"])){
		$delete_notice = delete_photo($id_from_get);
	}
		
	$to_head = '<link rel="stylesheet" type="text/css" href="style/gallery.css">';
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
	
	<h2>Foto andmete muutmine</h2>

	<div>
	<p>
	</p>
	<?php echo show_own_photo($id_from_get); ?>
	<?php echo $update_notice; ?>
	<?php echo $delete_notice; ?>

	</div>


</body>
</html>

