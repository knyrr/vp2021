<?php
	require_once ("../../../config.php");
    require_once("fnc_registration.php");

	$payment_notice = null;
	$selected_person_id = null;

	if(isset($_POST["payment_submit"])){
		if(isset($_POST["unpaid_persons_input"]) and !empty($_POST["unpaid_persons_input"])){
			$selected_person_id = filter_var($_POST["unpaid_persons_input"], FILTER_VALIDATE_INT);
		}
		if(empty($selected_person_id)){
			$payment_notice .= "Isik on valimata";
		}
		
		if(empty($payment_notice)){
			$payment_notice = mark_as_paid($selected_person_id);
		}
	}

?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Kinnituste haldamine</title>
</head>
<body>
	<h1>Kinnituste haldamine</h1>
	<p>Allolevad loendis on kirjas osalemise kinnitanud inimesed. Lisada saab teavet maksmise kohta.</p>
	
	<h3>Nimekiri</h3>
	<?php echo read_all_registered_people(); ?>

	


	<h3>Tasunuks märkimine</h3>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="unpaid_persons_input">Maksmata inimesed:</label></br>
		<select name="unpaid_persons_input" id="unpaid_persons_input">
			<option value="" selected disabled>Vali isik</option>
			<?php echo read_all_unpaid_people($selected_person_id);?> 
		</select>
		
		<input type="submit" name="payment_submit" value="Märgin tasunuks">
		
	</form>
	<span><?php echo $payment_notice; ?></span>


</body>
</html>