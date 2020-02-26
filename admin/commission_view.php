		<?php
		include("../include/config.inc.php");
		include("session.php");
		$converter  = new encryption();
		$dbfunction = new dbfunctions();
		$dbfunction->SimpleSelectQuery("select * from tbl_commission_setting where commission_id=".$converter->decode($_GET['id']));
		$objsel = $dbfunction->getFetchArray();
		?>
		<form class="form-horizontal bootstrap-validator-form">
		<fieldset>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">ID#:</label>
			<div class="col-lg-6">
			<?php echo $objsel['commission_id'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Order Number:</label>
			<div class="col-lg-6">
			<?php echo $objsel['order_number'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Commission(%):</label>
			<div class="col-lg-6">
			<?php echo $objsel['commission_perc'];?>
			</div>
		</div>
		
		<!--<div class="form-group">
			<label class="col-lg-3 control-label-text">Display Order:</label>
			<div class="col-lg-6">
			<?php echo $objsel['display_order'];?>
			</div>
		</div>-->
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Status:</label>
			<div class="col-lg-6">
			<?php echo ($objsel['is_active']!= 1)?'Deactive':'Active'; ?>
			</div>
		</div>
		</fieldset>
		</form>	