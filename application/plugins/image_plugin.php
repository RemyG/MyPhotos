<?php

/**
 * Rotate an image (clockwise if rotate_right is true, counter-clockwise if it's false).
 *
 * @return The path to the image.
 */
function rotateAndSaveImage($url, $rotate_right)
{
	$source = PICTURE_PATH.$url;
	$image = loadImage($source);
	$image = imagerotate($image, $rotate_right ? 270 : 90, 0);
	saveImage($image, $source, 95);
	return $source;
}

function insertTimeToImageUrl($url)
{
	$url_split = explode(".", $url);
	$newurl = "";
	if(count($url_split) > 2) {
		for($i = 0 ; $i < count($url_split) - 2 ; $i++) {
			$newurl .= $url_split[$i].".";
		}
		$newurl .= time().".".$url_split[count($url_split) - 1];
	} else if(count($url_split) == 2) {
		$newurl = $url_split[0];
		$newurl .= ".".time().".".$url_split[1];
	} else {
		$newurl = $url;
	}
	return $newurl;
}

function createAlbumCover($url)
{
	resizeImageToSize($url, COVER_HEIGHT, COVER_WIDTH, COVER_DIR);
}

function createThumbnail($url)
{
	resizeImageToSize($url, THUMB_HEIGHT, THUMB_WIDTH, THUMB_DIR);
}

function loadImage($source)
{
	$extension = explode(".", $source);
	$extension = $extension[sizeof($extension) - 1];
	$extension = strtolower($extension);

	switch($extension)
	{
		case 'jpg':
		case 'jpeg':
			return imagecreatefromjpeg($source);
		case 'png':
			return imagecreatefrompng($source);
	}
}

function saveImage($image, $url, $quality)
{
	$extension = explode(".", $url);
	$extension = $extension[sizeof($extension) - 1];
	$extension = strtolower($extension);

	switch($extension)
	{
		case 'jpg':
		case 'jpeg':
			imagejpeg($image, $url, $quality);
			break;
		case 'png':
			imagepng($image, $url, 9);
			break;
	}
}


function resizeImageToSize($url, $thumbh, $thumbw, $destDir)
{
	$source = PICTURE_PATH.$url;

	$newname = "thumb-". $oldname;

	// Dimension of intermediate thumbnail

	$nh = $thumbh;
	$nw = $thumbw;

	$size = getImageSize($source);
	$w = $size[0];
	$h = $size[1];

	// Applying calculations to dimensions of the image

	$ratio = $h / $w;
	$nratio = $nh / $nw;

	if($ratio > $nratio)
	{
		$x = intval($w * $nh / $h);
		if ($x < $nw)
		{
			$nh = intval($h * $nw / $w);
		}
		else
		{
			$nw = $x;
		}
	}
	else
	{
		$x = intval($h * $nw / $w);
		if ($x < $nh)
		{
			$nw = intval($w * $nh / $h);
		}
		else
		{
			$nh = $x;
		}
	}

	// Building the intermediate resized thumbnail

	$resimage = loadImage($source);
	$newimage = imagecreatetruecolor($nw, $nh);  // use alternate function if not installed
	imageCopyResampled($newimage, $resimage, 0, 0, 0, 0, $nw, $nh, $w, $h);

	// Making the final cropped thumbnail

	$viewimage = imagecreatetruecolor($thumbw, $thumbh);
	imagecopy($viewimage, $newimage, 0, 0, ($nw / 2 - $thumbw / 2), ($nh / 2 - $thumbh / 2), $nw, $nh);

	// Output
	saveImage($viewimage, PICTURE_PATH.$destDir.$url, 95);
}