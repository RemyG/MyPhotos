<?php

class Form_helper {

	function getCleanValue($array, $key) {

		if(array_key_exists($key, $array)) {
			$value = $array[$key];
			if(is_array($value)) {
				foreach($value as $key=>$val) {
					$value[$key] = htmlentities($val);
				}
				return $value;
			} else {
				return htmlentities($array[$key]);
			}
		}
		return null;
		
	}

}