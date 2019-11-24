<?php
	include_once('../templates/tpl_common.php');
	include_once('../templates/tpl_list_houses.php');
  
	draw_head();
	draw_navbar();

	list_houses_result();

	draw_footer();
?>