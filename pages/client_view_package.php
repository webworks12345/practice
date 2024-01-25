<?= element('header') ?>
<!-- Styles -->
<style>
    @media (min-width: 1000px) {
        .package-view {
            margin: 120px;
            width: auto;
        }
    }

    @media (max-width: 700px) {
        .package-view {
            margin-top: 120px;
        }

        .total {
            margin: 0 !important;
        }
    }

    /* Additional styling for the modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.9);
        padding-top: 160px;
    }

    .modal-content {
        margin: auto;
        display: block;
        max-width: 50%;
        max-height: 50%;
        position: relative;
    }

    .close {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 30px;
        color: #fff;
        cursor: pointer;
    }
</style>

<?php
if (!defined('ACCESS')) die('DIRECT ACCESS NOT ALLOWED');

$clientID = $_SESSION['userID'];
$clientType = $_SESSION['usertype'];
$businessCode = isset($_GET['businessCode']) ? $_GET['businessCode'] : '';
$branchCode =  isset($_GET['branchCode']) ? $_GET['branchCode'] : '';
$packCode = isset($_GET['packCode']) ? $_GET['packCode'] : '';

// Fetch package details
$packageDetailsQ = $DB->query("SELECT p.*, i.*
    FROM package p
    JOIN items i ON p.packCode = i.packCode
    WHERE p.packCode = '$packCode'");

// Check if the query was successful
if ($packageDetailsQ) {
    $packageDetails = $packageDetailsQ->fetch_assoc();
}
?>

<div id="package-view" class="package-view h-100">

    <!-- Navigation Bar -->
    <div class="row d-flex justify-content-start align-items-center" style="margin-left: 50px">
        <a href="?page=client_package&businessCode=<?= $businessCode?>&branchCode=<?= $branchCode ?>" class="col-1 btn-back btn-lg justify-content-center align-items-center d-flex text-dark">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="col-10 d-flex justify-content-start"><?= $packageDetails['packName'] ?></h1>
    </div>

    <!-- Package Details Table -->
    <div class="card mt-5 justify-content-center align-items-center d-flex p-3 table-responsive">
        <table class="table table-hover table-responsive">
            <!-- Table Header -->
            <thead>
                <tr style="border-bottom: 2px solid orange;">
                    <th>Image</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Additional Detail</th>
                    <?php if ($packageDetails['pricingType'] === 'per pax') : ?>
                    <?php else : ?>
                        <th>Quantity</th>
                        <th>Price</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
                <?php foreach ($packageDetailsQ as $row) : ?>
                    <?php
                    // Fetch other details from item_details table
                    $itemCode = $row['itemCode'];
                    $itemDetailsQ = $DB->query("SELECT * FROM item_details WHERE itemCode = '$itemCode'");
                    $itemDetails = $itemDetailsQ->fetch_assoc();
                    ?>
                    <tr>
                        <td>
                            <img src="<?= $row['itemImage'] ?>" alt="<?= $row['itemName'] ?>" style="max-width: 200px; height: 180px;"
                            onclick="openModal('<?= $row['itemImage'] ?>', '<?= $row['itemName'] ?>')">
                        </td>
                        <td><?= $row['itemName'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td>
                    <?php if (!empty($itemDetails['detailName']) && !empty($itemDetails['detailValue'])) : ?>
                        <strong><?= $itemDetails['detailName'] ?></strong>: <?= $itemDetails['detailValue'] ?>
                    <?php else : ?>
                        N/A
                    <?php endif; ?>
                </td>
                        <?php if ($packageDetails['pricingType'] === 'per pax') : ?>
                        <?php else : ?>
                            <td><?= $row['quantity']." ". $row['unit'] ?></td>
                            <td><?= '₱' . number_format($packageDetails['price'], 2)  ?></td>
                        <?php endif; ?>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Total Container -->
    <div class="container mt-3 text-center" style="background-color: white; padding: 10px; height: auto;">
            <?php
            if ($packageDetails['pricingType'] === 'per pax') {
                // Display 'per pax' pricing
                $total = 'Total: ' . '₱' . number_format($packageDetails['amount'], 2) . ' / pax';
            } else {
                // Calculate total for other pricing types
                $total = 0;
                foreach ($packageDetailsQ as $row) {
                    $total += $row['quantity'] * $row['price'];
                }
                $total = 'Total: ' . '₱' . number_format($total, 2);
            }
            ?>
            <p style="font-size: 30px;"><?= $total ?></p>

            <!-- Quantity Meter Container -->
            <div id="quantityMeterContainer" style="margin-top: 10px; display:none;">
                <label for="quantityMeter">No. of Persons:</label>
                <input type="number" id="quantityMeter" placeholder="Enter quantity" value="1">
            </div>
    </div>


    <div class="container mt-3 text-center">
        <button id="customizeButton" class="btn btn-primary" onclick="customizePackage()">Customize</button>
        <button id="backButton" class="btn btn-secondary d-none" onclick="backToPackageView()">Back</button>
        <button id="saveButton" class="btn btn-success d-none" onclick="saveCustomization()">Save</button>
    </div>

</div>

<!-- Checkout Container -->
<div id="checkoutContainer" class="container mt-3 text-center d-none">
    <h2>Checkout</h2>
    <table id="checkoutTable" class="table table-bordered table-responsive" style="margin-top: 130px;">
        <!-- Table Header -->
        <thead>
    <tr>
        <th>Item Name</th>
        <th>Description</th>
        <th>Image</th>
        <th>Customized</th>
        <th>Quantity</th>
    </tr>
</thead>

        <!-- Table Body -->
        <tbody id="checkoutTableBody">
            <!-- Checkout table content will be added dynamically -->
        </tbody>
    </table>

    <!-- Back and Checkout Buttons -->
    <div class="container mt-3 text-center">
            <button id="backToCustomization" class="btn btn-secondary d-none" onclick="backToCustomization()">Back to Customization</button>
            <button id="proceedToCheckout" class="btn btn-success d-none" onclick="proceedToCheckout()">Proceed to Checkout</button>
        </div>
    </div>

    <!-- Modal for displaying full-size image -->
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal">&times;</span>
            <img id="fullImage" style="width: 100%; height: auto;">
        </div>
    </div>



<!-- JavaScript for opening and closing the modal -->
<script>
    // Open modal with full-size image
    function openModal(imageSrc, itemName) {
        var modal = document.getElementById('imageModal');
        var modalImage = document.getElementById('fullImage');

        modal.style.display = 'block';
        modalImage.src = imageSrc;
        modalImage.alt = itemName;
    }

    // Close the modal
    function closeModal() {
        var modal = document.getElementById('imageModal');
        modal.style.display = 'none';
    }

    // Close the modal when clicking the close button
    document.getElementsByClassName('close')[0].onclick = closeModal;

    // Close the modal when clicking outside the image
    window.onclick = function(event) {
        var modal = document.getElementById('imageModal');
        if (event.target == modal) {
            closeModal();
        }
    };


    function customizePackage() {
        var packageDetails = <?php echo json_encode($packageDetails); ?>;
        var pricingType = packageDetails['pricingType'];

        if (pricingType === 'per pax') {
            customizePerPax();
        } else {
            customizeOther();
        }

        // Show back button
        document.getElementById('customizeButton').classList.add('d-none');
        document.getElementById('backButton').classList.remove('d-none');
        document.getElementById('saveButton').classList.remove('d-none');

    }

    var customizationApplied = false; // Add this global variable

function customizePerPax() {
    if (!customizationApplied) {
        var tableBody = document.querySelector('#package-view table tbody');
        var rows = tableBody.querySelectorAll('tr');

        // Add a new column with a textarea for 'per pax' pricing
        rows.forEach(function(row) {
            var textareaCell = document.createElement('td');
            var textarea = document.createElement('textarea');
            textarea.placeholder = 'Enter customization';
            textarea.className = 'form-control';
            textareaCell.appendChild(textarea);
            row.appendChild(textareaCell);
        });

        // Show the quantity meter container
        document.getElementById('quantityMeterContainer').style.display = 'block';

        customizationApplied = true;
    }
}

    function customizeOther() {
        var tableBody = document.querySelector('#package-view table tbody');
        var rows = tableBody.querySelectorAll('tr');

        // Add two new columns with textarea and quantity meter for other pricing types
        rows.forEach(function(row) {
            var textareaCell = document.createElement('td');
            var textarea = document.createElement('textarea');
            textarea.placeholder = 'Enter customization';
            textarea.className = 'form-control';
            textareaCell.appendChild(textarea);
            row.appendChild(textareaCell);

            var quantityCell = document.createElement('td');
            var quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.placeholder = 'Enter quantity';
            quantityInput.className = 'form-control';
            quantityInput.value = 1;
            quantityInput.min = 1;
            quantityCell.appendChild(quantityInput);
            row.appendChild(quantityCell);
        });

    }

    function backToPackageView() {
    var tableBody = document.querySelector('#package-view table tbody');
    var rows = tableBody.querySelectorAll('tr');

    // Determine pricing type
    var pricingType = <?php echo json_encode($packageDetails['pricingType']); ?>;

    if (pricingType === 'per pax') {
        // Remove the two last cells for 'per pax' pricing
        rows.forEach(function (row) {
            row.removeChild(row.lastElementChild); // Remove the last cell
        });

        // Hide the quantity meter container
        document.getElementById('quantityMeterContainer').style.display = 'none';
    } else {
        // Remove the last cell for other pricing types
        rows.forEach(function (row) {
            row.removeChild(row.lastElementChild); // Remove the last 
            row.removeChild(row.lastElementChild); // Remove the second last cell
            
        });

        // Hide the quantity meter container
        document.getElementById('quantityMeterContainer').style.display = 'none';
    }

    // Hide back button
    document.getElementById('backButton').classList.add('d-none');
    document.getElementById('saveButton').classList.add('d-none');
    document.getElementById('customizeButton').classList.remove('d-none');

    customizationApplied = false;
}

</script>

