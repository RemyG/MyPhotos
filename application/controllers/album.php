<?php

class Album extends Controller {

	function show($alb_hashname)
	{
		$error_messages = array();
		$success_messages = array();

		$right_menu[] = 'show_album';

		if(!$this->isConnected()) {
			$this->redirect('error/401');
		}

		$isUserAdmin = $this->isUserAdmin();

		$pictureModel = $this->loadModel('Picture_model');
		$albumModel = $this->loadModel('Album_model');

		$formHelper = $this->loadHelper('Form_helper');
		$sessionHelper = $this->loadHelper('Session_helper');

		$this->loadPlugin('date_plugin');
		$this->loadPlugin('image_plugin');
		$this->loadPlugin('string_plugin');

		if(!$isUserAdmin && !$albumModel->hasUserRightToSeeAlbum($sessionHelper->get('user_hash'), $alb_hashname)) {
			$this->redirect('error/401');
		}

		$album = $albumModel->getAlbumByHashname($alb_hashname);

		if($isUserAdmin && $_POST && key_exists('deleteAlbum', $_POST)) {

			$alb_id = $formHelper->getCleanValue($_POST, 'alb_id');

			$result = $albumModel->deleteAlbum($alb_id);
			$pictureModel->deletePicturesFromAlbum($alb_id);

			if(is_array($result)) {

				$error_messages[] = "Error while getting album # ".$alb_id." - ".$result[0]." - ".$result[1];

			} else if(is_int($result) && $result == 1) {

				if(is_dir(PICTURE_PATH.$alb_id)) {
					$this->deleteDir(PICTURE_PATH.$alb_id);
				}
				if(is_dir(PICTURE_PATH.COVER_DIR.$alb_id)) {
					$this->deleteDir(PICTURE_PATH.$alb_id);
				}
				if(is_dir(PICTURE_PATH.THUMB_DIR.$alb_id)) {
					$this->deleteDir(PICTURE_PATH.$alb_id);
				}

				$this->redirect('');

			}

		}

		if($isUserAdmin && $_POST && key_exists('editTitle', $_POST))
		{
			$album->title = $formHelper->getCleanValue($_POST, 'alb_title');
			$albumModel->updateAlbum($album);
		}

		if($isUserAdmin && $_POST && key_exists('saveChanges', $_POST))
		{
			$nb_updated = 0;

			foreach($_POST as $key=>$value)
			{
				if(startsWith($key, 'desc_'))
				{
					$pic_id = explode("_", $key);
					$pic_id = $pic_id[1];
					$picture = $pictureModel->getPictureById($pic_id);
					if(is_array($picture))
					{
						$error_messages[] = "Error while getting image # ".$pic_id." - ".$picture[0]." - ".$picture[1];
					}
					else if(is_a($picture, 'PictureDTO'))
					{
						$picture->desc = $value;
						$result = $pictureModel->updatePicture($picture);

						if(is_array($result))
						{
							$error_messages[] = "Error while updating image # ".$pic_id." - ".$result[0]." - ".$result[1];
						}
						else if(is_int($result) && $result == 1)
						{
							$nb_updated++;
						}
						else
						{
							$error_messages[] = "Unknown error while updating image # ".$pic_id;
						}
					}
					else
					{
						$error_messages[] = "Unknown error while getting image # ".$pic_id;
					}
				}
			}
		}

		if($isUserAdmin && key_exists("filesToUpload", $_FILES) && count($_FILES['filesToUpload']) > 0)
		{
			$alb_id = $formHelper->getCleanValue($_POST, "alb_id");

			for($i = 0 ; $i < count($_FILES['filesToUpload']['name']) ; $i++)
			{
				$name = $_FILES['filesToUpload']['name'][$i];
				$name = insertTimeToImageUrl($name);
				$type= $_FILES['filesToUpload']['type'][$i];
				$size = $_FILES['filesToUpload']['size'][$i];
				$tmp = $_FILES['filesToUpload']['tmp_name'][$i];

				if (file_exists(PICTURE_PATH.$alb_id."/". $name))
				{
					$error_messages[] = $name . " already exists. ";
				}
				else
				{
					if(move_uploaded_file($tmp, PICTURE_PATH.$alb_id."/".$name))
					{
						copy(PICTURE_PATH.$alb_id."/".$name, PICTURE_PATH.$alb_id."/orig-".$name);
						createThumbnail($alb_id."/".$name);
						$picture = new PictureDTO(null, $alb_id."/" .$name, $alb_id, 0, "");
						$pictureModel->insertPicture($picture);
						$success_messages[] = "Uploaded ".$name;
					}
					else
					{
						$error_messages[] = "Error while creating file for ".$name;
					}
				}
			}
		}

		if($isUserAdmin && $_POST && key_exists('changeDate', $_POST))
		{
			$albDate = $formHelper->getCleanValue($_POST, 'alb_date');
			$time = strtotime($albDate);
			if($time !== false)
			{
				$album->date_crea = $time;
				$albumModel->updateAlbum($album);
			}
		}

		$alb_day = getDateAsDDFromTimestamp($album->date_crea);
		$alb_month = getDateAsMMFromTimestamp($album->date_crea);
		$alb_year = getDateAsYYYYFromTimestamp($album->date_crea);

		$list_pictures = $pictureModel->getAllPicturesForAlbum($album->id);
		foreach($list_pictures as $picture)
		{
			if($picture->cover)
			{
				$album->cover = $picture;
			}
		}

		$isConnected = $this->isConnected();

		$alb_day = getDateAsDDFromTimestamp($album->date_crea);
		$alb_month = getDateAsMMFromTimestamp($album->date_crea);
		$alb_year = getDateAsYYYYFromTimestamp($album->date_crea);

		$alb_date = date("Y-m-d", $album->date_crea);

		$template = $this->loadView('album_show_view');
		$template->set('album', $album);
		$template->set('list_pictures', $list_pictures);
		$template->set('isUserAdmin', $isUserAdmin);
		$template->set('isConnected', $isConnected);
		$template->set('right_menu', $right_menu);
		$template->set('alb_day', $alb_day);
		$template->set('alb_month', $alb_month);
		$template->set('alb_year', $alb_year);
		$template->set('alb_date', $alb_date);
		$template->render();

	}

