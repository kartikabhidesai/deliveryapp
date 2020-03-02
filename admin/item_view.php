<?php
include("../include/config.inc.php");
include("session.php");
$converter = new encryption();
$dbfunction = new dbfunctions();
$sql = "SELECT tbl_item.*, tbl_item_size_price.item_size, tbl_item_size_price.item_price ,tbl_variant.variant_name";
$sql .= " FROM tbl_item INNER JOIN tbl_item_size_price ON tbl_item_size_price.item_id=tbl_item.id INNER JOIN tbl_variant ON tbl_variant.id=tbl_item_size_price.item_size WHERE tbl_item.id=" . $_GET['id'];
$query = mysqli_query($dbConn, $sql) or die("tbl_item.php: search items");
$query1 = mysqli_query($dbConn, $sql) or die("tbl_item.php: search items");

?>
<form class="form-horizontal bootstrap-validator-form">
    <fieldset>
        <div class="form-group">
            <label class="col-lg-4 control-label-text">Item Image:</label>
            <div class="col-lg-6">
                <img src="../uploads/item/'<?php echo $row['item_image']; ?>'" width="100" height="100" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label-text">Item Name:</label>
            <div class="col-lg-6">
                <?php while ($row = mysqli_fetch_array($query1)) { 
                 echo $row['item_name']; 
                 break;
                 } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label-text">Item size</label>
            <label class="col-lg-4 control-label-text">Item price</label>
        </div>
        <?php while ($row = mysqli_fetch_array($query)) { ?>

            <div class="form-group">
                <div class="col-lg-6 text-center">
                    <?php echo $row['variant_name']; ?>
                </div>
                <div class="col-lg-6">
                    <?php echo $row['item_price']; ?>
                </div>
            </div>
        <?php } ?>
    </fieldset>
</form>	