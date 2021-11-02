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
	require_once ("fnc_gallery.php");
	
	$page = 1;
	$limit = 3;
	$photo_count = count_own_photos();
	if(!isset($_GET["page"]) or $_GET["page"] < 1){
		$page = 1;
	} elseif(round($_GET["page"] - 1) * $limit >= $photo_count){
		$page = ceil($photo_count / $limit);
	} else {
		$page = $_GET["page"];
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
	
	<h2>Minu oma fotode galerii</h2>

	<div>
	<p>
	<?php
		if($page > 1){
			echo '<span><a href="?page=' .($page - 1) .'">Eelmine leht</a></span>  | ';
		} else {
			echo "<span>Eelmine leht</span>  | ";
		}
		if($page * $limit < $photo_count){
			echo '<span><a href="?page=' .($page + 1) .'">Järgmine leht</a></span>';
		} else {
			echo "<span>Järgmine leht</span>";
		}
	?>
	</p>
	<?php echo read_own_photo_thumbs($page, $limit); ?>
	</div>


</body>
</html>

