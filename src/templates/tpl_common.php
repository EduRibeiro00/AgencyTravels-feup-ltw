<?php function draw_head($class = null) { ?>
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<title>Agency Travels</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="../css/style.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
		<link href="https://fonts.googleapis.com/css?family=Parisienne|Roboto&display=swap" rel="stylesheet"> 
		<script src="../js/main.js" defer></script>
	</head>
	<body <?=$class == null? '' : "class=$class" ?> > 
<?php } ?>

<?php function draw_footer() { ?>
    <footer>
		<div class="circular-cropper img-size-60">
			<img id="logo" src="http://www.berkanacompany.com/wp-content/uploads/2014/05/logo-placeholder-300x200.jpg">
		</div>
        <span id="cpline"> &copy; Agency Travels, LTW 2019. All rights reserved. </span>
        <div id="follow">
			Follow us:
			<div class="circular-cropper img-size-60">
				<img id="github" src="https://cdn0.tnwcdn.com/wp-content/blogs.dir/1/files/2016/11/github-image-796x418.png">
			</div>
            <ul>
                <li><a href="https://github.com/EduRibeiro00">Eduardo Ribeiro</a>
                <li><a href="https://github.com/arubenruben">Manuel Coutinho</a>
                <li><a href="https://github.com/ManelCoutinho">Ruben Almeida</a>
            </ul>
        </div>
    </footer>
  </body>
</html>
<?php } ?>

<?php 
include_once('../templates/tpl_search_form.php');
include_once('../templates/tpl_login_form.php');

function draw_navbar($class = null) { ?>
    <nav id="navbar" <?=$class == null? '' : "class=$class" ?>>
		<a id="mainpagelink" href="main_page.php">
			<div class="circular-cropper img-size-60" id="logo-cropper">
				<img id="logo" src="http://www.berkanacompany.com/wp-content/uploads/2014/05/logo-placeholder-300x200.jpg">
			</div>
		</a>
        <form id="search_form" action="../actions/action_search.php" method="get">
			<i class="fas fa-search"></i><input type="text" name="location" autocomplete="off" placeholder="Search for places in...">
			<!-- TODO: ver section-->
			<section id="search-hints"></section>
			<?php 
				draw_search_form();
			?>
        </form>
        <a id="housespagelink" href="my_houses.php">My Houses</a>
		<a id="reservspagelink" href="my_reserves.php">My Reservations</a>
		<a id="profilepagelink" href="profile.php">
			<div class="circular-cropper img-size-60" id="profile-cropper">	
				<img id="profilepic" src="https://ligaportuguesa.pt/wp-content/uploads/2019/03/marega.jpg">	
			</div>Moussa
		</a>
		<!-- TODO: assim para testar popup -->
		<a id="loginlink" href="#">Login</a>
	  </nav>
	  <?php draw_login_form(); ?>
<?php } ?>

<?php function draw_star_rating($rating) { ?>
	<div class="star-rating">
		<div class="back-stars row">
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
		
			<div class="front-stars row" style="width:<?=($rating * 20.0)?>%">
				<i class="fas fa-star"></i>
				<i class="fas fa-star"></i>
				<i class="fas fa-star"></i>
				<i class="fas fa-star"></i>
				<i class="fas fa-star"></i>
			</div>
		</div>
	</div>
<?php } ?>
