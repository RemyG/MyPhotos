<?php

function isCurrentUserAdmin() {
	
	$user_dao = DAOFactory::getFactory()->getUserDAO();
	
	$user_id = cleanGlobal($_SESSION, 'user_id');
	$user_hash = cleanGlobal($_SESSION, 'user_hash');
	
	if($user_id && $user_hash) {
		return $user_dao->isUserAdmin($user_id, $user_hash);
	}
	
	return false;
	
}

function isConnected() {
	
	$user_id = cleanGlobal($_SESSION, 'user_id');
	$user_hash = cleanGlobal($_SESSION, 'user_hash');
	$user_desc = cleanGlobal($_SESSION, 'user_desc');
	
	$output = null;
	
	if($user_id && $user_hash) {
		$output = "Connected";
		if($user_desc && $user_desc != "") {
			$output .= " as ".$user_desc;
		}
	}
	
	return $output;
	
}