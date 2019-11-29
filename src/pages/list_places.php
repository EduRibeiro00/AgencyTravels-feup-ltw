<?php
	include_once('../includes/session_include.php');
	include_once('../templates/tpl_common.php');
	include_once('../templates/tpl_list_houses.php');
	include_once('../database/db_user.php');

	if(isset($_SESSION['userID']) && $_SESSION['userID'] != '') {
		$user_info = getUserInformation($_SESSION['userID']);
		$jsFiles = ['../js/main.js'];
	}
	else {
		$user_info = null;
		$jsFiles = ['../js/main.js', '../js/login.js'];
	}

	draw_head($jsFiles);
	draw_navbar($user_info);
	list_houses_result();
	draw_footer();
?>