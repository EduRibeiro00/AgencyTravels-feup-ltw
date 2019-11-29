
<?php function draw_login_form() { ?>
	<div id="pop-up">
		<form id="login-form" class="column">
			
			<div class="circular-cropper">
				<img id="logo-login" src="http://www.berkanacompany.com/wp-content/uploads/2014/05/logo-placeholder-300x200.jpg">
			</div>

			<i id="login-form-cross"class="close-popup fas fa-times"></i>

			<p id="login-message" class="error-message"></p>

			<label for="username">Username</label>
			<input type="text" placeholder="Enter Username" name="username" required>

			<label for="password">Password</label>
			<input type="password" placeholder="Enter Password" name="password" required>

			<button id="submit-button" class="button" type="submit">Login</button>
		</form>
	</div>
<?php } ?>
