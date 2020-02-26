		<?php
		include("../include/config.inc.php");
		include("session.php");
		$converter  = new encryption();
		$dbfunction = new dbfunctions();
		$dbfunction->SimpleSelectQuery("SELECT * FROM tbl_notification_types where is_active='1' AND is_deleted='0' AND is_visible='1'");
		//$objsel = $dbfunction->getFetchArray();
		$action = $converter->encode("notification");
		
		?>
		
		
		<form class="form-horizontal bootstrap-validator-form" method="post">
		<fieldset>
		
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Type:</label>
			<div class="col-lg-6">
			<select class="form-control" name="not_type_id" id="not_type_id" required>
											<option value="">-- Select One --</option>
											<?php
												
												while ($objsel = $dbfunction->getFetchArray())
                                                    {
	
												?>
												
												<option value="<?php echo $objsel["not_type_id"];?>" <?=$isSelected?> ><?php echo stripslashes(trim($objsel["not_type_title"])); ?></option>
												<?php } ?>
			</select>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4 control-label">Notification:</label>
			<div class="col-sm-6 col-md-6 col-lg-6">
			<textarea name="not_text" id="not_text" rows=4 style="width:100%" required ></textarea> 
			</div>
		</div>
		
		
		<input type="hidden" name="action" value="<?=$action?>">
		
		<div class="form-group">
			<label class="col-lg-4 control-label"></label>
			<div class="col-lg-4">
			<button tabindex="19" title="Send" class="btn btn-primary" id="btnSend" name="send" data-bv-field="save1" >Send</button>
			</div>
		</div>
		</fieldset>
		</form>	