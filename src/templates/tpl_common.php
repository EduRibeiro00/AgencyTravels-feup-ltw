<?php function draw_head() { ?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <title>Agency Travels</title>
    <meta charset="UTF-8">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
  </head>
  <body>
<?php } ?>

<?php function draw_footer() { ?>
    <footer>
        <p>&copy; Agency Travels, LTW 2019. All rights reserved.</p>
        <img src="https://cdn.worldvectorlogo.com/logos/airbnb-2.svg">
        <div id="follow">
            <p>Follow us:</p>
            <img src="https://cdn0.tnwcdn.com/wp-content/blogs.dir/1/files/2016/11/github-image-796x418.png">
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

<?php function draw_navbar() { ?>
    <nav id="links">
		<a href="main_page.php"><img src="https://cdn.worldvectorlogo.com/logos/airbnb-2.svg"></a>
		<a href="search_page.php">Search</a>
		<a href="my_houses.php">My Houses</a>
		<a href="my_reserves.php">My Reservations</a>
		<div id="account">
			<a href="profile.php">
				<img id="profilepic"src="https://ligaportuguesa.pt/wp-content/uploads/2019/03/marega.jpg">
				Moussa
			</a>
			</div>
		<a href="action_logout.php">Logout</a>
	</nav> 
<?php } ?>

<?php function draw_search_button() { ?>
    <button class="btn"><i class="fas fa-search"></i> Search Place to Stay</button>
<?php } ?>