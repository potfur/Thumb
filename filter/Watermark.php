<?php
namespace Moss\component\thumb\filter;

use \Moss\component\thumb\FilterInterface;

class Watermark implements FilterInterface {

	protected $file;
	protected $posX;
	protected $posY;

	/**
	 * @param string $file path to watermark image
	 * @param int    $posX horizontal watermark position - positive value - from left edge, negative - from right
	 * @param int    $poxY vertical watermark position - positive value - from top edge, negative - from bottom
	 */
	public function __construct($file, $posX = 0, $poxY = 0) {
		$this->file = $file;
		$this->posX = (int) $posX;
		$this->posY = (int) $poxY;
	}

	/**
	 * Returns filter identifier
	 *
	 * @return string
	 */
	public function identify() {
		return 'watermark';
	}


	/**
	 * Applies filter on image
	 *
	 * @param \resource $image
	 *
	 * @return \resource
	 */
	public function apply($image) {
		try {
			$watermark = $this->readImage($this->file);
		}
		catch(\InvalidArgumentException $e) {
			return $image;
		}

		$x = $this->posX < 0 ? imagesx($image) - imagesx($watermark) + $this->posX : $this->posX;
		$y = $this->posY < 0 ? imagesy($image) - imagesy($watermark) + $this->posY : $this->posY;

		imagecopyresampled($image, $watermark, $x, $y, 0, 0, imagesx($watermark), imagesy($watermark), imagesx($watermark), imagesy($watermark));

		return $image;
	}

	/**
	 * Reads image
	 *
	 * @param string      $image path to image
	 * @param null|string $mime  image MIME
	 *
	 * @return \resource
	 * @throws \InvalidArgumentException
	 */
	protected function readImage($image, $mime = null) {
		if(!$mime) {
			$info = getimagesize($image);
			$mime = $info['mime'];
		}
		switch($mime) {
			case "image/jpeg":
			case "image/jpg":
				$srcImg = imagecreatefromjpeg($image);
				break;
			case "image/gif":
				$srcImg = imagecreatefromgif($image);
				break;
			case "image/png":
				$srcImg = imagecreatefrompng($image);
				break;
		}

		if(!isset($srcImg)) {
			throw new \InvalidArgumentException('Source image could not be set');
		}

		imagealphablending($srcImg, true);

		return $srcImg;
	}

}
