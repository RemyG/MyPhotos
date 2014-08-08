<?php

class Main extends Controller {

	function index()
	{

		$right_menu[] = 'home';

		if(!$this->isConnected()) {
			$this->redirect('user/login');
		}

		$isUserAdmin = $this->isUserAdmin();
		$isConnected = $this->isConnected();

		$datePlugin = $this->loadPlugin('date_plugin');
		$stringPlugin = $this->loadPlugin('string_plugin');

		$albumModel = $this->loadModel('Album_model');
		$pictureModel = $this->loadModel('Picture_model');

		$formHelper = $this->loadHelper('Form_helper');

		$alb_title = $formHelper->getCleanValue($_POST, 'alb_title');

		if($this->isUserAdmin() && $_POST && $alb_title != null) {

			$hashname = uniqid(stringNonAlphanumeric($alb_title), true);
			$album = new AlbumDTO(null, $alb_title, time(), $hashname);

			$alb_id = $albumModel->createAlbum($album);

			if (!is_dir(PICTURE_PATH .$alb_id)) {
				mkdir(PICTURE_PATH.$alb_id);
				mkdir(PICTURE_PATH.COVER_DIR.$alb_id, 0775, true);
				mkdir(PICTURE_PATH.THUMB_DIR.$alb_id, 0775, true);
			}

		}

		if($this->isUserAdmin()) {
			$list_albums = $albumModel->getAllAlbums();
		} else {
			$sessionHelper = $this->loadHelper('Session_helper');
			$user_hash = $sessionHelper->get('user_hash');
			$list_albums = $albumModel->getAlbumForUser($user_hash);
		}

		foreach($list_albums as $album) {
			$list_pictures = $pictureModel->getAllPicturesForAlbum($album->id);
			foreach($list_pictures as $picture) {
				if($picture->cover) {
					$album->cover = $picture;
				}
			}
		}

		$template = $this->loadView('main_view');
		$template->set('list_albums', $list_albums);
		$template->set('isUserAdmin', $isUserAdmin);
		$template->set('isConnected', $isConnected);
		$template->set('right_menu', $right_menu);
		$template->render();


	}

}

?>