	private function deleteDir($dir)
	{
		foreach(glob($dir . '/*') as $file)
		{
			if(is_dir($file))
			{
				$this->deleteDir($file);
			}
			else
			{
				unlink($file);
			}
		}
		rmdir($dir);
	}

	/**
	 * Rotate the picture counter-clockwise.
	 */
	function rotateLeft($picId)
	{
		if(!$this->isUserAdmin()) {
			$this->redirect('error/401');
		}

		return $this->rotate($picId, false);
	}

	/**
	 * Rotate the picture clockwise.
	 */
	function rotateRight($picId)
	{
		if(!$this->isUserAdmin()) {
			$this->redirect('error/401');
		}

		return $this->rotate($picId, true);
	}

	function rotate($picId, $rotateRight)
	{
		$pictureModel = $this->loadModel('Picture_model');
		$this->loadPlugin('image_plugin');

		$picture = $pictureModel->getPictureById($picId);
		rotateAndSaveImage($picture->url, $rotateRight);
		createThumbnail($picture->url);
		if($picture->cover == 1)
		{
			createAlbumCover($picture->url);
		}

		return $picture->url."?v=".time();
	}

	/**
	 * Delete the picture.
	 */
	function delete($picId)
	{
		if(!$this->isUserAdmin()) {
			$this->redirect('error/401');
		}

		$jsonResult = array();

		$pictureModel = $this->loadModel('Picture_model');
		$picture = $pictureModel->getPictureById($picId);

		$jsonResult['picture'] = $picture;

		if(is_array($picture))
		{
			$jsonResult['error'] = "Error while getting image # ".$picId." - ".$result[0]." - ".$result[1];
		}
		else  if(is_a($picture, 'PictureDTO'))
		{
			$result = $pictureModel->deletePicture($picId);
			$jsonResult['result'] = $result;

			if(is_array($result))
			{
				$jsonResult['error'] = "Error while deleting ".$picture->url." - ".$result[0]." - ".$result[1];
			}
			else if (is_int($result) && $result == 1)
			{
				if (file_exists(PICTURE_PATH.$picture->url)) {
					unlink(PICTURE_PATH.$picture->url);
				}
				if (file_exists(PICTURE_PATH.COVER_DIR.$picture->url)) {
					unlink(PICTURE_PATH.COVER_DIR.$picture->url);
				}
				if (file_exists(PICTURE_PATH.THUMB_DIR.$picture->url)) {
					unlink(PICTURE_PATH.THUMB_DIR.$picture->url);
				}
				$jsonResult['success'] = "Image deleted";
			}
			else
			{
				$jsonResult['error'] = "Unknown error while deleting image ".$picId;
			}
		}
		else
		{
			$jsonResult['error'] = "Unknown error while deleting image ".$picId;
		}

		return json_encode($jsonResult);
	}

