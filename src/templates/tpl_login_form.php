
<?php function draw_login_form() { ?>
	<!-- TODO: depois ver id por causa de js -->
	<div id="pop-up">
		<form id="login-form" class="column" action="../actions/actions_login.php" method="post">
			
			<div class="circular-cropper img-size-10" id="logo-cropper">
				<img id="logo" src="http://www.berkanacompany.com/wp-content/uploads/2014/05/logo-placeholder-300x200.jpg">
			</div>
			<i class="close-popup fas fa-times"></i>

			<label for="email">Email</label>
			<input type="email" placeholder="Enter Email" name="email" required>

			<label for="password">Password</label>
			<input type="password" placeholder="Enter Password" name="password" required>

			<button class="button" type="submit">Login</button>
		</form>
	</div>
<?php } ?>
