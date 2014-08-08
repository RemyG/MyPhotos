<?php

class User extends Controller {

	function login($userhash = null)
	{

		$sessionHelper = $this->loadHelper('Session_helper');
		$formHelper = $this->loadHelper('Form_helper');

		$userModel = $this->loadModel('User_model');

		$isUserAdmin = $this->isUserAdmin();

		if($userhash == 'out') {

			$sessionHelper->destroy();
			$this->redirect('');

		}

		if($_POST && key_exists('hash', $_POST)) {
			$userhash = $formHelper->getCleanValue($_POST, "hash");
		}
		$password = $formHelper->getCleanValue($_POST, 'password');

		if(!$userhash == '') {

			$this->loadPlugin('string_plugin');

			if(endsWith($userhash, ".html")) {
				$userhash = substr($userhash, 0, -5);
			}

			$user = $userModel->getUserByHashPassword($userhash, $password == "" ? "" : sha1($password));

			if($user != null) {

				$sessionHelper->set('user_id', $user->id);
				$sessionHelper->set('user_hash', $user->hash);
				$sessionHelper->set('user_desc', $user->desc);

				$this->redirect('');

			} else {

				$info_message[] = 'Please enter your password.';

			}

		}

		$isConnected = $this->isConnected();

		$template = $this->loadView('user_login_view');
		$template->set('userhash', $userhash);
		$template->set('isUserAdmin', $isUserAdmin);
		$template->set('isConnected', $isConnected);
		$template->render();
	}

}