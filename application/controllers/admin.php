<?php

class Admin extends Controller {

	function index()
	{

		$userModel = $this->loadModel('User_model');

		$isUserAdmin = $this->isUserAdmin();

		if(!$isUserAdmin) {
			$this->redirect('error/401');
		}

		$formHelper = $this->loadHelper('Form_helper');

		if($_POST && key_exists('createUser', $_POST)) {

			$user_name = $formHelper->getCleanValue($_POST, 'user_name');
			if($user_name && $user_name != '') {
				$userhash = $user_name;
			} else {
				$userhash = uniqid("", true);
			}
			$user = new UserDTO(null, $userhash, "", "");
			$userModel->createUser($user);

		}

		$list_users = $userModel->getAllUsers();

		$isConnected = $this->isConnected();

		$template = $this->loadView('admin_index_view');
		$template->set('list_users', $list_users);
		$template->set('isUserAdmin', $isUserAdmin);
		$template->set('isConnected', $isConnected);
		$template->render();

	}

	function user($userhash) {

		$userModel = $this->loadModel('User_model');
		$albumModel = $this->loadModel('Album_model');

		$isUserAdmin = $this->isUserAdmin();

		if(!$isUserAdmin) {
			$this->redirect('error/401');
		}

		$formHelper = $this->loadHelper('Form_helper');

		$user = $userModel->getUserByHash($userhash);

		if($_POST && key_exists('albums', $_POST)) {

			$albums_to_add = $formHelper->getCleanValue($_POST, 'albums');

			$albumModel->clearAlbumsForUser($user->id);

			foreach($albums_to_add as $alb_id) {

				$albumModel->addAlbumToUser($alb_id, $user->id);

			}
		}

		if($_POST && key_exists('updateDesc', $_POST)) {

			$desc = $formHelper->getCleanValue($_POST, 'desc');
			$user->desc = $desc;
			$userModel->updateUser($user);

		}

		if($_POST && key_exists('updatePassword', $_POST)) {

			$password = $formHelper->getCleanValue($_POST, 'password');
			$password2 = $formHelper->getCleanValue($_POST, 'password2');
			if($password == $password2) {
				$user->password = sha1($password);
				$userModel->updateUser($user);
			}

		}

		if($_POST && key_exists('updateAdmin', $_POST)) {

			if(key_exists('setAdmin', $_POST)) {
				$user->admin = 1;
				$userModel->updateUser($user);
			} else {
				$user->admin = 0;
				$userModel->updateUser($user);
			}

		}

		$user_albums = $albumModel->getAlbumForUser($user->hash);

		$user_albums_id = array();

		foreach($user_albums as $album) {
			$user_albums_id[] = $album->id;
		}

		$list_albums = $albumModel->getAllAlbums();

		$isConnected = $this->isConnected();

		$template = $this->loadView('admin_user_view');
		$template->set('user', $user);
		$template->set('list_albums', $list_albums);
		$template->set('isUserAdmin', $isUserAdmin);
		$template->set('isConnected', $isConnected);
		$template->set('user_albums_id', $user_albums_id);
		$template->render();

	}

}