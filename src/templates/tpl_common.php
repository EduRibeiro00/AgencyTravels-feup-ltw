<?php 
function draw_head($jsArray, $class = null) { ?>
  <!DOCTYPE html>
  <html lang="en-US">
	<head>
		<title>Agency Travels</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="../css/style.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
		<link href="https://fonts.googleapis.com/css?family=Parisienne|Roboto&display=swap" rel="stylesheet"> 
		<?php foreach($jsArray as $jsFile) { ?>
			<script src=<?=$jsFile?> defer></script>
		<?php } ?>
	</head>
	<body <?=$class == null? '' : "class=$class" ?> > 
<?php } ?>

<?php function draw_footer() { ?>
    <footer>
			<a class="circ-img-link" href="main_page.php">
				<div class="circular-cropper">
						<img id="logo" src="../assets/images/others/logo.jpg">
				</div>
			</a>
        <span id="cpline"> &copy; Agency Travels, LTW 2019. All rights reserved. </span>
        <div id="follow">
			Follow us:
				<div class="circular-cropper">
					<img id="github" src="../assets/images/others/github-logo.png">
				</div>		
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

function draw_navbar($user_info, $hints, $class = null) { ?>
    <nav id="navbar" <?=$class == null? '' : "class=$class" ?>>
			<a class="circ-img-link" href="main_page.php">
				<div class="circular-cropper">
					<img id="logo" src="../assets/images/others/logo.jpg">
				</div>
			</a>
		</div>
      <form id="search_form" action="../pages/list_places.php" method="get">
				<i class="fas fa-search"></i><input type="text" name="location" autocomplete="off" placeholder="Search for places in...">
				<section id="search-hints"></section>
				<?php 
					if($hints) {
						draw_search_form();
					}
				?>
      </form>
		
		<?php if(isset($user_info)) {
			$name = explode(" ", $user_info['name'])[0]; ?>
			
			<a id="housespagelink" href="my_houses.php?userID=<?=$user_info['userID']?>">My Houses</a>
			<a id="reservspagelink" href="my_reservs.php">My Reservations</a>
			<a id="link-image" class="circ-img-link" href="profile_page.php?userID=<?=$user_info['userID']?>">
				<img class="circular-img" id="profilepic" src="../assets/images/users/small/<?=$user_info['image']?>">	
			</a>
			<a id="link-name" href="profile_page.php?userID=<?=$user_info['userID']?>"><?=$name?></a>
			<a id="logoutlink" href="../actions/action_logout.php">Logout</a>

		<?php } else { ?>
			<a id="loginlink">Login</a>
			<a id="signuplink" href="../pages/signup.php">Signup</a>
		<?php } ?>
	  </nav>
		<?php if($user_info == NULL) {
						draw_login_form();
		 } ?>
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