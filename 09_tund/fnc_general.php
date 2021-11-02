<?php
	function test_input($data) {
		$data = htmlspecialchars($data);
		$data = stripslashes($data);
		$data = trim($data);
		return $data;
	}
	
	function format_date_est($date){
		$notice = null;
		$month_names_et = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];
		if(!empty($date)){
			$temp_date = new DateTime($date);
			$day = $temp_date->format("j");
			$month = $temp_date->format("n");
			$year = $temp_date->format("Y");			
			$notice = $day .". " .$month_names_et[$month-1] ." " .$year;
		}
		return $notice;
	}
	
	function convert_to_hours_mins($duration_in_mins){
		$notice = null;
		if(!empty($duration_in_mins)){
			if ($duration_in_mins == 1){
				$minutes = $duration_in_mins;
				$notice = $minutes ." minut";
			}
			if ($duration_in_mins > 1 and $duration_in_mins <= 60){
				$minutes = $duration_in_mins;
				$notice = $minutes ." minutit";
			}
			if ($duration_in_mins > 60 and $duration_in_mins < 120){
				$hours = floor($duration_in_mins / 60);
				$minutes = $duration_in_mins % 60;
				if ($minutes == 0){
					$notice = $hours ." tund";
				}	
				if ($minutes != 0){
					$notice = $hours ." tund " .$minutes ." minutit";
				}					
			}
			if ($duration_in_mins >= 120){
				$hours = floor($duration_in_mins / 60);
				$minutes = $duration_in_mins % 60;
				if ($minutes == 0){
					$notice = $hours ." tundi";
				}	
				if ($minutes != 0){
					$notice = $hours ." tundi " .$minutes ." minutit";
				}					
			}	
		}
		return $notice;	
	}