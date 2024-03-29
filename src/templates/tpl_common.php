<?php 
function draw_head($jsArray, $class = null) { ?>
  <!DOCTYPE html>
  <html lang="en-US">
	<head>
		<title>Agency Travels</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="../css/style.css">
		<link rel="stylesheet" href="../css/responsive.css">
		<link rel="stylesheet" href="../calendar/css/lightpick.css">
		<link rel="stylesheet" href="../carousel/css/splide.min.css">

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
		<link href="https://fonts.googleapis.com/css?family=Parisienne|Roboto&display=swap" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
		<script src="../calendar/js/lightpick.js"></script>
		<script src="../carousel/js/splide.min.js"></script>

		<?php foreach($jsArray as $jsFile) { ?>
			<script src=<?=$jsFile?> defer></script>
		<?php } ?>
	</head>
	<body <?=$class == null? '' : "class=$class" ?> > 
<?php } ?>

<?php function draw_footer() { ?>
	</div>  <!-- fecha a div body-wrapper -->
    <footer class="row">
			<a class="circ-img-link" href="main_page.php">
				<div class="circular-cropper">
						<img id="logo" src="../assets/images/others/logo.png">
				</div>
			</a>
        <span id="cpline"> &copy; Agency Travels, LTW 2019. All rights reserved. </span>
        <div id="follow" class="row">
			<div class="circular-cropper">
				<img id="github" src="../assets/images/others/github-logo.png">
			</div>
			Follow us:			
			<ul>
                <li><a href="https://github.com/EduRibeiro00">Eduardo Ribeiro</a>
                <li><a href="https://github.com/ManelCoutinho">Manuel Coutinho</a>
                <li><a href="https://github.com/arubenruben">Ruben Almeida</a>
            </ul>
        </div>
    </footer>
  </body>
</html>
<?php } ?>

<?php 
include_once('../templates/tpl_search_form.php');
include_once('../templates/tpl_login_form.php');

function draw_navbar($user_info, $hints, $class = null) { 
	$location = isset($_GET['location']) && validateLocationValue($_GET['location']) ? $_GET['location'] : '' ;?>
    <nav id="navbar" <?=$class == null? '' : "class=$class" ?>>
			<a class="circ-img-link" href="main_page.php">
				<div class="circular-cropper">
					<img id="logo" src="../assets/images/others/logo.png">
				</div>
			</a>
		</div>
		<input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
			<form id="search_form" action="../pages/list_places.php" method="get">
	  			<i class="fas fa-search"></i><input id="nav-search-place" type="text" name="location" pattern="[a-zA-Z -]*" autocomplete="off" placeholder="Search for places in...">
				<section id="search-hints"></section>
				<?php 
					if($hints) {
						draw_search_form();
					}
				?>
      </form>
		<ul class="row">
		<?php if(isset($user_info)) {
			$name = explode(" ", $user_info['name'])[0]; ?>
			
			<a id="housespagelink" href="my_houses.php?userID=<?=$user_info['userID']?>">My Houses</a>
			<a id="reservspagelink" href="my_reservs.php">My Reservations</a>
			<div class="row">
				<a id="link-image" class="circ-img-link" href="profile_page.php?userID=<?=$user_info['userID']?>">
					<img class="circular-img" id="profilepic" src="../assets/images/users/small/<?=$user_info['image']?>">	
				</a>
				<a id="link-name" href="profile_page.php?userID=<?=$user_info['userID']?>"><?=htmlspecialchars($name)?></a>
			</div>
			<a id="logoutlink" href="../actions/action_logout.php">Logout</a>

		<?php } else { ?>
			<a id="loginlink">Login</a>
			<a id="signuplink" href="../pages/signup.php">Signup</a>
		<?php } ?>
		</ul>
		<input type="checkbox" id="hamburger"> 
     	<label class="hamburger" for="hamburger"></label>
	  </nav>
		<?php 
		draw_dialog();
		if($user_info == NULL) draw_login_form(); ?>

		<div id="body-wrapper"> 	<!-- abre a div body-wrapper -->
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
<?php } 

function draw_dialog(){ ?>
	<div id="top-dialog" class="column">
		<i id="login-form-cross"class="close-popup fas fa-times"></i>
		<p></p>
	</div>
<?php } ?>

<?php function draw_confirmation_form() { ?>
	<div id="cf-popup" class="pop-up">
		<form id="cf-confirmation" class="animate">
			<input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

			<i class="close-popup fas fa-times"></i>
			<div class="row">
				<button id="cf-confirm-button" class="button" type="submit">Confirm</button>
				<button id="cf-cancel-button" class="button" type="reset">Cancel</button>
			</div>
		</form>
	</div>
<?php } ?>