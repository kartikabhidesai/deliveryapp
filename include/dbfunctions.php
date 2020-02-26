<?php
error_reporting(0);	
session_start();
/*
		This class contains all the database related functions which is required to run a query
	*/
	class dbfunctions
	{
		var $dbconnection;
		var $ressel;
		var $temp;
		
		/*
			This functions is a constructor of the class and will be executed on the object creation of this class.
			Arguments: -
		*/
		
		function dbfunctions()
		{
		 	$this->temp = $dbconnection = mysqli_connect(DB_HOST,DB_USER,DB_PASS , DB_NAME);
			if(!$dbconnection){
				die("Error in establishing connection: ".mysqli_error());
				} else {
				//$dblink = mysqli_select_db(DB_NAME,$dbconnection);
				//mysqli_query ("set character_set_client='utf8'"); 
				//mysqli_query ("set character_set_results='utf8'"); 
				//mysqli_query ("set collation_connection='utf8_general_ci'"); 
			}
			mysqli_query($this->temp,"SET NAMES 'utf8';"); 
		}
		
		/*
			This functions is used for the select query.
			@$tablename = Table name
			@fieldlist = Field list
			@where = Where clause
			@orderby = Order by clause
			@groupby = Group by clause
			@limit = Limit for the query
			@echoquery = Print query for debugging.
		*/
		
		function SelectQuery($tablename, $fieldlist, $where = '', $orderby = '', $groupby = '', $limit='', $echoquery = 0)
		{
			mysqli_query($this->temp,"SET NAMES 'utf8'"); 
			//mysqli_query('SET CHARACTER SET utf8'); 
			$qrysel = "SELECT $fieldlist FROM $tablename ".($where!=""?" WHERE $where":"")." $groupby $orderby $limit";
			if($echoquery==1){ echo $qrysel; }
			$this->ressel = mysqli_query($this->temp,$qrysel);
			
		}
		
		function Query($query)
		{
			
			$this->ressel = mysqli_query($this->temp,$query);
			
		}
		
		function SimpleSelectQuery($selqry)
		{
			mysqli_query("SET NAMES 'utf8'"); 
			//mysqli_query('SET CHARACTER SET utf8'); 
			$qrysel = $selqry;
			$this->ressel = mysqli_query($this->temp,$qrysel) or die(mysqli_error());
		}
		
		/*
			This functions is used for get the number of rows on the current query.
		*/
		function getNumRows()
		{
			$totalnumberofrows = mysqli_num_rows($this->ressel);
			return $totalnumberofrows;
		}
		
		function GenerateSelectQuery($tablename, $fieldlist, $where = '', $orderby = '', $groupby = '', $limit='', $echoquery = 0)
		{
			$qrysel = "SELECT $fieldlist FROM $tablename ".($where!=""?" WHERE $where":"")." $groupby $orderby $limit";
			return $qrysel;
		}
		
		/*
			This functions is used for get the number of rows on the current query.
		*/
		function getFetchArray()
		{
			$returnarray = mysqli_fetch_array($this->ressel,MYSQLI_ASSOC);
			return $returnarray;
		}
		
		/*
			This functions is used for get the number of rows on the current query.
		*/
		function getAffectedRows()
		{
			$affectedrows = mysqli_affected_rows();
			return $affectedrows;
		}
		
		/*
			This functions is used for get the number of rows on the current query.
			@tablename = Table name
			@fieldsarray = Fields array with key and values - key = fieldname - value = value
		*/
		function InsertQuery($tablename, $fieldsarray)
		{
			mysqli_query($this->temp,"SET NAMES 'utf8'"); 
			$qryins = "Insert into $tablename ";
			
			$allkeys = "(";
			$allvalues = "(";
			
			foreach($fieldsarray as $key => $value)
			{
				$allkeys .= "`".$key."`".",";
				$allvalues .= "'".mysqli_real_escape_string($this->temp,$value)."',";
			}
			$allkeys = substr($allkeys,0,-1).")";
			$allvalues = substr($allvalues,0,-1).")";
			
			$qryins .= $allkeys." VALUES ".$allvalues;
			mysqli_query($this->temp,$qryins) or die(mysqli_error());
		}
		
		/*
			This functions is used for get the last inserted id from the insert query.
		*/
		function getLastInsertedId()
		{
			$lastinsertid = mysqli_insert_id($this->temp);
			return $lastinsertid;
		}
		
		/*
			This functions is used for get the number of rows on the current query.
			@tablename = Table name
			@fieldsarray = Fields array with key and values - key = fieldname - value = value
		*/
		function UpdateQuery($tablename, $fieldsarray, $where = '')
		{
			mysqli_query($this->temp,"SET NAMES 'utf8'"); 
			$qryupd = "Update $tablename set ";
			
			foreach($fieldsarray as $key => $value)
			{
				$qryupd .= '`'.$key.'`="'.mysqli_real_escape_string($this->temp,$value).'",';
			}
			$qryupd = substr($qryupd,0,-1)." ".($where!=""?"Where $where":"");
			mysqli_query($this->temp,$qryupd) or die(mysqli_error());
		}
		
		function db_safe()
		{
			$arguments =  func_get_args();
			$returnquery = $arguments[0];
			for($i = 1; $i < count($arguments); $i++){
				$returnquery = str_replace("%".$i,mysqli_real_escape_string($this->temp,$arguments[$i]),$returnquery);
			}
			return $returnquery;
		}
		
		function __destruct() {
			@mysqli_close();    
		}
		
		function DeleteQuery($tablename,$where='') 
		{
			$this->sql	= "DELETE FROM  ".$tablename;
			
			if($where != '')
			{
				$this->sql .=" WHERE ".$where." ";
			}
			//echo $this->sql;echo"<br>";exit;
			$this->result	= mysqli_query($this->temp,$this->sql);
			return $this->result;
		}

		//  Add Avtivity Log Data
		function InsertActivityQuery($tablename, $fieldsarray)
		{
			mysqli_query($this->temp,"SET NAMES 'utf8'"); 
			$qryins = "Insert into $tablename ";
			
			$allkeys = "(";
			$allvalues = "(";
			foreach($fieldsarray as $key => $value)
			{
				$allkeys .= "`".$key."`".",";
				$allvalues .= "'".mysqli_real_escape_string($this->temp,$value)."',";
			}
			$allkeys = substr($allkeys,0,-1).")";
			$allvalues = substr($allvalues,0,-1).")";
			
			$qryins .= $allkeys." VALUES ".$allvalues;
			mysqli_query($this->temp,$qryins) or die(mysqli_error());
		}	
	}
?>