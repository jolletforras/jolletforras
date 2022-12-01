<?php

function justbr($text,$length)
{
    $text = strip_tags($text,"<p>");
    $text = str_replace("<p>", "",$text);
    $text = str_replace("</p>", "Ł",$text);
    $text = mb_substr($text,0,$length);
    $text = str_replace("Ł", "<br/>",$text);

    return $text;
}


function addhttp($url)
{
	return is_numeric(strpos($url,'http')) || empty($url) ?  $url : 'http://'.$url;
}


function constx($constant)
{
	return config('constants.'.$constant);
}


function NewSizeMaxWidth($width, $hight, $maxwidth, &$new_width, &$new_hight)
{
	if ($width <= $maxwidth)
	{
		$new_width = $width;
		$new_hight = $hight;
	}
	else
	{
		$new_width = $maxwidth;
		$new_hight = $hight*$maxwidth/$width;
	}
}

function NewSizeMaxHight($width, $hight, $maxhight, &$new_width, &$new_hight)
{
	if ($hight <= $maxhight)
	{
		$new_width = $width;
		$new_hight = $hight;
	}
	else
	{
		$new_width =  $width * $maxhight / $hight;
		$new_hight = $maxhight;
	}
}

/**
* scaling on base
* in case mode=0 the bigger side
* in case mode=1 the width
* in case mode=2 the height
*/
function generateImage ($imagefile, $maxsize, $mode, $newfilename)
{
	if (!file_exists($imagefile))
		return false;

		list($width, $hight, $type) = getimagesize($imagefile);

		if ( ($mode==0 && $width>$hight) || $mode==1) {
			NewSizeMaxWidth($width, $hight, $maxsize, $new_width, $new_hight);
		}
		else {
			NewSizeMaxHight($width, $hight, $maxsize, $new_width, $new_hight);
		}

		switch ($type)
		{
			case 1:
				$image = imagecreatefromgif($imagefile);
				break;
			case 2:
				$image = imageCreateFromJpeg($imagefile);
				break;
			case 3:
				$image = imagecreatefrompng($imagefile);
				break;
		}

		$newimage = imagecreatetruecolor ($new_width, $new_hight);
		imagecopyresampled ($newimage, $image, 0, 0, 0, 0, $new_width, $new_hight, $width, $hight); // generates the new image
		imagejpeg ($newimage, $newfilename, 90); // generate a (90% quality) jpeg image and save it on the given name
}
