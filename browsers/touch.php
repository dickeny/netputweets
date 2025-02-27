<?php
require 'desktop.php';
function touch_theme_status_form($text = '', $in_reply_to_id = NULL) {
	return desktop_theme_status_form($text, $in_reply_to_id, false);
}

function touch_theme_search_form($query) {
	return desktop_theme_search_form($query);
}

function touch_theme_avatar($url, $force_large = false) {
	if (setting_fetch('avataro', 'yes') == 'yes') {
		if (FORCE_SSL == 1) {
			$url = preg_replace("/^http:\/\/[^.]+\.twimg\.com\/(.+)$/i", "https://s3.amazonaws.com/twitter_production/$1", $url);
		}
		return "<img class='shead' src='$url' width='48' height='48' />";
	} else {
		return null;
	}
}

function touch_theme_page($title, $content) {
	$page = ($_GET['page'] == 0 ? null : " - Page ".$_GET['page'])." - ";
	echo '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width; initial-scale=1.0;" /><link href="'.BASE_URF.'favicon.ico" rel="shortcut icon" type="image/x-icon" /><title>'.$title.$page.NPT_TITLE.'</title><base href="'.BASE_URF.'" />'.theme('css').'</head><body id="thepage">'.theme('menu_top').$content.theme('menu_bottom').'</body></html>';
	exit();
}

function touch_theme_menu_top() {
	$links = $main_menu_titles = array();
	if (setting_fetch('tophome', 'yes') == 'yes') $main_menu_titles[] = __("Home");
	if (setting_fetch('topreplies', 'yes') == 'yes') $main_menu_titles[] = __("Replies");
	if (setting_fetch('topretweets', 'yes') == 'yes') $main_menu_titles[] = __("Retweets");
	if (setting_fetch('topdirects', 'yes') == 'yes') $main_menu_titles[] = __("Directs");
	if (setting_fetch('topsearch') == 'yes') $main_menu_titles[] = __("Search");

	foreach (menu_visible_items() as $url => $page) {
		$title = $url ? $page['title'] : __("Home");
		$type = in_array($title, $main_menu_titles) ? 'main' : 'extras';
		$links[$type][] = "<a href='".BASE_URL."$url'>$title</a>";
	}
	if (user_is_authenticated()) {
		$user = user_current_username();
		if (setting_fetch('topuser') == 'yes') array_unshift($links['main'], "<b><a href='".BASE_URL."user/$user'>$user</a></b>");
		array_unshift($links['extras'], "<b><a href='".BASE_URL."user/$user'>$user</a></b>");
	}
	array_push($links['main'], '<a href="#" onclick="return toggleMenu()">'.__('More').'</a>');
	$html = '<div id="menu" class="menu">';
	$html .= theme('list', $links['main'], array('id' => 'menu-main'));
	$html .= theme('list', $links['extras'], array('id' => 'menu-extras'));
	$html .= '</div>';
	return $html;
}

function touch_theme_menu_bottom() {
	return js_counter('status');
}

function touch_theme_css() {
	$out = theme_css().'<link rel="stylesheet" href="'.BASE_URF.'browsers/touch.css" /><script type="text/javascript">'.file_get_contents('browsers/touch.js').'</script>';
	$out .= '<style type="text/css">'.setting_fetch('css').'</style>';
	return $out;
}
?>