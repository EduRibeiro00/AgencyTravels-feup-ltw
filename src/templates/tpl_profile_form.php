<?php

function draw_profile_form($all_locations, $title, $user_info = null) {
    if($user_info != null) {
        $id = $user_info['userID'];
        $username = $user_info['username'];
        $name = $user_info['name'];
        $password = $user_info['password'];
        $email = $user_info['email'];
        $bio = $user_info['description'];
        $birthDate = $user_info['birthDate'];
        $gender = $user_info['gender'];
        $location = $user_info['locationID'];
    
        $imageName = $user_info['image'];
        $imagePreview = "../assets/images/users/medium/$imageName";
        $hasFile = $imageName == "noImage.png" ? "no" : "yes";
    }
    else {
        $id = null;
        $username = '';
        $name = '';
        $password = '';
        $email = '';
        $bio = '';
        $birthDate = '';
        $gender = '';
        $location = '';
        $imagePreview = '../assets/images/users/medium/noImage.png';
        $hasFile = "no";
    } 
    
   if($gender == 'M') {
     $isMale = "checked";
     $isFemale = "unchecked";
     $isOther = "unchecked";
   }
   else if($gender == 'F') {
    $isMale = "unchecked";
    $isFemale = "checked";
    $isOther = "unchecked";
   }
   else if($gender == 'O') {
    $isMale = "unchecked";
    $isFemale = "unchecked";
    $isOther = "checked";
   }
   else {
    $isMale = "unchecked";
    $isFemale = "unchecked";
    $isOther = "unchecked";
   }   

?>

    <main id="profile-form">
        <header>
            <h3><?=$title?></h3>
        </header>

        <form>
            <?php if($id != null) { ?>
                <input type="hidden" name="userID" value=<?=$id?>>
            <?php } ?>

            <section id="img-upload" class="row">
                <div>
                    <label for="prof-image">Profile image:
                        <img id="img-to-upload" class="circular-img" src="<?=$imagePreview?>">
                    </label>
				</div>
				<!-- TODO ver este label -->
                <label class="button" for="imageFile">Select foto</label>
                <input type="file" id="imageFile" accept="image/*" name="imageFile" data-hasFile=<?=$hasFile?>>
                <button class="button" type="button" id="remove-button">Remove</button>
            </section>

            <label for="username">Username: 
                <input type="text" id="username" name="username" required value="<?=$username?>">
            </label>
            <label for="name">Name: 
                <input type="text" id="name" name="name" required value="<?=$name?>">
            </label>
            <label for="password">Password: 
                <input type="password" id="password" name="password" required >
            </label>

            <?php if($title == 'Edit profile') { ?>
                <div class="new-password-div row">
                    <label for="new-password">New password: 
                        <input type="password" id="new-password" name="new-password">
                    </label>
                    <label for="conf-new-password">Confirm new password: 
                        <input type="password" id="conf-new-password" name="conf-new-password">
                    </label>
                </div>
            <?php } ?>
            
            <label for="email">Email: 
                <input type="email" id="email" name="email" required value="<?=$email?>">
            </label>
            <label for="bio">Bio: 
                <textarea id="bio" rows="10" cols="50" name="bio"><?=$bio?></textarea>
            </label>
            <label for="birthDate">Birth Date: 
                <input type="date" id="birthDate" name="birthDate" min="1910-01-01" required value="<?=$birthDate?>"> 
            </label>

            <div id="form-gender">
                <h4>Gender:</h4>
                    <input type="radio" name="gender" value="M" <?=$isMale?> required> M
                    <input type="radio" name="gender" value="F" <?=$isFemale?> required> F
                    <input type="radio" name="gender" value="O" <?=$isOther?> required> O
            </div>
            <label for="location">Location: 
                <select id="location" name="location" required>
                    <?php foreach($all_locations as $eachLocation) { 
                        $selected = $eachLocation['locationID'] == $location ? "selected" : ""; 
                        $locationString = $eachLocation['country'] . ' - ' . $eachLocation['city']; ?>
                        <option value=<?=$eachLocation['locationID']?> <?=$selected?>><?=$locationString?></option>
                    <?php } ?>
                </select>
            </label>

            <p id="profile-form-error" class="error-message"></p>
			<button class="button">Confirm</button>
        </form>
					</main>

<?php } ?>