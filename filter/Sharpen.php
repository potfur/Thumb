<?php
namespace Moss\component\thumb\filter;

use \Moss\component\thumb\FilterInterface;

class Sharpen implements FilterInterface {

	protected $sharpenMatrix = array(array(-1, -1, -1), array(-1, 16, -1), array(-1, -1, -1));

	public function __construct($sharpenMatrix = array()) {
		if(empty($sharpenMatrix)) {
			return;
		}

		$this->sharpenMatrix = $sharpenMatrix;
	}

	/**
	 * Returns filter identifier
	 *
	 * @return string
	 */
	public function identify() {
		return 'sharpen';
	}


	/**
	 * Applies filter on image
	 *
	 * @param \resource $image
	 *
	 * @return \resource
	 */
	public function apply($image) {
		imageconvolution($image, $this->sharpenMatrix, 8, 0);

		return $image;
	}

}
