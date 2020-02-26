		<?php
		include("../include/config.inc.php");
		include("session.php");
		$converter  = new encryption();
		$dbfunction = new dbfunctions();
		$dbfunction->SimpleSelectQuery("select * from tbl_coupons where coupon_id=".$converter->decode($_GET['id']));
		$objsel = $dbfunction->getFetchArray();
		?>
		<form class="form-horizontal bootstrap-validator-form">
		<fieldset>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Coupon ID:</label>
			<div class="col-lg-6">
			<?php echo $objsel['coupon_id'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Coupon Code:</label>
			<div class="col-lg-6">
			<?php echo $objsel['coupon_code'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Start Time:</label>
			<div class="col-lg-6">
			<?php echo datFormat($objsel['start_time'],true);?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Expiry Time:</label>
			<div class="col-lg-6">
			<?php echo datFormat($objsel['expiry_time'],true);?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Discount:</label>
			<div class="col-lg-6">
			<?php echo $objsel['discount_value']. ' (' .$objsel['discount_type'].')';?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Multi Use:</label>
			<div class="col-lg-6">
			<?php echo ($objsel['is_multi_use']!= 1)?'Yes':'No'; ?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Status:</label>
			<div class="col-lg-6">
			<?php echo ($objsel['is_active']!= 1)?'Deactive':'Active'; ?>
			</div>
		</div>
		</fieldset>
		</form>	