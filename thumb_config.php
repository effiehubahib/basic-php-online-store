<?php

$ServerInfo['gd_string']  = 'unknown';
$ServerInfo['gd_numeric'] = 0;
//ob_start();
if (!include_once('lib/phpThumb/phpthumb.functions.php')) {
	//ob_end_flush();
	die('failed to include_once("lib/phpThumb/phpthumb.functions.php")');
}
if (!include_once('lib/phpThumb/phpthumb.class.php')) {
	//ob_end_flush();
	die('failed to include_once("lib/phpThumb/phpthumb.class.php")');
}
//ob_end_clean();
$phpThumb = new phpThumb();
if (include_once('lib/phpThumb/phpThumb.config.php')) {
	foreach ($PHPTHUMB_CONFIG as $key => $value) {
		$keyname = 'config_'.$key;
		$phpThumb->setParameter($keyname, $value);
	}
}
$ServerInfo['phpthumb_version'] = $phpThumb->phpthumb_version;
$ServerInfo['im_version']       = $phpThumb->ImageMagickVersion();;
$ServerInfo['gd_string']        = phpthumb_functions::gd_version(true);
$ServerInfo['gd_numeric']       = phpthumb_functions::gd_version(false);
unset($phpThumb);
?>