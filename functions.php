<?php

// Full-Text RSS
$makefultextfeedURL = 'http://127.0.0.1/XXX/makefulltextfeed.php';

function fetchURL() {
	// Prevent queries being in the wrong order.
	if(strstr($_SERVER['REQUEST_URI'],'v1/clear?format=')) {
		echo 'Check parameter order. URL query must come first.';
		exit;
	}
		
	$url = str_replace('&iframe=false', '', str_replace('&iframe=true', '', str_replace('&format=xml','',str_replace('&format=json','', str_replace('/v1/clear?url=','',$_SERVER['REQUEST_URI'])))));	
	$url = urldecode($url);
	return $url;
}

// extract article contents + clean JS
function fetchContent($url) {
	
	$article = json_decode(file_get_contents($makefultextfeedURL.'?url=&max=1&format=json&url='.urlencode($url)),true);
	

	
	$content = $article['rss']['channel']['item']['description'];
	
	require_once 'cleaner/HTMLPurifier.standalone.php';	
	$purifier = new HTMLPurifier();
    $content = $purifier->purify($content);
    
	    
    require_once('addons/StripAttributes.php');
    $sa = new StripAttributes();
	$sa->exceptions = array(  
	    'iframe' => array( 'class', 'src', 'type' ),  
	    'img' => array( 'src' ),  
	    'a' => array( 'href', 'title' )  
	);  
	$content = $sa->strip($content);  
	
	$array[] = array (
    	"title"  => $article['rss']['channel']['item']['title'],
    	"description" => $content,
    	"link"   => $article['rss']['channel']['item']['link']
	);
	
	return $array;
}

// Convert raw string to JSON
function toJSON($raw) {
	header('Content-type: application/json');
	$raw = unserialize($raw);
	$arr = array (
		'status'  => 'success',
		'item'   => array('title' => trim(htmlspecialchars($raw[0]['title'])),
		'description' => htmlspecialchars(trim($raw[0]['description'])),
		'link' => htmlspecialchars($raw[0]['link'])) 
	);
	//print_r($raw);
	return json_encode($arr);
}

// Convert raw to XML
function toXML($raw) {
	header ("content-type: text/xml");
	$raw = unserialize($raw);
	return '<?xml version="1.0" encoding="UTF-8"?>
		<rss>
			<channel>
				<status>Success</status>
				<item>
					<title>'.trim(htmlspecialchars($raw[0]['title'])).'</title>
					<description>'.htmlspecialchars(trim($raw[0]['description'])).'</description>
					<link>'.htmlspecialchars($raw[0]['link']).'</link>
				</item>
			</channel>
		</rss>';
}

// Handles cache - fetches and makes paths
function fetchPath($url) {
	$urlmd5 = md5($url);
	$folder = 'buffer/'.substr($urlmd5, 0, 3);
	$file = $urlmd5.'.clr';
	return $path = $folder.'/'.$file;
}
function makePath($url) {
	$urlmd5 = md5($url);
	$folder = 'buffer/'.substr($urlmd5, 0, 3);
	$file = $urlmd5.'.clr';
	@mkdir($folder,0755,true);
}

?>