<?php
namespace Moss\component\thumb\filter;

use \Moss\component\thumb\FilterInterface;

class FilmGrain implements FilterInterface {

	protected $grain;
	protected $intensity;

	public function __construct($grain = 0.8, $intensity = 0.75) {
		if($grain < 0 || $grain > 1) {
			throw new \InvalidArgumentException('Grain value must be between 0 and 1');
		}
		$this->grain = (float) $grain;

		if($intensity < 0 || $intensity > 1) {
			throw new \InvalidArgumentException('Intensity value must be between 0 and 1');
		}
		$this->intensity = 1 - (float) $intensity;
	}

	/**
	 * Returns filter identifier
	 *
	 * @return string
	 */
	public function identify() {
		return 'filmgrain';
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

		$grain = imagecreatetruecolor($width, $height);

		$transparency = imagecolorallocatealpha($grain, 255, 0, 255, 127);
		imagecolortransparent($grain, $transparency);
		imagefill($grain, 0, 0, $transparency);

		$grains = array();
		for($i = 0, $c = ($width * $height) * $this->grain; $i < $c; $i++) {
			$grains[mt_rand(0, $width) . 'x' . mt_rand(0, $height)] = true;
		}

		for($i = 0; $i < $width; $i++) {
			for($j = 0; $j < $height; $j++) {
				if($this->intensity !== 0) {
					$image = $this->modifyIntensity($image, $image, $i, $j);
				}

				if(isset($grains[$i . 'x' . $j])) {
					$grain = $this->modifyBrightness($image, $grain, $i, $j);
				}
			}
		}

		imagealphablending($grain, true);
		imagecopyresampled($image, $grain, 0, 0, 0, 0, $width, $height, $width, $height);

		return $image;
	}

	protected function getColor($src, $x, $y) {
		return imagecolorsforindex($src, imagecolorat($src, $x, $y));
	}

	protected function setColor($trg, $x, $y, $rgba) {
		imagesetpixel($trg, $x, $y, imagecolorallocatealpha($trg, $rgba['red'], $rgba['green'], $rgba['blue'], $rgba['alpha']));
		return $trg;
	}

	protected function modifyIntensity($src, $trg, $x, $y) {
		$rgba = $this->getColor($src, $x, $y);

		$max = max($rgba);
		$mod = (255 - $max) * ($max > 100 ? 1 : -1);
		$rgba = $this->normalize($rgba, $mod);
		$rgba['alpha'] = 127 * $this->intensity;

		return $this->setColor($trg, $x, $y, $rgba);
	}

	protected function modifyBrightness($src, $trg, $x, $y) {
		$rgba = $this->getColor($src, $x, $y);

		$mod = mt_rand(-127, 127);
		$rgba = $this->normalize($rgba, $mod);
		$rgba['alpha'] = 64;

		return $this->setColor($trg, $x, $y, $rgba);
	}

	protected function normalize($rgba, $mod) {
		foreach(array('red', 'green', 'blue') as $i) {
			$c = $rgba[$i] + ($rgba[$i] / 255) * $mod;

			if($c < 0) {
				$c = 0;
			}

			if($c > 255) {
				$c = 255;
			}

			$rgba[$i] = (int) $c;
		}

		return $rgba;
	}
}
