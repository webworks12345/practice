<div id="detailsForm" class="detailsForm card border-0 rounded-5 shadow p-3 mb-5 bg-white rounded" style="width: 75vw; display:none;">
    <div class="business-details d-flex justify-content-between p-3" id="businessDetails<?= $row['businessCode'] ?>" style="display: none;">
        <h2>Business Information</h2>
        <div class="d-flex" style="position: absolute; top: 5%; right: 5%;">
            <a href="#" id="editButton" class="btn-edit float-end mt-4" onclick="toggleEditable()"></a>
        </div>
        <br>
        <!-- 2 columns for details and pic -->
        <form method="post" action="?action=businessAction">
            <input type="hidden" name="business_Code" value="<?= $row['businessCode'] ?>">
            <div class="column d-flex row justify-content-between">
                <div class="col-md-7 flex-column">
                    <h6>About Us</h6>
                    <input type="text" class="bus-Name-field form-control" name="data[busName]" id="busName" placeholder="Business Name" value="<?= $row['busName'] ?>" readonly>
                    <h6>About Us</h6>
                    <input type="text" class="about-field form-control" name="data[about]" id="about" placeholder="Tell something about your business" value="<?= $row['about'] ?>" readonly>
                    <h6>Contact Us</h6>
                    <input type="text" class="contact-field form-control" name="data[phone]" id="phone" placeholder="(e.g. Links, Contact Numbers, Websites)" value="<?= $row['phone'] ?>" readonly>
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary" name="updateBusiness" id="saveBusiness" style="display: none;" onclick="toggleEditable()">Save</button>
                        <button type="button" class="btn btn-secondary" id="cancelBusiness" style="display: none;" onclick="toggleEditable()">Cancel</button>
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
            <a href="#add-branch" id="AddBranch" class="btn-add-branch align-items-center justify-content-center add-branch-button" data-businesscode="<?= $row['businessCode'] ?>" onclick="toggleAddBranch(this)">
                <i class="bi bi-plus-square"></i>
                <span>Add Branch</span>
            </a>
        </form>
    </div>
</div>

<?php
$businessCode = $row['businessCode'];
// Fetch branch details from the branches table based on the businessCode
$branchQuery = "SELECT * FROM branches WHERE businessCode = $businessCode";
$branchResult = $DB->query($branchQuery);
?>

<div class="add-branch" id="branch<?= $businessCode ?>" style="display: none;">
    <div class="branch-info card border-0 rounded-5 shadow p-3 mb-5 bg-white rounded">
        <div class="d-flex justify-content-between p-4">
            <h2>Branch Information</h2>
        </div>
        <form method="post" action="?action=businessAction">
            <input type="hidden" name="add_branch" value="<?= $row['businessCode'] ?>">
            <div class="column d-flex row justify-content-between">
                <div class="col-md-7 flex-column" style="height: 300px;">
                    <h6>Branch Name</h6>
                    <input type="text" class="about-field form-control" name="data[branchName]" placeholder="Tell something about your business">
                    <h6>Address</h6>
                    <input type="text" class="about-field form-control" name="data[address]" placeholder="Bldg No., Street, Brgy., City/Province">
                    <h6>Coordinates</h6>
                    <input type="text" class="about-field form-control" name="data[coordinates]" placeholder="Enter Branch Map Location">
                </div>
                <div class="col-md-5">
                    <div>
                        <div class="mb-4 d-flex justify-content-center">
                            <img src="" alt="Preview" id="imagePreview" style="max-width: 100%; max-height: 200px; display: none;" />
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="btn btn-primary btn-rounded">
                                <label class="form-label text-white m-1" for="branchImage">Choose file</label>
                                <input type="file" class="form-control d-none" name="branchImage" id="branchImage" onchange="previewImage(this)" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-4 d-flex">
                    <button type="submit" button class="btn btn-primary" name=createBranch id="createBranch<?= $branchData['branchCode'] ?>" style="display: block;" onclick="toggleAddBranch()">Create Branch</button>
                    <button type="button" button class="btn btn-secondary" name=cancelCreate id="cancelCreate<?= $branchData['branchCode'] ?>" style="display: block;" onclick="toggleAddBranch()">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- View Branch -->
