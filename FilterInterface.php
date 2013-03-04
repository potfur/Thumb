<?php
namespace Moss\component\thumb;

interface FilterInterface {


	/**
	 * Returns filter identifier
	 *
	 * @return string
	 */
	public function identify();

	/**
	 * Applies filter on image
	 *
	 * @param \resource $image
	 *
	 * @return \resource
	 */
	public function apply($image);
}
