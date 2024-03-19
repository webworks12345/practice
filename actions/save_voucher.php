<style>
    .success-popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #4CAF50;
        color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        text-align: center;
    }

    .success-popup a {
        color: #fff;
        text-decoration: underline;
        margin-top: 10px;
        display: inline-block;
    }
</style>



<?php
// Include your database connection logic here
global $DB;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ownerID = $_POST['ownerID'];
    $businessCode = $_POST["businessCode"];
    $branchCode = $_POST["branchCode"];
    $packCode = $_POST["packCode"];
    $voucherName = $_POST["voucherName"];
    $voucherCode = $_POST["voucherCode"];
    $discountType = $_POST["discountType"];
    $discountValue = $_POST["discountValue"];
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $condition = $_POST["condition"];

    // Additional fields for specific conditions
    $selectedPackage = isset($_POST["selectedPackage"]) ? $_POST["selectedPackage"] : null;
    $minSpend = isset($_POST["minSpend"]) ? $_POST["minSpend"] : null;

     // Check if the voucher code already exists
     $checkDuplicateSql = "SELECT COUNT(*) AS count FROM voucher WHERE voucherCode = '$voucherCode'";
     $result = $DB->query($checkDuplicateSql);
     $row = $result->fetch_assoc();
     $count = $row['count'];
 
     if ($count > 0) {
        echo "<div class='success-popup'>
        <p>Voucher Code Already Exist. Please use a different Voucher Code.</p>
      </div>
      <script>
        setTimeout(function() {
            window.location.href = '?page=create_voucher';
        }, 3000); // Redirect after 3 seconds
      </script>";
     }

    // Insert voucher details into the database
    if ($condition === "Specific Package") {
    // If the discount type is specific package, save the package code
    $sql = "INSERT INTO voucher (ownerID, businessCode, branchCode, voucherName, voucherCode, voucherType, discountType, discountValue, startDate, endDate, packCode)
            VALUES ('$ownerID', '$businessCode', '$branchCode', '$voucherName','$voucherCode', '$condition','$discountType', '$discountValue', '$startDate', '$endDate', '$packCode')";
    }else if ($condition === "Minimum Spend") {
    // If the condition is minimum spend, save the minimum spend value
    $sql = "INSERT INTO voucher (ownerID, businessCode, branchCode, voucherName, voucherCode, voucherType, discountType, discountValue, startDate, endDate, min_spend)
            VALUES ('$ownerID', '$businessCode', '$branchCode', '$voucherName', '$voucherCode', '$condition', '$discountType', '$discountValue', '$startDate', '$endDate', '$minSpend')";
    } else {
    // For other conditions
    $sql = "INSERT INTO voucher (ownerID, businessCode, branchCode, voucherName, voucherCode, voucherType, discountType, discountValue, startDate, endDate)
            VALUES ('$ownerID','$businessCode', '$branchCode', '$voucherName', '$voucherCode', '$condition', '$discountType', '$discountValue', '$startDate', '$endDate')";
    }

    if ($DB->query($sql) === TRUE) {
        // Voucher created successfully
        echo "<div class='success-popup'>
                <p>Voucher created successfully!</p>
              </div>
              <script>
                setTimeout(function() {
                    window.location.href = '?page=owner_voucher';
                }, 3000); // Redirect after 3 seconds
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $DB->error;
    }
}
?>
