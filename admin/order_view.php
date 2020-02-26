		<?php
		include("../include/config.inc.php");
		include("session.php");
		$converter  = new encryption();
		$dbfunction = new dbfunctions();
		$dbfunction1 = new dbfunctions();
		$dbfunction->SimpleSelectQuery("select * from tbl_orders where invoice_no='".$converter->decode($_GET['id'])."'");
		$objsel = $dbfunction->getFetchArray();
		?>
		
		<form class="form-horizontal bootstrap-validator-form">
		<fieldset>
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Order#:</label>
			<div class="col-lg-6">
			<?php echo $objsel['invoice_no'];?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Order Status:</label>
			<div class="col-lg-6">
			<?php echo $objsel['order_status'];?>
			</div>
		</div>
		
		<?php if($objsel['cancel_reason']!='') { ?>
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Cancel Reason:</label>
			<div class="col-lg-6">
			<?php echo $objsel['cancel_reason'];?>
			</div>
		</div>
		<?php } ?>
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Customer Name:</label>
			<div class="col-lg-6">
			<?php echo $objsel['customer_name'];?>
			</div>
		</div>
		
		<!--<div class="form-group">
			<label class="col-lg-4 control-label-text">Store ID:</label>
			<div class="col-lg-6">
			<?php echo $objsel['store_id'];?>
			</div>
		</div>-->
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Store Name:</label>
			<div class="col-lg-6">
			<?php echo $objsel['store_name'];?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Delivery Address:</label>
			<div class="col-lg-6">
			<?php echo $objsel['delivery_address'];?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Order Size:</label>
			<div class="col-lg-6">
			<?php echo ucfirst($objsel['order_size']);?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Order Details:</label>
			<div class="col-lg-6">
			<?php echo $objsel['order_details'];?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Delivery Fee:</label>
			<div class="col-lg-6">
			<?php echo $objsel['delivery_fee'];?>
			</div>
		</div>
		
		<!--<div class="form-group">
			<label class="col-lg-4 control-label-text">Captain Tip:</label>
			<div class="col-lg-6">
			<?php echo $objsel['captain_tip'];?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Sub Total:</label>
			<div class="col-lg-6">
			<?php echo $objsel['sub_total'];?>
			</div>
		</div>-->
		<?php if($objsel['discount']>0) { ?>
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Discount:</label>
			<div class="col-lg-6">
			<?php echo $objsel['discount'];?>
			</div>
		</div>
		<?php } ?>
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Total:</label>
			<div class="col-lg-6">
			<?php echo $objsel['grand_total'] . ' ('.$objsel['payment_type'].')';?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Customer Points:</label>
			<div class="col-lg-6">
			<?php echo $objsel['customer_points'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Captain Points:</label>
			<div class="col-lg-6">
			<?php echo $objsel['earning_points'];?>
			</div>
		</div>
		<?php if($objsel['captain_name']!='') { ?>
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Captain Name:</label>
			<div class="col-lg-6">
			<?php echo $objsel['captain_name'];?>
			</div>
		</div>
		<?php } ?>
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Order Time:</label>
			<div class="col-lg-6">
			<?php echo date("d/m/Y h:i a",strtotime($objsel['created_on']));?>
			</div>
		</div>
		
		<?php if($objsel['delivered_on']!='' && $objsel['delivered_on']!='0000-00-00 00:00:00') { ?>
		<div class="form-group">
			<label class="col-lg-4 control-label-text">Delivered on:</label>
			<div class="col-lg-6">
			<?php echo (strpos($objsel['delivered_on'], ' ') !== false)?$objsel['delivered_on']:date('d/m/Y h:i a',strtotime($objsel['delivered_on']));?>
			</div>
		</div>
		<?php } ?>	
	</fieldset> 
		
	</form>	