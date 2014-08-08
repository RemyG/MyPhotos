<?php

class UserDTO
{
	public $id;
	public $hash;
	public $password;
	public $desc;
	public $admin;

	public function __construct($id, $hash, $password, $desc = "", $admin = 0) {
		$this->id = $id;
		$this->hash = $hash;
		$this->password = $password;
		$this->desc = $desc;
		$this->admin = $admin;
	}
}