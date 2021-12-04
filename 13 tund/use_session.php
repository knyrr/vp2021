<?php
	//session_start();
	require_once("classes/SessionManager.class.php");
	SessionManager::sessionStart("vp", 0, "/~marrun/vp2021/", "greeny.cs.tlu.ee");
	//sisselogimise kontroll
	if(!isset($_SESSION["user_id"])){
		header("Location: page.php");
	}
	//väljalogimise kontroll	
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
	}