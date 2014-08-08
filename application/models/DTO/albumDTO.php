<?php

class AlbumDTO
{
	public $id;
	public $title;
	public $date_crea;
	public $hashname;

	public $list_pictures;
	public $cover;

	public function __construct($id, $title, $date_crea, $hashname) {
		$this->id = $id;
		$this->title = $title;
		$this->date_crea = $date_crea;
		$this->hashname = $hashname;
	}
}