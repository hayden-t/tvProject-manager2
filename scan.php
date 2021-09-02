<pre>
<?php

require_once('getID3/getid3/getid3.php');
require_once 'getID3/getid3/extension.cache.mysqli.php';
$getID3 = new getID3;
$getID3 = new getID3_cached_mysqli('localhost', 'getid3', 'root', '');
$getID3->encoding = 'UTF-8';
$getID3->options_audiovideo_quicktime_ReturnAtomData = false;

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('G:\video\Flipper - Complete TV-series (1964 - 1995)\s01'));
foreach ($iterator as $file) {
	if ($file->isDir()) continue;
	$path = $file->getPathname();
	
	$file_meta = $getID3->analyze($path);	
	//var_dump($file_meta);
	echo $path."<br />";
	ob_flush();
	flush();

}

?>
</pre>