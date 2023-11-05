<?php if( ! defined( 'ACCESS' ) ) die( 'DIRECT ACCESS NOT ALLOWED' ); ?>

<?= element( 'header' ) ?>

<?= element( 'owner-side-nav' ) ?>

<?php
global $DB;
$ownerID = $_SESSION['userID'];

$sql = "SELECT * FROM business WHERE ownerID = $ownerID AND status = 1";
$result = $DB->query($sql);
?>

<?php if ($result->num_rows > 0): ?>
    <div id="own-bus" class="own-bus">
        <div class="d-flex justify-content-between p-3">
            <h1>My Business</h1>
            <a href="?page=bus-register" id="registerButton">
                <i class="bi bi-plus-square black-text"></i>
                <span class="black-text">Register your business here!</span>
            </a>
            <a href="#" id="backButton" class="btn-back float-start mt-4" onclick="toggleBack()" style="display: none;">
                <i class="bi bi-arrow-left"></i>
                <span>Back</span>
            </a>
        </div>

        <div id="businessList">
            <table class="table table-hover table-responsive">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Business Name</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <th scope="row"><?= $count++ ?></th>
                            <td>
                                <div class="view-business" data-businesscode="<?= $row['businessCode'] ?>">
                                    <?= $row['busName'] ?>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-primary view-business" onclick="toggleView()" data-businesscode="<?= $row['businessCode'] ?>">View</button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php $result->data_seek(0); // Reset the result set pointer to the beginning for displaying details ?>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div id="detailsForm" class="bus-info card border-0 rounded-5 shadow p-3 mb-5 bg-white rounded" style="display: none; position: absolute; top: 80%; left: 60%; transform: translate(-50%, -50%); width: 70%;">
            <div class="business-details d-flex justify-content-between p-3" id="businessDetails<?= $row['businessCode'] ?>" style="display: none;">
                <!-- Include business details here -->
                <h2>Business Information</h2>
                <div class="d-flex" style="position: absolute; top: 5%; right: 5%;">
                    <a href="#" id="editButton" class="btn-edit float-end mt-4" onclick="toggleEditable()">
                        <i class="bi bi-pencil-fill"></i>
                        <span>Edit</span>
                    </a>
                </div>
                <br>
                <!-- 2 columns for details and pic -->
                <form method="post" action="?action=update_business">
                    <input type="hidden" name="business_Code" value="<?= $row['businessCode'] ?>">
                    <div class="column d-flex row justify-content-between">
                        <div class="col-md-7 flex-column">
                            <h6>About Us</h6>
                            <input type="text" class="bus-Name-field form-control" name="data[busName]" id="busName" placeholder="Business Name" value="<?= $row['busName'] ?>" readonly>
                            <h6>About Us</h6>
                            <input type="text" class="about-field form-control" name="data[about]" id="about" placeholder="Tell something about your business" value="<?= $row['about'] ?>" readonly>
                            <h6>Contact Us</h6>
                            <input type="text" class="contact-field form-control" name="data[phone]" id="phone" placeholder="(e.g. Links, Contact Numbers, Websites)" value="<?= $row['phone'] ?>" readonly>
                            <div class="d-flex" >
                                <button type="submit" class="btn btn-primary" name="updateBusiness" id="saveButton" style="display: none;" onclick="toggleEditable()">Save</button>
                                <button type="button" class="btn btn-secondary" id="cancelButton" style="display: none;" onclick="toggleEditable()">Cancel</button>
                            </div>
                        </div>
                        <!-- image preview -->
                        <div class="col-md-5">
                            <div>
                                <div class="mb-4 d-flex justify-content-center">
                                    <img src="https://mdbootstrap.com/img/Photos/Others/placeholder.jpg" alt="example placeholder" />
                                </div>
                                <div class="d-flex justify-content-center">
                                    <div class="btn btn-primary btn-rounded">
                                        <label class="form-label text-white m-1" for="customFile1">Choose file</label>
                                        <input type="file" class="form-control d-none" id="customFile1" />
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                        <a href="#branch-details" id="ViewBranch" class="btn-view-branches align-items-center justify-content-center view-branch-button" data-businesscode="<?= $row['businessCode'] ?>" onclick="toggleViewBranch(this)">
                            <i class="bi bi-eye"></i>
                            <span>View Branch</span>
                        </a>
                        <br>
                        <a href="#branch" id="AddBranch" class="btn-add-branch align-items-center justify-content-center">
                            <i class="bi bi-plus-square"></i>
                            <span>Add Branch</span>
                        </a>

                </form>
            </div>
        </div>

        <!-- ADD BRANCH DETAILS -->
        <?php
        // Fetch branch details from the branches table based on business code
        $businessCode = $row['businessCode'];
        $branchQuery = "SELECT * FROM branches WHERE businessCode = $businessCode";
        $branchResult = $DB->query($branchQuery);
        if ($branchResult->num_rows > 0) {
            $branchData = $branchResult->fetch_assoc();
        }
        ?>
   <?php while ($branchData = $branchResult->fetch_assoc()): ?>
    
        <div class="branch-details" id="branchDetails<?= $row['businessCode'] ?>" style="display: none;">
            <div class="bus-info card border-0 rounded-5 shadow p-3 mb-5 bg-white rounded" 
            style="position: absolute; top: 160%; left: 60%; transform: translate(-50%, -50%); width: 70%;">
                <div class="d-flex justify-content-between p-4">
                    <h2>Branch Information</h2>
                    <a href="#" id="editButton2" class="btn-edit float-end mt-4" onclick="toggleEditBranch()">
                        <i class="bi bi-pencil-fill"></i>
                        <span>Edit</span>
                    </a>
                </div>
                <div class="column d-flex row justify-content-between">
                    <div class="col-md-7 flex-column">
                        <!-- Include form fields for branch details -->
                        <form method="post" action="">
                            <input type="hidden" name="businessCode" value="">
                            <!-- Add fields for branchName, address, and coordinates here -->
                            <label for="branchName">Branch Name:</label>
                            <input type="text" class="form-control" name="branchName" id="branchName" placeholder="Branch Name" value='<?= $branchData['branchName'] ?>' readonly>
                            <label for="address">Address:</label>
                            <input type="text" class="form-control" name="address" id="address" placeholder="Address" value='<?= $branchData['address'] ?>' readonly>
                            <label for="coordinates">Coordinates:</label>
                            <input type="text" class="form-control" name="coordinates" id="coordinates" placeholder="Coordinates" value='<?= $branchData['coordinates'] ?>' readonly>
                            <div class="d-flex" >
                                <button type="submit" class="btn btn-primary" name="updateBranch" id="saveButton2" style="display: none;" onclick="toggleEditBranch()">Save</button>
                                <button type="button" class="btn btn-secondary" id="cancelButton2" style="display: none;" onclick="toggleEditBranch()">Cancel</button>
                            </div>
                        
                        </form>
                    </div>
               
                <div class="col-md-5">
                    <div>
                        <div class="mb-4 d-flex justify-content-center">
                            <img src="https://mdbootstrap.com/img/Photos/Others/placeholder.jpg" alt="example placeholder" />
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="btn btn-primary btn-rounded">
                                <label class="form-label text-white m-1" for="customFile1">Choose file</label>
                                <input type="file" class="form-control d-none" id="customFile1" />
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
       <?php endwhile; ?> 
    <?php endwhile; ?>