<div class="branch-details" id="branchDetails<?= $businessCode ?>" style="display: none;">
    <?php while ($branchData = $branchResult->fetch_assoc()): ?>
        <div class="branch-info card border-0 rounded-5 shadow p-3 mb-5 bg-white rounded">
            <div class="d-flex justify-content-between p-4">
                <h2>Branch Information</h2>
                <a href="#" id="editBranch<?= $branchData['branchCode'] ?>" class="btn-edit float-end mt-4" onclick="toggleEditBranch(<?= $branchData['branchCode'] ?>)">
                    <i class="bi bi-pencil-fill"></i>
                    <span>Edit</span>
                </a>
            </div>
            <form method="post" action="?action=businessAction">
                <input type="hidden" name="branch" value="<?= $row['businessCode'] ?>">
                <input type="hidden" name="branch_Code" value="<?= $branchData['branchCode'] ?>">
                <div class="column d-flex row justify-content-between">
                    <div class="col-md-7 flex-column">
                        <h6>Branch Name</h6>
                        <input type="text" class="about-field form-control" name="data[branchName]" id="branchName<?= $branchData['branchCode'] ?>" placeholder="Tell something about your business" value="<?= $branchData['branchName'] ?>" readonly>
                        <h6>Address (Bldg No., Street, Brgy., City/Province)</h6>
                        <input type="text" class="about-field form-control" name="data[address]" id="address<?= $branchData['branchCode'] ?>" placeholder="Tell something about your business" value="<?= $branchData['address'] ?>" readonly>
                        <h6>Coordinates</h6>
                        <input type="text" class="about-field form-control" name="data[coordinates]" id="coordinates<?= $branchData['branchCode'] ?>" placeholder="Tell something about your business" value="<?= $branchData['coordinates'] ?>" readonly>
                    </div>
                    <div class="col-md-5">
                        <div>
                            <div class="mb-4 d-flex justify-content-center">
                                <img src="https://mdbootstrap.com/img/Photos/Others/placeholder.jpg" alt="example placeholder" />
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="btn btn-primary btn-rounded">
                                    <label class="form-label text-white m-1" for="customFile1">Choose file</label>
                                    <input type="file" class="form-control d-none" name="branchImage" id="customFile1" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <a href="#package" id="ViewPackage" class="btn-view-package align-items-center justify-content-center view-package-button" data-branchcode="<?= $branchData['branchCode'] ?>" onclick="toggleViewPackage(this)">
                            <i class="bi bi-eye"></i>
                            <span>View Package</span>
                        </a>
                        <br>
                        <a href="#package" id="AddPackage" class="btn-add-branch align-items-center justify-content-center add-package-button" data-branchCode="<?= $branchData['branchCode'] ?>" onclick="toggleAddPackage(this)">
                            <i class="bi bi-eye"></i>
                            <span>Add Package</span>
                        </a>
                    </div>

                    <div class="mt-4 p-4 d-flex">
                        <button type="submit" button class="btn btn-primary" name=updateBranch id="updateBranch<?= $branchData['branchCode'] ?>" style="display: none;" onclick="toggleEditBranch()">Save</button>
                        <button type="button" button class="btn btn-secondary" id="cancelBranch<?= $branchData['branchCode'] ?>" style="display: none;" onclick="toggleEditBranch()">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    <?php endwhile; ?>
</div> <!-- end of branch info -->


<!-- View Package -->
<div class="package-details" id="packageDetails<?= $businessCode ?>" style="display: none;">
    <?php while ($branchData = $branchResult->fetch_assoc()): ?>
        <?php
        $branchCode = $branchData['branchCode'];
        $packageQuery = "SELECT * FROM package WHERE branchCode = $branchCode";
        $packageResult = $DB->query($packageQuery);
        ?>

        <div class="package-info card border-0 rounded-5 shadow p-3 mb-5 bg-white rounded">
            <div class="d-flex justify-content-between p-4">
                <h2>Package Information</h2>
            </div>

            <table class="table table-hover table-responsive">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($packageData = $packageResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= $packageData['name'] ?></td>
                            <td><?= $packageData['description'] ?></td>
                            <td><?= $packageData['quantity'] ?></td>
                            <td><?= $packageData['price'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    <?php endwhile; ?>
</div>