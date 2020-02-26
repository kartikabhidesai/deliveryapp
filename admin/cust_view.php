		<?php
		include("../include/config.inc.php");
		include("session.php");
		$converter  = new encryption();
		$dbfunction = new dbfunctions();
		$dbfunction->SimpleSelectQuery("select * from tbl_users where user_id=".$converter->decode($_GET['id']));
		$objsel = $dbfunction->getFetchArray();
		?>
		<form class="form-horizontal bootstrap-validator-form">
		<fieldset>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">CustomerID:</label>
			<div class="col-lg-6">
			<?php echo $objsel['user_id'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Username:</label>
			<div class="col-lg-6">
			<?php echo $objsel['username'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Full Name:</label>
			<div class="col-lg-6">
			<?php echo $objsel['full_name'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Address:</label>
			<div class="col-lg-6">
			<?php echo $objsel['address'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">EmailID:</label>
			<div class="col-lg-6">
			<?php echo $objsel['email_id'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Phone:</label>
			<div class="col-lg-6">
			<?php echo $objsel['phone'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Points:</label>
			<div class="col-lg-6">
			<?php echo $objsel['total_points'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Balance:</label>
			<div class="col-lg-6">
			<?php echo $objsel['wallet_balance'];?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label-text">Activation Code:</label>
			<div class="col-lg-6">
			<?php echo $objsel['activation_code'];?>
			</div>
		</div>
		<?php
		/*$dbfunction1 = new dbfunctions();
		$dbfunction1->SimpleSelectQuery("select avg(custRate) as avgRating from tbl_orders where custRate>0 and user_id='".$objsel['user_id']."'");
		$objsel1 = $dbfunction1->getFetchArray();*/
		?>
		<!--<div class="form-group">
			<label class="col-lg-3 control-label-text">Rating:</label>
			<div class="col-lg-6">
			<?php
			for($i=1;$i <= 10;$i++)
			{
				$j = round($objsel1['avgRating']);
				if($i <= $j)
					echo "<img src='../assets/img/star.png'>";
				else
					echo "<img src='../assets/img/star_grey.png'>";
			} ?>
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