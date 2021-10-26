<?php
	function save_image($image, $file_type, $target){
		$notice = null;
		
		if($file_type == "jpg"){
			if(imagejpeg($image, $target, 90)){ //true v false
				$notice = "Pilt on salvestatud";
			} else {
				$notice = "Pildi salvestamisel tekkis tõrge!";
			}
		}
		if($file_type == "png"){
			if(imagepng($image, $target, 6)){ //true v false
				$notice = "Pilt on salvestatud";
			} else {
				$notice = "Pildi salvestamisel tekkis tõrge!";
			}
		}
		if($file_type == "gif"){
			if(imagegif($image, $target)){ //true v false
				$notice = "Pilt on salvestatud";
			} else {
				$notice = "Pildi salvestamisel tekkis tõrge!";
			}
		}
		return $notice;
	}