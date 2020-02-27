<?php
include("../include/config.inc.php");
include("session.php");
$converter = new encryption();
$dbfunction = new dbfunctions();
$dbfunction->SimpleSelectQuery("select * from tbl_category where id=" . $_GET['id']);
$objsel = $dbfunction->getFetchArray();
?>
<form class="form-horizontal bootstrap-validator-form">
    <fieldset>
        <div class="form-group">
            <label class="col-lg-4 control-label-text">Category Name:</label>
            <div class="col-lg-6">
                <?php echo $objsel['name']; ?>
            </div>
        </div>
    </fieldset>
</form>	