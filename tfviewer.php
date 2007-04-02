<?php
/*
Plugin Name: ThinkFree Viewer
Plugin URI: http://viewer.thinkfree.com/
Description: The plugin allows users to view office(.doc, .xls and .ppt) documents on browsers.
Author: support@thinkfree.com
Version: 1.1
Author URI: http://thinkfree.com
License : http://viewer.thinkfree.com/tou.html
*/ 

$resource_base = 'http://viewer.thinkfree.com/';
$server_base = 'http://viewer.thinkfree.com/';
$view_base = $server_base.'html?';

// detect office file links and modify links
function convert_office_files($text) {
	$pattern = '/(<a.*href\s*=\s*[\'"])(.*?)([\'"].*a>)/i';
	$text = preg_replace_callback($pattern, 'modify_link', $text);
	return $text;
}

function modify_link($match) {
	// $match is result of pattern for anchor tag
	global $server_base, $view_base;
	$org_url = $match[2];
	// check extensions for office documents
	$pattern = '/(rtf|doc|xls|ppt)$/i';
	if(preg_match($pattern, $org_url, $matches)) {
		// encoded document url
		$e_doc_url = urlencode( $org_url );
		$viewurl = $view_base.'url='.$e_doc_url.'&action=view';
		$img_tag = '<img src="http://viewer.thinkfree.com/images/view.gif" style="cursor:pointer" onclick="'."showWindow('".$viewurl."');".'"/>';
		return $match[0].' '.$img_tag;
	} else {
		// return unchanged
		return $match[0];
	}
	return $match[1].$org_url.$match[3];
}

// callback for action 'wp_head'
function prepareThinkFree($unused) {
	global $resource_base;
	echo '<link href="'.$resource_base.'theme/tf.css" rel="stylesheet" type="text/css" />';
	echo '<script type="text/javascript" src="'.$resource_base.'js/prototype.js"></script>';
	echo '<script type="text/javascript" src="'.$resource_base.'js/window.js"></script>';
	echo '<script type="text/javascript" src="'.$resource_base.'js/viewer.js"></script>';
}

// add css, javascript declaration
add_action('wp_head', 'prepareThinkFree');
add_filter('the_content', 'convert_office_files');
?>
