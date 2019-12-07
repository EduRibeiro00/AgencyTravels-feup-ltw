<?php
	include_once('../includes/session_include.php');
	include_once('../templates/tpl_common.php');
	include_once('../templates/tpl_list_houses.php');
	include_once('../database/db_user.php');

	if(isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
		$user_info = getUserInformation($_SESSION['userID']);
		$jsFiles = ['../js/main.js', '../js/filter.js'];
	}
	else {
		$user_info = NULL;
		$jsFiles = ['../js/main.js', '../js/login.js', '../js/filter.js'];
	}

	$places = 
	draw_head($jsFiles);
	draw_navbar($user_info, true);
	list_houses_result($places);
	draw_footer();
?>