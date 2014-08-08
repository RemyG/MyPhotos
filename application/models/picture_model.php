<?php

class Picture_model extends Model {

	public function getPictureById($pic_id) {

		$stmt = $this->prepareStatement('SELECT
					pic_id
					, pic_url
					, alb_id
					, cover
					, pic_desc
				FROM
					'.DB_TABLE_PREFIX.'picture
				WHERE
					pic_id = ?
				;');

		$result = $this->executeStatement($stmt, array($pic_id));

		$picture = null;

		if(sizeof($result) == 1) {

			$picture = new PictureDTO($result[0]['pic_id'], $result[0]['pic_url'],
				$result[0]['alb_id'], $result[0]['cover'], $result[0]['pic_desc']);

		}

		return $picture;

	}

	public function getAllPictures() {

		$stmt = $this->prepareStatement('SELECT
					pic_id
					, pic_url
					, alb_id
					, cover
					, pic_desc
				FROM
					'.DB_TABLE_PREFIX.'picture
				;');

		$results = $this->executeStatement($stmt);

		$list_result = array();

		foreach($results as $result) {
			$list_result[] = new PictureDTO($result['pic_id'], $result['pic_url'],
				$result['alb_id'], $result['cover'], $result['pic_desc']);
		}

		return $list_result;

	}

	public function getAllPicturesForAlbum($alb_id) {

		$stmt = $this->prepareStatement('SELECT
					pic_id
					, pic_url
					, alb_id
					, cover
					, pic_desc
				FROM
					'.DB_TABLE_PREFIX.'picture
				WHERE
					alb_id = ?
				ORDER BY
					pic_url ASC
				;');

		$results = $this->executeStatement($stmt, array($alb_id));

		$list_result = array();

		foreach($results as $result) {
			$list_result[] = new PictureDTO($result['pic_id'], $result['pic_url'],
				$result['alb_id'], $result['cover'], $result['pic_desc']);
		}

		return $list_result;

	}

	public function insertPicture($picture) {

		$stmt = $this->prepareStatement('INSERT INTO
					'.DB_TABLE_PREFIX.'picture
				SET
					pic_url = ?
					, alb_id = ?
					, cover = ?
					, pic_desc = ?
				;');

		$result = $this->executeStatementInsert($stmt, array($picture->url, $picture->alb_id, $picture->cover, $picture->desc));

		return $result;

	}

	public function updatePicture($picture) {

		$stmt = $this->prepareStatement('UPDATE
					'.DB_TABLE_PREFIX.'picture
				SET
					pic_url = ?
					, alb_id = ?
					, cover = ?
					, pic_desc = ?
				WHERE
					pic_id = ?
				;');

		$result = $this->executeStatementUpdate($stmt, array($picture->url, $picture->alb_id, $picture->cover, $picture->desc, $picture->id));

		return $result;

	}

	public function deletePicture($pic_id) {

		$stmt = $this->prepareStatement('DELETE FROM
					'.DB_TABLE_PREFIX.'picture
				WHERE
					pic_id = ?
				;');

		$result = $this->executeStatementUpdate($stmt, array($pic_id));

		return $result;

	}

	public function deletePicturesFromAlbum($alb_id) {

		$stmt = $this->prepareStatement('DELETE FROM
					'.DB_TABLE_PREFIX.'picture
				WHERE
					alb_id = ?
				;');

		$result = $this->executeStatementUpdate($stmt, array($alb_id));

		return $result;

	}

}