<?php endif; ?>




<?php if ($result->num_rows == 0): ?>
      <div id="own-bus" class="own-bus">
      <h1>My Business</h1>
      <div class="d-flex justify-content-center align-items-center p-3" style="height: 100vh;">
          <div class="business-form-background">
              <a href="?page=bus-register">
                  <i class="bi bi-plus-square"></i>
                  <span>No Business Record Found, Register your business here!</span>
              </a>
          </div>
      </div>
  </div>
  <?php endif; ?>


    <script>
        document.querySelectorAll('.view-business').forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default link behavior
                var businessCode = button.getAttribute('data-businesscode');
                // Hide the business list
                document.getElementById('businessList').style.display = 'none';

                // Display the business details
                var detailsForm = document.getElementById('detailsForm');
                detailsForm.style.display = 'block';

                // Hide all business details and show the one corresponding to the clicked button
                document.querySelectorAll('.business-details').forEach(function (details) {
                    details.style.display = 'none';
                });
                var businessDetails = document.getElementById('businessDetails' + businessCode);
                businessDetails.style.display = 'block';
                detailsForm.innerHTML = businessDetails.innerHTML;
            });
        });


   function toggleBack() {
        // Show the list of businesses
        document.getElementById("businessList").style.display = "block";
        // Hide the business info section
        document.getElementById("detailsForm").style.display = "none";
        // Change the text of the "Back" button to "Register your business"
        document.getElementById("registerButton").style.display = "block";
        document.getElementById("backButton").style.display = "none";

        location.reload();
   }

  function toggleView() {
    // Show the list of businesses
    document.getElementById("businessList").style.display = "block";
    // Hide the business info section
    document.getElementById("detailsForm").style.display = "none";
    // Change the text of the "Back" button to "Register your business"
    document.getElementById("registerButton").style.display = "none";
    document.getElementById("backButton").style.display = "block";

  }

  function toggleEditable() {
        // Toggle the readonly attribute on input fields
        toggleInputEditable("busName");
        toggleInputEditable("about");
        toggleInputEditable("phone");
        toggleButtonVisibility("saveButton");
        toggleButtonVisibility("cancelButton");
        toggleButtonVisibility("ViewBranch");
        toggleButtonVisibility("AddBranch");

        var editButton = document.getElementById("editButton");
        editButton.style.display = editButton.style.display === "none" ? "block" : "none";
    }

    function toggleInputEditable(inputId) {
        var input = document.getElementById(inputId);
        input.readOnly = !input.readOnly;
    }


    function toggleButtonVisibility(buttonId) {
        var button = document.getElementById(buttonId);
        button.style.display = button.style.display === "none" ? "block" : "none";
    }

//=====================BRANCH==============================


    function toggleAddBranch() {
    document.getElementById("branch").style.display = "block";
    toggleButtonVisibility("ViewBranch");
    
}


function toggleViewBranch(button) {
        const businessCode = button.getAttribute('data-businesscode');
        const branchDetails = document.getElementById(`branchDetails${businessCode}`);
        branchDetails.style.display = 'block';
        
}

function toggleEditBranch() {
        // Toggle the readonly attribute on input fields
        toggleInputEditable("branchName");
        toggleInputEditable("address");
        toggleInputEditable("coordinates");
        toggleButtonVisibility("saveButton2");
        toggleButtonVisibility("cancelButton2");
        toggleButtonVisibility("ViewPackage");
        toggleButtonVisibility("AddPackage");

        var editButton = document.getElementById("editButton2");
        editButton.style.display = editButton.style.display === "none" ? "block" : "none";
    }
</script>
