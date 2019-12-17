
<?php function draw_login_form() { ?>
	<div id="login-popup" class="pop-up">
		<form id="login-form" class="column animate">
			
			<input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

			<div class="circular-cropper">
				<img id="logo-login" src="../assets/images/others/logo.png">
			</div>

			<i id="login-form-cross-login"class="close-popup fas fa-times"></i>

			<p id="login-message" class="error-message"></p>

			<label for="username">Username</label>
			<input type="text" placeholder="Enter Username" name="username" required>

			<label for="password">Password</label>
			<input type="password" placeholder="Enter Password" name="password" required>

			<button id="login-button" class="button" type="submit">Login</button>
		</form>
	</div>
<?php } ?>