	/**
	 * Set the picture as the album cover.
	 */
	function makeCover($albId, $picId)
	{
		if(!$this->isUserAdmin()) {
			$this->redirect('error/401');
		}

		$pictureModel = $this->loadModel('Picture_model');
		$albumModel = $this->loadModel('Album_model');
		$this->loadPlugin('image_plugin');

		$picture = $pictureModel->getPictureById($picId);

		if(is_array($picture))
		{
			$error_messages[] = "Error while getting image # ".$picId." - ".$result[0]." - ".$result[1];
		}
		else if(is_a($picture, 'PictureDTO'))
		{
			$result = $albumModel->removeCoverFromAlbum($albId);

			if(is_array($result))
			{
				$error_messages[] = "Error while removing cover from album # ".$albId." - ".$result[0]." - ".$result[1];
			}
			else
			{
				$result = $albumModel->setCoverToAlbum($albId, $picId);

				if(is_array($result))
				{
					$error_messages[] = "Error while setting cover to album # ".$albId." - ".$result[0]." - ".$result[1];
				}
				else
				{
					createAlbumCover($picture->url);
				}
			}
		}
		else
		{
			$error_messages[] = "Unknown error while setting cover to album # ".$albId;
		}
	}

	function restoreOriginalImage($picId)
	{
		if(!$this->isUserAdmin()) {
			$this->redirect('error/401');
		}

		$pictureModel = $this->loadModel('Picture_model');
		$this->loadPlugin('image_plugin');

		$jsonResult = array();

		$picture = $pictureModel->getPictureById($picId);

		if(is_array($picture))
		{
			$jsonResult['error'] = "Error while getting image # ".$picId." - ".$result[0]." - ".$result[1];
		}
		else if(is_a($picture, 'PictureDTO'))
		{
			$currentFile = PICTURE_PATH.$picture->url;
			$fileAsArray = array();
			preg_match('/(.*\/)(.*)/', $currentFile, $fileAsArray);
			if (sizeof($fileAsArray) == 3) {
				$originalFile = $fileAsArray[1]."orig-".$fileAsArray[2];
				if (file_exists($originalFile)) {
					copy($originalFile, $currentFile);
					createThumbnail($picture->url);
					if($picture->cover == 1) {
						createAlbumCover($picture->url);
					}
					$jsonResult['success'] = $picture->url."?v=".time();
				}
				else {
					$jsonResult['error'] = "Original file (".$originalFile.") not found.";
				}
			}
			else {
				$jsonResult['error'] = "File name doesn't match.";
			}
		}
		else
		{
			$jsonResult['error'] = "Unknown error while setting cover to album # ".$albId;
		}

		return json_encode($jsonResult);
	}

}

