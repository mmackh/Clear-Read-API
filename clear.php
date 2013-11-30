<?php

include('functions.php');

if ($_GET['format'] === 'json') {
	$json = true;
}

$url = fetchURL();

if ($url && $_GET['url']) {
	$path = fetchPath($url);	
	if(!file_exists($path)) {

		$raw = fetchContent($url);
		if ($raw) {
			makePath($url);
			file_put_contents($path,serialize($raw));
			if($json) {
				echo toJSON(file_get_contents($path));
			} else {
				echo toXML(file_get_contents($path));
			}
		} else {
			if($json) {
				echo toJSON(file_get_contents(fetchContent($url)));
			} else {
				echo toXML(file_get_contents(fetchContent($url)));
			}
		}
	} else {
		if($json) {
			echo toJSON(file_get_contents($path));
		} else {
			echo toXML(file_get_contents($path));
		}
	}
	//require('donovan/donovan_stats.php');
} else {
	echo 'Check Params';
}


//var_dump(getArticle(fetchURL()));

//echo toJSON(fetchContent(fetchURL()));

//stats counter

?>