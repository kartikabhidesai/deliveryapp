<?php
include("../include/config.inc.php");
include("session.php");
$converter = new encryption();
$dbfunction = new dbfunctions();
$sql = "SELECT tbl_product.*, tbl_category.name";
$sql.=" FROM tbl_product INNER JOIN tbl_category ON tbl_category.id=tbl_product.cat_id WHERE tbl_product.is_deleted='0' AND tbl_product.id=" . $_GET['id'];
$query = mysqli_query($dbConn, $sql) or die("product_grid.php: search products");
?>
<form class="form-horizontal bootstrap-validator-form">
    <fieldset>
        <?php while ($row = mysqli_fetch_array($query)) {?>
        <div class="form-group">
            <label class="col-lg-4 control-label-text">Image:</label>
            <div class="col-lg-6">
                <img src="../uploads/product/'.$row['product_name'].'" width="100" height="100" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label-text">Product Name:</label>
            <div class="col-lg-6">
                <?php echo $row['product_name']; ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label-text">Product Category:</label>
            <div class="col-lg-6">
                <?php echo $row['name']; ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label-text">Contact Number:</label>
            <div class="col-lg-6">
                <?php echo $row['mobile']; ?>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-4 control-label-text">Address:</label>
            <div class="col-lg-6">
                <?php echo $row['address']; ?>
            </div>
        </div>
        <?php  }?>
    </fieldset>
</form>	