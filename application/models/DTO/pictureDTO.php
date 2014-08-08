<?php

class PictureDTO
{
	public $id;
	public $url;
	public $alb_id;
	public $cover;
	public $desc;

	public function __construct($id, $url, $alb_id, $cover = 0, $desc = "") {
		$this->id = $id;
		$this->url= $url;
		$this->alb_id = $alb_id;
		$this->cover = $cover;
		$this->desc = $desc;
	}
}