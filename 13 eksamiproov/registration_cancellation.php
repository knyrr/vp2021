<?php
	require_once ("../../../config.php");
    require_once("fnc_registration.php");

	$cancellation_notice = null;
	$studentcode = null;
	$studentcode_error = null;
	

	if(isset($_POST["cancellation_submit"])){
		if(isset($_POST["studentcode_input"]) and !empty($_POST["studentcode_input"])){
			$studentcode = filter_var($_POST["studentcode_input"], FILTER_VALIDATE_INT);
			$numlength = strlen((string)$studentcode);
			if($numlength != 6){
				$studentcode_error = "Palun sisesta kuukohaline üliõpilaskood!";
			}
		} else {
			$studentcode_error = "Palun sisesta kuukohaline üliõpilaskood!";
		}
		
		if(empty($studentcode_error)){
			$cancellation_notice = cancel_registration($studentcode);
		}
	}

?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Osalemise tühistamine</title>
</head>
<body>
	<h1>Osalemise tühistamine</h1>
	<p>Alljärgneva vormiga on võimalik osalemine tühistada.</p>

	<h3>Tühistamisvorm</h3>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="studentcode_input">Üliõpilaskood:</label><br>
		<input name="studentcode_input" id="studentcode_input" type="text" value="<?php echo $studentcode; ?>"><span><?php echo $studentcode_error; ?></span><br>
		<input type="submit" name="cancellation_submit" value="Tühistan osalemise">
		
	</form>
	<span><?php echo $cancellation_notice; ?></span>


</body>
</html>