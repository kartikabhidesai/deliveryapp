		<?php
		include("../include/config.inc.php");
		include("session.php");
		$converter  = new encryption();
		$dbfunction = new dbfunctions();
		$dbfunction->SimpleSelectQuery("select * from tbl_notification_types where not_type_id=".$converter->decode($_GET['id']));
		$objsel = $dbfunction->getFetchArray();
		?>
		<form class="form-horizontal bootstrap-validator-form">
		<fieldset>
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Display Title:</label>
			<div class="col-lg-6">
			<?php echo $objsel['not_type_title'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Short Desc:</label>
			<div class="col-lg-6">
			<?php echo $objsel['not_type_app'];?>
			</div>
		</div>
		<?php if($objsel['not_type_icon']!="") { ?>
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Icon:</label>
			<div class="col-lg-6">
			<img  src="../uploads/icons/<?php echo $objsel['not_type_icon'];?>" alt="Preview Image"  />
			</div>
		</div>
		<?php } ?>
		
		
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Status:</label>
			<div class="col-lg-6">
			<?php echo ($objsel['is_active']!= 1)?'Deactive':'Active'; ?>
			</div>
		</div>
		
		</fieldset>
		</form>	