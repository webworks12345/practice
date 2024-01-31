<?php
$userData = viewUser($_SESSION['userID']);

$fname = $userData->fname;
$lname = $userData->lname;
$birthday = $userData->birthday;
$email = $userData->email;
$number = $userData->number;
$username = $userData->username;
$password = $userData->password;
$ownerAddress = $userData->ownerAddress;
$profileImage = $userData->profileImage;



?>

<script src="assets/js/script.js"></script>
<div class="pb-5" style="height: auto ">
<div id="profile" class="profile" style="margin: 120px 0 0 23%; width: 70vw" >
  <div class="d-flex justify-content-between p-3">
    <h1 >My Profile</h1>
    <a href="#" class="btn-edit btn-md btn btn-outline-dark float-end mt-4 " onclick="toggleEditable()">
      <i id="pencilIcon" class="bi bi-pencil-fill"> Edit Profile</i>
    </a>
  </div>

  <form id="profileForm" method="POST" class="pb-5 ">
        <?= csrf_token() ?>
        <input type="hidden" name="action" value="usersAction">
        <div class="form">
            <div class="card p-4 mb-3">
            <div class="row g-3">
                <div class="d-flex justify-content-center align-items-center mb-2 ">
                <div>
                    <img class="profileImg rounded-circle shadow p-1 mb-5 bg-white" id="preview_profileImage" alt="Profile Image" src="assets/images/profile-placeholder.jpg" style="width: ">
                        <div class="d-flex justify-content-center align-items-center">
                            <input id="ProfPic" type="file" class="form-control" id="addProfileImage" name="addProfileImage" accept="image/*" onchange="previewImage(this, 'preview_profileImage')" style="display:none; width:135px">
                        </div>
                </div>
                </div>
                <div class="fields mb-3 ">
                    <label class="mb-2 col-lg-3 col-sm-5" for="fname">First Name</label>
                    <p id="fnameDisplay" style="display:block"><?php echo $fname; ?></p>
                    <input type="text" class="col form-control" name="fname" id="fname" placeholder="First Name" required 
                        value="<?php echo $fname; ?>" style="display:none">
                </div>
                <div class=" fields mb-3">
                    <label class="mb-2 col-lg-3 col-sm-5" for="lname">Last Name</label>
                    <p id="lnameDisplay" style="display:block"><?php echo $lname; ?></p>
                    <input type="text" class=" col form-control" name="lname" id="lname" placeholder="Last Name" required 
                        value="<?php echo $lname; ?>" style="display:none">
                </div>
                <div class="fields  mb-3 ">
                    <label class="mb-2 col-lg-3 col-sm-5" for="birthday">Birthday</label>
                    <p id="birthdayDisplay" style="display:block"><?php echo $birthday; ?></p>
                    <input type="date" class="date col form-control" name="birthday" id="birthday" placeholder="Birthday" required 
                        value="<?php echo $birthday; ?>" style="display:none">
                </div>
               
                <div class="fields  mb-3 ">
                    <label class="mb-2 col-lg-3 col-sm-5 " for="number">Mobile Number</label>
                    <p id="numberDisplay" style="display:block"><?php echo $number; ?></p>
                    <input type="text" class=" col form-control" name="number" id="number" placeholder="Mobile Number" required 
                        value="<?php echo $number; ?>" style="display:none">
                </div>
                <div class="fields mb-3 ">
                    <label class="mb-2 col-lg-3 col-sm-5" for="ownerAddress">Address</label>
                    <p id="ownerAddressDisplay" style="display:block"><?php echo $ownerAddress; ?></p>
                    <input type="text" class=" col form-control" name="ownerAddress" id="ownerAddress" placeholder="Address"  
                        value="<?php echo $ownerAddress; ?>" style="display:none">
                </div>
</div>
                </div>
                <div class="card p-4">
                    <div class="fields mb-3 ">
                        <label class="mb-2 col-lg-3 col-sm-5 " for="email">Email Address</label>
                        <p id="emailDisplay" style="display:block"><?php echo $email; ?></p>
                        <input type="email" class=" col form-control" name="email" id="email" placeholder="Email Address" required 
                            value="<?php echo $email; ?>" style="display:none">
                    </div>
                    <div class="fields ">
                        <label class="mb-2 col-lg-3 col-sm-5 " for="username">Username</label>
                        <p id="usernameDisplay" style="display:block"><?php echo $username; ?></p>
                        <input type="text" class=" col form-control" name="username" id="username" placeholder="Username" required 
                            value="<?php echo $username; ?>" style="display:none">
                    </div>
                </div>
                    
                   
                <div class="d-flex justify-content-end p-3" id="buttons">
                    <button type="submit" name="updateProfile" id="updateButton" class="btn-update btn btn-primary me-2"
                        style="display: none">Save Changes</button>
                    <button type="button" name="cancel" id="cancelButton" class="btn-cancel btn btn-secondary"
                        style="display: none" onclick="cancelEdit()">Cancel</button>
                </div>
                </div>
        </div>
    </form>
</div>

<style>
    @media (max-width: 1700px) {
        .fields{
            display: flex;
            flex-direction: row
        }
        .profileImg{
            width: 180px;
            height: 180px;
        }
    }

    @media (max-width:700px) {
        .fields{
            display: flex;
            flex-direction: column;
        }
        .profileImg{
            width: 8rem;
        }
    }
</style>
<script>
    function toggleEditable() {
    toggleVisibility("ProfPic");

    toggleVisibility("fnameDisplay");
    toggleVisibility("fname");
    toggleVisibility("lnameDisplay");
    toggleVisibility("lname");
    toggleVisibility("birthdayDisplay");
    toggleVisibility("birthday");
    toggleVisibility("numberDisplay");
    toggleVisibility("number");
    toggleVisibility("ownerAddressDisplay");
    toggleVisibility("ownerAddress");
    toggleVisibility("emailDisplay");
    toggleVisibility("email");
    toggleVisibility("usernameDisplay");
    toggleVisibility("username");

    toggleVisibility("updateButton");
    toggleVisibility("cancelButton");

    var editButton = document.getElementById("editButton");
    editButton.style.display = editButton.style.display === "none" ? "block" : "none";
}

function toggleVisibility(elementId) {
    var element = document.getElementById(elementId);
    if (element.style.display === "none" || element.style.display === "") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}

function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>