		<?php
		include("../include/config.inc.php");
		include("session.php");
		$converter  = new encryption();
		$dbfunction = new dbfunctions();
		$dbfunction->SimpleSelectQuery("select * from tbl_users where user_id=".$converter->decode($_REQUEST['id']));
		$objsel = $dbfunction->getFetchArray();
		$action = $converter->encode("redeemPoints");
		
		?>
		
		
		<form class="form-horizontal bootstrap-validator-form" method="post">
		<fieldset>
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Customer#:</label>
			<div class="col-lg-6">
			<?php echo $objsel['user_id'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Points:</label>
			<div class="col-lg-6">
			<?php echo $objsel['total_points'];?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4 control-label">Redeem:</label>
			<div class="col-lg-4">
			<input type="text" class="form-control" name="redeemPoints" id="redeemPoints" value="<?php echo $objsel['total_points'];?>" required > 
			</div>
		</div>
		
		<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>">
		<input type="hidden" name="action" value="<?=$action?>">
		<input type="hidden" name="total_points" id="total_points" value="<?=$objsel['total_points']?>">
		<div class="form-group">
			<label class="col-lg-4 control-label"></label>
			<div class="col-lg-4">
			<button tabindex="19" title="Update" class="btn btn-primary" value="submit" id="btnSave" name="save" type="submit" data-bv-field="save1" >Update</button>
			</div>
		</div>
		</fieldset>
		</form>	