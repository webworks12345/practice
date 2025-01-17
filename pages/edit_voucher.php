<?= element('owner_header') ?>

<?php
$voucherID = $_GET['id'];

$voucherDetails = $DB->query("SELECT * FROM voucher WHERE voucherID = '$voucherID'");
if ($voucherDetails->num_rows > 0) {
    $voucher = $voucherDetails->fetch_assoc();
}

$businessCode = $voucher["businessCode"];
$branchCode = $voucher["branchCode"];

$businessDetails = $DB->query("SELECT * FROM business  WHERE businessCode = '$businessCode'");
$business = $businessDetails->fetch_assoc();

$branchDetails = $DB->query("SELECT * FROM branches  WHERE branchCode = '$branchCode'");
$branch = $branchDetails->fetch_assoc();

if ($voucher["voucherType"] == 'Specific Package') {
    $packageDetails = $DB->query("SELECT * FROM package WHERE branchCode = '$branchCode'");
}

$busName = isset($business["busName"]) ? $business["busName"] : "All Business";
$branchName = isset($branch["branchName"]) ? $branch["branchName"] : "";
?>

<div class="package-info" style="margin: 120px 0 0 30%">
    <div class="card p-5 bg-opacity-25 bg-white">
        <form action="?action=update_voucher" method="post">
            <h4>Edit Voucher</h4>
            <h6><?= $busName . ($branchName !== "" ? " (" . $branchName . ")" : "") ?></h6>

            <hr>
            <h6><?= $voucher["voucherType"] ?></h6><br>
           
            Voucher Code: <input type="text" name="voucherCode" class="form-control" value="<?= $voucher["voucherCode"] ?>"><br>
            
            <?php if ($voucher["voucherType"] == 'Specific Package') { ?>
                <div id="packageField">
                    Specific Package: 
                    <select name="specificPackage" class="form-control">
                        <?php while ($package = $packageDetails->fetch_assoc()) { ?>
                            <option value="<?= $package['packCode'] ?>"><?= $package['packName'] ?></option>
                        <?php } ?>
                    </select><br>
                </div>
            <?php } ?>
            
            <?php if ($voucher["voucherType"] == 'Minimum Spend') { ?>
                <div id="minSpendField">
                    Minimum Spend: <input type="text" name="min_spend" class="form-control" value="<?= $voucher["min_spend"] ?>"><br>
                </div>
            <?php } ?>

            Discount Type:
            <select name="discountType" class="form-control"required>
                <option value="percentage" <?= ($voucher["discountType"] == "percentage") ? "selected" : "" ?>>Percentage</option>
                <option value="amount" <?= ($voucher["discountType"] == "amount") ? "selected" : "" ?>>Amount</option>
            </select>
            
            Discount Value: <input type="text" name="discountValue" class="form-control" value="<?= $voucher["discountValue"] ?>"required><br>
            
           
            
            Start Date: <input type="date" name="startDate" class="form-control" value="<?= $voucher["startDate"] ?>"required><br>
            
            End Date: <input type="date" name="endDate" class="form-control" value="<?= $voucher["endDate"] ?>" required><br>
            <input type="hidden" name="voucherID" value="<?= $voucherID ?>">
            <input type="submit" class="btn btn-primary" value="Update Voucher">
        </form>
    </div>
</div>

