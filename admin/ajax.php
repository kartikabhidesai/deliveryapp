<?php
	include("../include/config.php");
	include("session.php");
	$generalFunction = new generalfunction();
	$converter  = new encryption();
	$dbfunction   = new dbfunctions();
	
	function dateFormat($date)
    {
		$date = date_create($date);
		return date_format($date, "m/d/Y");
	}
	if(isset($_GET['CinemaId']) && $_GET['CinemaId']!="")
	{
		$CinemaId = $_GET['CinemaId'];
		$TheatreId = $_GET['TheatreId'];
		$Query  = "SELECT TheatreId,TheatreName FROM ManageTheatre WHERE CinemaId='".$CinemaId."' AND IsActive='1' AND IsDeleted='0' ORDER BY TheatreName ASC";
		$dbfunction->SimpleSelectQuery($Query);
		$numRows = $dbfunction->getNumRows();
		if($numRows > 0)
		{
		?>
		<select class="select_fild" name="TheatreId" id="TheatreId" tabindex="3" style="width:248px">
		<option value="">Select A Theatre</option>
			<?php 
				while($datas = $dbfunction->getFetchArray())
				{
				?>
				<option value="<?php echo $datas['TheatreId']; ?>" <?php echo $TheatreId==$datas['TheatreId']?"selected='selected'":"" ?>><?php echo $datas['TheatreName']; ?></option>
				<?php
				}
			?>
		</select>
		<?php
		}
		else
		{
		?>
		<select  class="select_fild" name="TheatreId" id="TheatreId" tabindex="3" style="width:248px">
			<option value="">No Theatre found</option>
		</select>
		<?php
			
		}
	}
	
?>