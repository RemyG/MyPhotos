<?php

class Album_model extends Model {

	public function getAllAlbums() {

		$stmt = $this->prepareStatement('SELECT
					alb_id
					, alb_title
					, alb_date_crea
					, alb_hashname
				FROM
					'.DB_TABLE_PREFIX.'album
				ORDER BY
					alb_date_crea DESC
				;');

		$results = $this->executeStatement($stmt);

		$list_result = array();

		foreach($results as $result) {
			$list_result[] = new AlbumDTO($result['alb_id'], $result['alb_title'],
				$result['alb_date_crea'], $result['alb_hashname']);
		}

		return $list_result;

	}

	public function getAlbumById($alb_id) {

		$stmt = $this->prepareStatement('SELECT
					alb_id
					, alb_title
					, alb_date_crea
					, alb_hashname
				FROM
					'.DB_TABLE_PREFIX.'album
				WHERE
					alb_id = ?
				;');

		$result = $this->executeStatement($stmt, array($alb_id));

		$album = null;

		if(sizeof($result) == 1) {

			$album = new AlbumDTO($result[0]['alb_id'], $result[0]['alb_title'],
				$result[0]['alb_date_crea'], $result[0]['alb_hashname']);

		}

		return $album;

	}

	public function getAlbumByTitle($alb_title) {

		$stmt = $this->prepareStatement('SELECT
					alb_id
					, alb_title
					, alb_date_crea
					, alb_hashname
				FROM
					'.DB_TABLE_PREFIX.'album
				WHERE
					alb_title = ?
				;');

		$result = $this->executeStatement($stmt, array($alb_title));

		$album = null;

		if(sizeof($result) == 1) {

			$album = new AlbumDTO($result[0]['alb_id'], $result[0]['alb_title'],
				$result[0]['alb_date_crea'], $result[0]['alb_hashname']);

		}

		return $album;

	}

	public function getAlbumByHashname($alb_hashname) {

		$stmt = $this->prepareStatement('SELECT
					alb_id
					, alb_title
					, alb_date_crea
					, alb_hashname
				FROM
					'.DB_TABLE_PREFIX.'album
				WHERE
					alb_hashname = ?
				;');

		$result = $this->executeStatement($stmt, array($alb_hashname));

		$album = null;

		if(sizeof($result) == 1) {

			$album = new AlbumDTO($result[0]['alb_id'], $result[0]['alb_title'],
				$result[0]['alb_date_crea'], $result[0]['alb_hashname']);

		}

		return $album;

	}

	public function createAlbum($album) {

		$stmt = $this->prepareStatement('INSERT INTO
					'.DB_TABLE_PREFIX.'album
				SET
					alb_title = ?
					, alb_date_crea = ?
					, alb_hashname = ?
				;');

		$result = $this->executeStatementInsert($stmt, array($album->title, $album->date_crea, $album->hashname));

		return $result;

	}

	public function removeCoverFromAlbum($alb_id) {

		$stmt = $this->prepareStatement('UPDATE
					'.DB_TABLE_PREFIX.'picture
				SET
					cover = 0
				WHERE
					alb_id = ?
				;');

		$result = $this->executeStatementUpdate($stmt, array($alb_id));

		return $result;

	}

	public function setCoverToAlbum($alb_id, $pic_id) {

		$stmt = $this->prepareStatement('UPDATE
					'.DB_TABLE_PREFIX.'picture
				SET
					cover = 1
				WHERE
					alb_id = ?
					AND pic_id = ?
				;');

		$result = $this->executeStatementUpdate($stmt, array($alb_id, $pic_id));

		return $result;

	}

	public function deleteAlbum($alb_id) {

		$stmt = $this->prepareStatement('DELETE FROM
					'.DB_TABLE_PREFIX.'album
				WHERE
					alb_id = ?
				;');

		$result = $this->executeStatementUpdate($stmt, array($alb_id));

		return $result;

	}

	public function getAlbumForUser($user_hash) {

		$stmt = $this->prepareStatement('SELECT
					'.DB_TABLE_PREFIX.'album.alb_id
					, alb_title
					, alb_date_crea
					, alb_hashname
				FROM
					'.DB_TABLE_PREFIX.'album
				JOIN
					'.DB_TABLE_PREFIX.'album_user
						ON '.DB_TABLE_PREFIX.'album_user.alb_id = '.DB_TABLE_PREFIX.'album.alb_id
				JOIN
					'.DB_TABLE_PREFIX.'user
						ON '.DB_TABLE_PREFIX.'album_user.user_id = '.DB_TABLE_PREFIX.'user.user_id
				WHERE
					'.DB_TABLE_PREFIX.'user.user_hash = ?
				ORDER BY
					alb_date_crea DESC
				;');

		$results = $this->executeStatement($stmt, array($user_hash));

		$list_result = array();

		foreach($results as $result) {
			$list_result[] = new AlbumDTO($result['alb_id'], $result['alb_title'],
				$result['alb_date_crea'], $result['alb_hashname']);
		}

		return $list_result;

	}

	public function clearAlbumsForUser($user_id) {

		$stmt = $this->prepareStatement('DELETE FROM
					'.DB_TABLE_PREFIX.'album_user
				WHERE
					user_id = ?
				;');

		$result = $this->executeStatementUpdate($stmt, array($user_id));

		return $result;

	}

	public function addAlbumToUser($alb_id, $user_id) {

		$stmt = $this->prepareStatement('INSERT INTO
					'.DB_TABLE_PREFIX.'album_user
				SET
					alb_id = ?
					, user_id = ?
				;');

		$result = $this->executeStatementInsert($stmt, array($alb_id, $user_id));

		return $result;

	}

	public function hasUserRightToSeeAlbum($user_hash, $alb_hashname) {

		$stmt = $this->prepareStatement('SELECT
					'.DB_TABLE_PREFIX.'album.alb_id
				FROM
					'.DB_TABLE_PREFIX.'album_user
				JOIN
					'.DB_TABLE_PREFIX.'user
						ON '.DB_TABLE_PREFIX.'album_user.user_id = '.DB_TABLE_PREFIX.'user.user_id
				JOIN
					'.DB_TABLE_PREFIX.'album
						ON '.DB_TABLE_PREFIX.'album_user.alb_id = '.DB_TABLE_PREFIX.'album.alb_id
				WHERE
					'.DB_TABLE_PREFIX.'user.user_hash = ?
					AND '.DB_TABLE_PREFIX.'album.alb_hashname = ?
				;');

		$result = $this->executeStatement($stmt, array($user_hash, $alb_hashname));

		$album = null;

		if(sizeof($result) == 1) {

			return true;

		}

		return false;

	}

	public function updateAlbum($album) {

		$stmt = $this->prepareStatement('UPDATE
					'.DB_TABLE_PREFIX.'album
				SET
					alb_title = ?
					, alb_date_crea = ?
					, alb_hashname = ?
				WHERE
					alb_id = ?
				;');

		$result = $this->executeStatementUpdate($stmt, array($album->title, $album->date_crea, $album->hashname, $album->id));

		return $result;

	}

}