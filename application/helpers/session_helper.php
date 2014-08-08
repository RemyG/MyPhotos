<?php

class Session_helper {

	function set($key, $val)
	{
		$_SESSION["$key"] = $val;
	}
	
	function get($key)
	{
		if(array_key_exists($key, $_SESSION)) {
			return htmlentities($_SESSION[$key]);
		}
		return null;
	}
	
	function destroy()
	{
		session_destroy();
	}

}

?>