<?php function draw_header() { ?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <title>Agency Travels</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <header>
      <nav id="links">
        <a href="main_page.php"><img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fworldvectorlogo.com%2Flogo%2Fairbnb-2&psig=AOvVaw1-OAAJpAY-VJEWjexfQibk&ust=1573161312640000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCIjt3tzA1uUCFQAAAAAdAAAAABAD"></a>
        <a href="search_page.php">Search</a>
        <a href="my_houses.php">My Houses</a>
        <a href="my_reserves.php">My Reservations</a>
        <div id="account">
            <a href="profile.php">
                <img id="profilepic"src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fligaportuguesa.pt%2Ffuturo-de-marega-esta-decidido%2F&psig=AOvVaw31DNJkrWRmeTPDmfjC6jU5&ust=1573162333088000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCPi90cPE1uUCFQAAAAAdAAAAABAD">
                Moussa
            </a>
        </div>
        <a href="action_logout.php">Logout</a>
      </nav> 
    </header>
<?php } ?>

<?php function draw_footer() { ?>
    <footer>
        <p>&copy; Agency Travels, LTW 2019. All rights reserved.</p>
        <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fworldvectorlogo.com%2Flogo%2Fairbnb-2&psig=AOvVaw1-OAAJpAY-VJEWjexfQibk&ust=1573161312640000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCIjt3tzA1uUCFQAAAAAdAAAAABAD">
        <div id="follow">
            <p>Follow us:</p>
            <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fthenextweb.com%2Fdd%2F2019%2F01%2F05%2Fgithub-now-gives-free-users-unlimited-private-repositories%2F&psig=AOvVaw2vHc6VHna54bgX-HbKBbQs&ust=1573165025698000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCKDhx8jO1uUCFQAAAAAdAAAAABAK">
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