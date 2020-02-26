<?php
//Database Class
//@author Ravi Dhavlesha

class Database
{
	var $dbConnection = null ;
	var $num_records = 0 ;
	var $last_id = 0 ;
	
	function Database ( )
	{
		$this->connect ( ) ;
	}
	
	function get_last_id ( )
	{
		return $this->last_id ;
	}
	
	function get_total_records ( )
	{
		return $this->num_records ; 
	}
	
	function get_record_set ( $qry )
	{
		mysqli_query($this->dbConnection,"SET NAMES 'utf8'"); 
		$records = null ;
		if ( $qry != "" )
		{
			$records = mysqli_query ($this->dbConnection , $qry) or die ( mysqli_error ( ) ) ;
			$this->num_records = mysqli_num_rows ( $records ) ;
		}
		return $records ;
	}
	
	function records_to_array ( $records )
	{
		$array = null ;
		if ( $records != null )
			while ( $row = mysqli_fetch_assoc ( $records ) )
				$array[] = $row ;
		
		mysqli_free_result ( $records ) ;
		$records = null ;
		
		return $array ;
	}
	
	function operation_query ( $qry )
	{
		mysqli_query($this->dbConnection,"SET NAMES 'utf8'"); 
		$records = null ;
		if ( $qry != "" )
		{
			$records = mysqli_query ($this->dbConnection , $qry) or die ( mysqli_error ( )."<br>".$qry ) ;
			$this->last_id = mysqli_insert_id ( $this->dbConnection ) ;
			
		}
		return $records ;
	}
	
	function connect( )
	{
		// This function Connects to database
		if( $this->dbConnection == null )
		{
			$this->dbConnection=mysqli_connect( DB_HOST, DB_USER, DB_PASS ) or die ( "Database connection cannot be found" ) ;
			mysqli_select_db($this->dbConnection,DB_NAME) or die ( "Database cannot be found" ) ;
		}
		return $this->dbConnection ;
		
	}
	
	function disconnect( )
	{
		// This function is used to disconnect this connection to database.
		mysqli_close( $this->dbConnection ) ;
	}
}	
?>