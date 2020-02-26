		<?php
		include("../include/config.inc.php");
		include("session.php");
		$converter  = new encryption();
		$dbfunction = new dbfunctions();
		$dbfunction->SimpleSelectQuery("select * from tbl_users where user_id=".$converter->decode($_GET['id']));
		$objsel = $dbfunction->getFetchArray();
		$dbfunction1 = new dbfunctions();
		$dbfunction1->SimpleSelectQuery("select count(*) as total_order, sum(grand_total) as total_amount from tbl_orders 
		where captain_id='".$objsel["user_id"]."' and order_status='Delivered'");
		$objsel1 = $dbfunction1->getFetchArray();
		?>
		<form class="form-horizontal bootstrap-validator-form">
		<fieldset>
		<!--<div class="form-group">
			<label class="col-lg-4  control-label-text">CaptainID:</label>
			<div class="col-lg-6">
			<?php echo $objsel['user_id'];?>
			</div>
		</div>-->
		
		<div class="form-group">
			<label class="col-lg-4  control-label-text">Name:</label>
			<div class="col-lg-6">
			<?php echo $objsel['captain_name'];?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4  control-label-text">City:</label>
			<div class="col-lg-6">
			<?php echo $objsel['captain_city'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4  control-label-text">Phone:</label>
			<div class="col-lg-6">
			<?php echo $objsel['phone'];?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4  control-label-text">Profile Pic:</label>
			<div class="col-lg-6">
			<a target="_blank" href="<?= BASE_URL ?>uploads/captain/<?=$objsel['captain_pic'];?>">
			<img src="<?= BASE_URL ?>uploads/captain/<?=$objsel['captain_pic'];?>" width="100" height="100"/>
			</a>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4  control-label-text">Captain Id:</label>
			<div class="col-lg-6">
			<a target="_blank" href="<?= BASE_URL ?>uploads/captain/<?=$objsel['captain_id_pic'];?>">
			<img src="<?= BASE_URL ?>uploads/captain/<?=$objsel['captain_id_pic'];?>" width="100" height="100"/>
			</a>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-4  control-label-text">License:</label>
			<div class="col-lg-6">
			<a target="_blank" href="<?= BASE_URL ?>uploads/captain/<?=$objsel['captain_license'];?>">
			<img src="<?= BASE_URL ?>uploads/captain/<?=$objsel['captain_license'];?>" width="100" height="100"/>
			</a>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4  control-label-text">Total order delivered:</label>
			<div class="col-lg-6">
			<?php echo $objsel1['total_order'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4  control-label-text">Total order Amount:</label>
			<div class="col-lg-6">
			<?php echo $objsel1['total_amount'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4  control-label-text">Total order points:</label>
			<div class="col-lg-6">
			<?php echo $objsel['total_captain_points'];?>
			</div>
		</div>
		
		<!--<div class="form-group">
			<label class="col-lg-4  control-label-text">Status:</label>
			<div class="col-lg-6">
			<?php echo $objsel['captain_status']; ?>
			</div>
		</div>--
		</fieldset>
		</form>	