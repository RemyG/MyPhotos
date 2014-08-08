<?php

require_once(APP_DIR.'models/include_list.php');

class Controller {
	
	public function loadModel($name)
	{
		$model = new $name;
		return $model;
	}
	
	public function loadView($name)
	{
		$view = new View($name);
		return $view;
	}
	
	public function loadPlugin($name)
	{
		require(APP_DIR .'plugins/'. strtolower($name) .'.php');
	}
	
	public function loadHelper($name)
	{
		require_once(APP_DIR .'helpers/'. strtolower($name) .'.php');
		$helper = new $name;
		return $helper;
	}
	
	public function redirect($loc)
	{		
		header('Location: '. BASE_URL . $loc);
	}

	public function isUserAdmin() {

		$userModel = $this->loadModel('User_model');
		$sessionHelper = $this->loadHelper('Session_helper');
		
		$user_id = $sessionHelper->get('user_id');
		$user_hash = $sessionHelper->get('user_hash');
		
		if($user_id && $user_hash) {
			return $userModel->isUserAdmin($user_id, $user_hash);
		}
		
		return false;

	}

	function isConnected() {

		$sessionHelper = $this->loadHelper('Session_helper');
	
		$user_id = $sessionHelper->get('user_id');
		$user_hash = $sessionHelper->get('user_hash');
		$user_desc = $sessionHelper->get('user_desc');
		
		$output = null;
		
		if($user_id && $user_hash) {
			$output = "Connected";
			if($user_desc && $user_desc != "") {
				$output .= " as ".$user_desc;
			}
		}
		
		return $output;
		
	}
    
}

?>