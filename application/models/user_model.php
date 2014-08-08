<?php

class User_model extends Model {

	public function createUser(User $user) {

		$stmt = $this->prepareStatement('INSERT INTO
					'.DB_TABLE_PREFIX.'user
				SET
					user_hash 			= ?
					, user_password 	= ?
					, user_desc			= ?
				;');

		$result = $this->executeStatementInsert($stmt, array($user->hash, $user->password, $user->desc));

		return $result;

	}

	public function getAllUsers() {

		$stmt = $this->prepareStatement('SELECT
					user_id
					, user_hash
					, user_desc
				FROM
					'.DB_TABLE_PREFIX.'user
				;');


		$results = $this->executeStatement($stmt);

		$list_result = array();

		foreach($results as $result) {
			$list_result[] = new UserDTO($result['user_id'], $result['user_hash'], null, $result['user_desc']);
		}

		return $list_result;

	}

	public function getUserByHash($usr_hash) {

		$stmt = $this->prepareStatement('SELECT
					user_id
					, user_hash
					, user_desc
					, user_admin
				FROM
					'.DB_TABLE_PREFIX.'user
				WHERE
					user_hash = ?
				;');

		$result = $this->executeStatement($stmt, array($usr_hash));

		$user = null;

		if(sizeof($result) == 1) {

			$user = new UserDTO($result[0]['user_id'], $result[0]['user_hash'], null,
				$result[0]['user_desc'], $result[0]['user_admin']);

		}

		return $user;

	}

	public function getUserByHashPassword($usr_hash, $password) {

		$stmt = $this->prepareStatement('SELECT
					user_id
					, user_hash
					, user_desc
				FROM
					'.DB_TABLE_PREFIX.'user
				WHERE
					user_hash = ?
					AND user_password = ?
				;');

		$result = $this->executeStatement($stmt, array($usr_hash, $password));

		if(sizeof($result) == 1) {

			return new UserDTO($result[0]['user_id'], $result[0]['user_hash'], null, $result[0]['user_desc']);

		}

		return null;

	}

	public function updateUser($user) {

		$stmt = $this->prepareStatement('UPDATE
					'.DB_TABLE_PREFIX.'user
				SET
					user_hash 			= ?
					, user_desc			= ?
					'.($user->password != null ? ', user_password = ?' : '').'
					, user_admin		= ?
				WHERE
					user_id 				= ?
				;');

		if($user->password != null) {
			$result = $this->executeStatementUpdate($stmt, array($user->hash, $user->desc, $user->password, $user->admin, $user->id));
		} else {
			$result = $this->executeStatementUpdate($stmt, array($user->hash, $user->desc, $user->admin, $user->id));
		}

		return $result;

	}

	public function isUserAdmin($user_id, $user_hash) {

		$stmt = $this->prepareStatement('SELECT
					user_admin
				FROM
					'.DB_TABLE_PREFIX.'user
				WHERE
					user_id = ?
					AND user_hash = ?
				;');

		$result = $this->executeStatement($stmt, array($user_id, $user_hash));

		if(sizeof($result) == 1) {

			if($result[0]['user_admin'] == 1) {
				return true;
			}

		}

		return false;

	}

}