		<?php
		include("../include/config.inc.php");
		include("session.php");
		$converter  = new encryption();
		$dbfunction = new dbfunctions();
		$dbfunction1 = new dbfunctions();
		$dbfunction->SimpleSelectQuery("select * from tbl_contents where id=".$converter->decode($_GET['id']));
		$objsel = $dbfunction->getFetchArray();
		?>
		<form class="form-horizontal bootstrap-validator-form">
		<fieldset>
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Title:</label>
			<div class="col-lg-6">
			<?php echo $objsel['field_title'];?>
			</div>
		</div>
		
		<div class="form-group">
		<label class="col-lg-4 control-label-text">Description (English):</label>	
		<div class="col-lg-6">
		<?php 
		echo stripcslashes(stripcslashes($objsel['field_value']));
		?>
		</div>
		</div>	
		
		
		<div class="form-group">
		<label class="col-lg-4 control-label-text">Description (Arabic):</label>	
		<div class="col-lg-6">
		<?php 
		echo stripcslashes(stripcslashes($objsel['field_value_ar']));
		?>
		</div>
		</div>	
		
		
		<div class="form-group">
		<label class="col-lg-4 control-label-text">Added on:</label>	
		<div class="col-lg-6">
		<?php 
		if($objsel['created_on']!='0000-00-00 00:00:00' && $objsel['created_on']!='')
		echo date("d-M Y H:i",strtotime($objsel['created_on']));
		?>
		</div>
		</div>
		
		
		
		<div class="form-group">
		<label class="col-lg-4 control-label-text">Updated on:</label>	
		<div class="col-lg-6">
		<?php 
		if($objsel['updated_on']!='0000-00-00 00:00:00' && $objsel['updated_on']!='')
		echo date("d-M Y H:i",strtotime($objsel['updated_on']));
		?>
		</div>
		</div>
		
		
		<!--<div class="form-group">
			<label class="col-lg-4 control-label-text">Status:</label>
			<div class="col-lg-6">
			<?php echo ($objsel['is_active']!= 1)?'Deactive':'Active'; ?>
			</div>
		</div>-->
		</fieldset>
		</form>