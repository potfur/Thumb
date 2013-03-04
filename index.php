<?php
error_reporting(E_ALL);

require './Thumb.php';
require './FilterInterface.php';
require './filter/Sharpen.php';
require './filter/Watermark.php';
require './filter/Grayscale.php';
require './filter/FilmGrain.php';

$filters = array(
	'sharpen',
	'grayscale',
	'filmgrain',
	'watermark',
);

$Thumb = new \Moss\component\thumb\Thumb('./');
$Thumb->registerFilter(new \Moss\component\thumb\filter\Sharpen());
$Thumb->registerFilter(new \Moss\component\thumb\filter\Watermark('./icon.png', -10, -10));
$Thumb->registerFilter(new \Moss\component\thumb\filter\Grayscale());
$Thumb->registerFilter(new \Moss\component\thumb\filter\FilmGrain());

header('Content-type: '.$Thumb->thumbnailHeader('./test.jpg'));
echo $Thumb->make('./test.jpg', 320, null, false, $filters, false);