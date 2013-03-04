<?php
namespace Moss\component\thumb\filter;

use \Moss\component\thumb\FilterInterface;

class Grayscale implements FilterInterface {

	protected $contrast = 0;
	protected $brightness = 0;

	public function __construct($contrast = -25, $brightness = 20) {
		$this->contrast = (int) $contrast;
		$this->brightness = (int) $brightness;
	}

	/**
	 * Returns filter identifier
	 *
	 * @return string
	 */
	public function identify() {
		return 'grayscale';
	}


	/**
	 * Applies filter on image
	 *
	 * @param \resource $image
	 *
	 * @return \resource
	 */
	public function apply($image) {
		$width = imagesx($image);
		$height = imagesy($image);
		$tmp = imagecreatetruecolor($width, $height);

		imagefilter($image, IMG_FILTER_GRAYSCALE);
		imagefilter($image, IMG_FILTER_BRIGHTNESS, $this->brightness);
		imagefilter($image, IMG_FILTER_CONTRAST, $this->contrast);

		imagecopyresampled($tmp, $image, 0, 0, 0, 0, $width, $height, $width, $height);

		return $tmp;
	}

}
