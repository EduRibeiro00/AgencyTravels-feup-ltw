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
		<div class="circular-cropper">
			<img id="logo" src="http://www.berkanacompany.com/wp-content/uploads/2014/05/logo-placeholder-300x200.jpg">
		</div>
        <span id="cpline"> &copy; Agency Travels, LTW 2019. All rights reserved. </span>
        <div id="follow">
			Follow us:
			<div class="circular-cropper">
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

<?php function draw_navbar($class = null) { ?>
    <nav id="navbar" <?=$class == null? '' : "class=$class" ?>>
		<a id="mainpagelink" href="main_page.php">
			<div class="circular-cropper" id="logo-cropper">
				<img id="logo" src="http://www.berkanacompany.com/wp-content/uploads/2014/05/logo-placeholder-300x200.jpg">
			</div>
		</a>
        <form id="search_form" action="../actions/action_search.php" method="get">
			<i class="fas fa-search"></i><input type="text" name="search_value" placeholder="Search for places in...">
			<div id="filters">
				<fieldset id="dates_field">
					<h4>Dates</h4>
					<!-- TODO: mudar para current date mais 1 depois -->
					<label for="checkin">Check In
						<input type="date" id="checkin" name="checkin" min="2019-11-23">
					</label>
					<label for="checkout">Check Out
						<input type="date" id="checkout" name="checkout">
					</label>
				</fieldset>
				<fieldset id="guests_field">
					<h4>Guests</h4>
					<label for="adults">Adults
						<input type="number" id="adults" name="nAdults" min=1 value=1>
					</label>
					<label for="children">Children
						<input type="number" id="children" name="nChildren" min=0 value=0>
					</label>
				</fieldset>
				<fieldset id="price_field">
					<h4>Price</h4>
					<!-- TODO: min < max -->
					<label for="MinPrice">Min Price
						<input type="number" id="MinPrice" name="minPrice" value=0 min=0>
					</label>
					<label for="MaxPrice">Max Price
					<!-- TODO: por 1000 as 1000++ -->
						<input type="number" id="MaxPrice" name="maxPrice" value=1000 max=1000>
					</label>
				</fieldset>
				<fieldset id="rating_field">
					<h4>Rating</h4>
					<label for="5stars">
						<input type = "radio" name = "rating" valor = "5stars" id = "5stars">
					<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> 5</label>
					<label for="4stars">
						<input type = "radio" name = "rating" valor = "4stars" id = "4stars">
					<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i> 4+</label>
					<label for="3stars">
						<input type = "radio" name = "rating" valor = "3stars" id = "3stars">
					<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i> 3+</label>
					<label for="2stars">
						<input type = "radio" name = "rating" valor = "2stars" id = "2stars">
					<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i> 2+</label>
					<label for="1stars">
						<input type = "radio" name = "rating" valor = "1stars" id = "1stars">
					<i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i> 1+</label>
				</fieldset>
				<fieldset id="facilities_field">
					<h4>Facilities</h4>
					<label for="rooms">Rooms
						<input type="number" id="rooms" name="nRooms" min=0 value=0>
					</label>
					<label for="bathrooms">Bathooms
						<input type="number" id="bathrooms" name="nBathrooms" min=0 value=0>
					</label>
				</fieldset>
				<input type="submit" value="Search">
			</div>
        </form>
        <a id="housespagelink" href="my_houses.php">My Houses</a>
		<a id="reservspagelink" href="my_reserves.php">My Reservations</a>
		<a id="profilepagelink" href="profile.php">
			<div class="circular-cropper" id="profile-cropper">	
				<img id="profilepic" src="https://ligaportuguesa.pt/wp-content/uploads/2019/03/marega.jpg">	
			</div>Moussa
		</a>
		<a id="logoutlink" href="action_logout.php">Logout</a>
      </nav>
<?php } ?>