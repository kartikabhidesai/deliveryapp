		<?php
		include("../include/config.inc.php");
		include("session.php");
		$converter  = new encryption();
		$dbfunction = new dbfunctions();
		$dbfunction->SimpleSelectQuery("select * from tbl_orders where invoice_no=".$converter->decode($_REQUEST['id']));
		$objsel = $dbfunction->getFetchArray();
		$action = $converter->encode("cancelOrder");
		
		?>
		
		
		<form class="form-horizontal bootstrap-validator-form" method="post" >
		<fieldset>
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Order#:</label>
			<div class="col-lg-6">
			<?php echo $objsel['invoice_no'];?>
			</div>
		</div>
		
		
		<div class="form-group">
			<label class="col-lg-4 control-label">Reason:</label>
			<div class="col-lg-4">
			<textarea class="form-control" name="cancel_reason" required > <?php echo $objsel['cancel_reason'];?></textarea>
			</div>
		</div>
		
		<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>">
		<input type="hidden" name="action" value="<?=$action?>">
		<div class="form-group">
			<label class="col-lg-4 control-label"></label>
			<div class="col-lg-4">
			<button tabindex="19" title="Update" class="btn btn-primary" value="submit" id="save" name="save" type="submit" data-bv-field="save1">Update</button>
			</div>
		</div>
		</fieldset>
		</form>	