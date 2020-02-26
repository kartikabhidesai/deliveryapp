		<?php
		include("../include/config.inc.php");
		include("session.php");
		$converter  = new encryption();
		$dbfunction = new dbfunctions();
		$dbfunction->SimpleSelectQuery("select * from tbl_recharge_packages where package_id=".$converter->decode($_GET['id']));
		$objsel = $dbfunction->getFetchArray();
		?>
		<form class="form-horizontal bootstrap-validator-form">
		<fieldset>
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Package Id:</label>
			<div class="col-lg-6">
			#<?php echo $objsel['package_id'];?>
			</div>
		</div>
		<!--<div class="form-group">
			<label class="col-lg-4 control-label-text">Recharge Package Name:</label>
			<div class="col-lg-6">
			<?php echo $objsel['package_name'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Recharge Package Name(ar):</label>
			<div class="col-lg-6">
			<?php echo $objsel['package_name_ar'];?>
			</div>
		</div>-->
	
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Package Amount:</label>
			<div class="col-lg-6">
			<?php echo $objsel['recharge_amount'];?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Extra Discount Percent:</label>
			<div class="col-lg-6">
			<?php echo $objsel['extra_discount_perc'];?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Total Amount:</label>
			<div class="col-lg-6">
			<?php echo $objsel['total_amount'];?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Status:</label>
			<div class="col-lg-6">
			<?php echo ($objsel['is_active']!= 1)?'Deactive':'Active'; ?>
			</div>
		</div>
		
		</fieldset>
		</form>	