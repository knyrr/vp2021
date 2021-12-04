<?php
	require_once ("../../../config.php");
    require_once("fnc_registration.php");


	$firstname = null;
	$lastname = null;
	$studentcode = null;
	
	$firstname_error = null;
	$lastname_error = null;
	$studentcode_error = null;
	
	$registration_notice = null;

	if(isset($_POST["registration_submit"])){
		//eesnimi
		if(isset($_POST["firstname_input"]) and !empty($_POST["firstname_input"])){
			$firstname = test_input(filter_var($_POST["firstname_input"], FILTER_SANITIZE_STRING));
			if(empty($firstname)){
				$firstname_error = "Palun sisesta oma eesnimi!";
			}
		} else {
			$firstname_error = "Palun sisesta oma eesnimi!";
		}
		//perekonnanimi
		if(isset($_POST["lastname_input"]) and !empty($_POST["lastname_input"])){
			$lastname = test_input(filter_var($_POST["lastname_input"], FILTER_SANITIZE_STRING));
			if(empty($lastname)){
				$lastname_error = "Palun sisesta oma perekonnanimi!";
			}
		} else {
			$lastname_error = "Palun sisesta oma perekonnanimi!";
		}
		//üliõpilaskood
		if(isset($_POST["studentcode_input"]) and !empty($_POST["studentcode_input"])){
			$studentcode = filter_var($_POST["studentcode_input"], FILTER_VALIDATE_INT);
			$numlength = strlen((string)$studentcode);
			if($numlength != 6){
				$studentcode_error = "Palun sisesta kuukohaline üliõpilaskood!";
			}
		} else {
			$studentcode_error = "Palun sisesta kuukohaline üliõpilaskood!";
		}
		//Kui kõik korras, siis salvestan
		if(empty($firstname_error) and empty($lastname_error) and empty($studentcode_error)){
			$registration_notice = register_for_party($firstname, $lastname, $studentcode);
			$firstname = null;
			$lastname = null;
			$studentcode = null;
		}
		
	}
	

?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Peol osalemise kinnitamine</title>
</head>
<body>
	<h1>Peol osalemise kinnitamine</h1>
	<p>Pane oma nimi kirja allolevas vormis.</p>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="firstname_input">Eesnimi:</label><br>
		<input name="firstname_input" id="firstname_input" type="text" value="<?php echo $firstname; ?>"><span><?php echo $firstname_error; ?></span><br>
		<label for="lastname_input">Perekonnanimi:</label><br>
		<input name="lastname_input" id="lastname_input" type="text" value="<?php echo $lastname; ?>"><span><?php echo $lastname_error; ?></span><br>
		<label for="studentcode_input">Üliõpilaskood:</label><br>
		<input name="studentcode_input" id="studentcode_input" type="text" value="<?php echo $studentcode; ?>"><span><?php echo $studentcode_error; ?></span><br>
		<input name="registration_submit" type="submit" value="Kinnitan osalemise"><span><br>
		<br>
		<?php echo $registration_notice; ?></span>
		
	<hr>
	
	<h3>Peole tulijad (osalemise kinnitanud ja maksnud)</h3>
	<p>Peol osalemise on kinnitanud <?php echo count_all_registred_people(); ?> inimest.</p>
	<p>Pileti eest on maksund neist <?php echo count_all_confirmed_people(); ?> inimest.</p>

	<hr>
	
	<h3>Kustutamine</h3>
	<p><a href="registration_cancellation.php">Osalemise tühistamine</a></p>

		
</body>
</html>