<?php

class Error extends Controller {

	function index()
	{
		$this->error_404();
	}

	function error_404()
	{
		echo '<h1>404 Error</h1>';
		echo '<p>Looks like this page doesn\'t exist</p>';
	}

	function error_401() {
		$template = $this->loadView('error_401_view');
		$template->render();
	}

}

?>
