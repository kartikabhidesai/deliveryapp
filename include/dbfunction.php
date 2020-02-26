<?php
//21232f297a57a5a743894a0e4a801fc3

//EO9gxFGYZz6BNoLLTQ6_U5D4hNYVU3rkUTZPpLgAP9I
	
	class dbfunction
    {
		
		var $dbconnection1;
		var $ressel;
		
    	function dbfunction()
        {
			$this->dbconnection1 = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if (!$this->dbconnection1)
            {
				die("Error in establishing connection: " . mysqli_error());
			}
			else
            {
				
			}
		}
		
		function SelectQuery($tablename, $fieldlist, $where = '', $orderby = '', $groupby = '', $limit = '', $echoquery = 0)
        {
		
			$qrysel = "SELECT $fieldlist FROM $tablename " . ($where != "" ? " WHERE $where" : "") . " $groupby $orderby $limit";
			if ($echoquery == 1)
            {
				echo $qrysel;
			}
			$this->ressel = mysqli_query($this->dbconnection1, $qrysel) or die(mysqli_error($this->dbconnection1));
		}
		
		function Selectrestbylatlon($lat,$lon,$limit1,$limit2)
        {
			$latitude = $lat;
			$longitude = $lon;
		  	if($limit1 != '' && $limit2 != '')
			{
			$qrysel = "SELECT RestaurantName,RestaurantId AS RestaurantIdS,(SELECT AVG(Rating) FROM RestaurantRatings WHERE RestaurantId=RestaurantIdS) as avgRating,RestaurantCode,RestaurantIcon,RestaurantAddress,Latitude,Longitude,place_id,Open_Time,Close_Time,international_phone_number,( 3959 * acos( cos( radians('$latitude') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians( latitude ) ) ) ) AS distance FROM RestaurantDetails  WHERE IsActive='1' AND IsDeleted='0' HAVING distance < 3 order by distance DESC limit $limit1,$limit2";
			}
			else{
			$qrysel = "SELECT RestaurantName,RestaurantId AS RestaurantIdS,(SELECT AVG(Rating) FROM RestaurantRatings WHERE RestaurantId=RestaurantIdS) as avgRating,RestaurantCode,RestaurantIcon,RestaurantAddress,Latitude,Longitude,place_id,Open_Time,Close_Time,international_phone_number,( 3959 * acos( cos( radians('$latitude') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians( latitude ) ) ) ) AS distance FROM RestaurantDetails  WHERE IsActive='1' AND IsDeleted='0' HAVING distance < 3 order by distance DESC";
			}


			$res = mysqli_query($this->dbconnection1, $qrysel) or die(mysqli_error($this->dbconnection1));
			while($returnarray = mysqli_fetch_assoc($res))
			{ 
			 $arr[]=$returnarray;
			}
			return $arr;
		 
		}
		
		function getInsertdata($limit1,$limit2)
        {
			$qrysel = "select ManageSpecialOffers.RestaurantId,ManageSpecialOffers.SpecialOffersId,RestaurantDetails.RestaurantName, RestaurantDetails.RestaurantIcon, RestaurantDetails.Latitude, RestaurantDetails.Longitude ,ManageSpecialOffers.OfferDesc, ManageSpecialOffers.OfferImage,ManageSpecialOffers.OfferImage2,ManageSpecialOffers.OfferImage3 FROM ManageSpecialOffers INNER JOIN RestaurantDetails on ManageSpecialOffers.RestaurantId = RestaurantDetails.RestaurantId WHERE ManageSpecialOffers.IsActive='1' AND ManageSpecialOffers.IsDeleted='0' AND RestaurantDetails.IsActive='1' AND RestaurantDetails.IsDeleted='0' order by ManageSpecialOffers.SpecialOffersId DESC limit $limit1,$limit2";
			//AND RestaurantDetails.RestaurantType='2'
			$res = mysqli_query($this->dbconnection1, $qrysel) or die(mysqli_error($this->dbconnection1));
			while($returnarray = mysqli_fetch_assoc($res))
			{ 
				$arr[]=$returnarray;
			}
			return $arr;
		 
		}
		
		
		
		/*created by alok*/
		function SelectCheckQuery($tablename, $fieldlist, $where = '', $orderby = '', $groupby = '', $limit = '', $echoquery = 0)
        {
	     $qrysel = "SELECT $fieldlist FROM $tablename " . ($where != "" ? " WHERE $where" : "") . " $groupby $orderby $limit";
		 $res = mysqli_query($this->dbconnection1, $qrysel) or die(mysqli_error($this->dbconnection1));
		 $returnarray = mysqli_fetch_array($res);
		 return $returnarray;
		 
		}	
		
		function getLastInsertId()
        {
		    $lastid="SELECT * FROM UserMaster ORDER BY UserId DESC LIMIT 1";
			$lastresult = mysqli_query($this->dbconnection1, $lastid) or die(mysqli_error($this->dbconnection1));
			$returnarray = mysqli_fetch_array($lastresult);
		    return $returnarray;
		 }
		 
		function getLastInsertdata($tbl_name,$ord_by)
        {
		    $lastid="SELECT * FROM $tbl_name ORDER BY `$ord_by` DESC LIMIT 1";
			$lastresult = mysqli_query($this->dbconnection1, $lastid) or die(mysqli_error($this->dbconnection1));
			//$returnarray = mysqli_fetch_array($lastresult);
			$returnarray = mysqli_fetch_assoc($lastresult);
		    return $returnarray;
		 }
		
		
		function getAdvertisement()
        {
		    $lastid="SELECT * FROM advertisements WHERE IsActivate=1 AND IsDeleted=0 ORDER BY AdvertiseId DESC";
			$lastresult = mysqli_query($this->dbconnection1, $lastid) or die(mysqli_error($this->dbconnection1));
			while($returnarray = mysqli_fetch_array($lastresult))
			{
			 $data['AdvertiseId']=$returnarray['AdvertiseId'];
			 $data['AdvertiseTitle']=$returnarray['AdvertiseTitle'];
		     $data['AdvertiseImage']=$returnarray['AdvertiseImage'];
		     $data['AdvertiseDescription']=$returnarray['AdvertiseDesc'];
			 $d[]=$data;
			 $data='';
		    }
			return $d;
		 } 
		/*created by alok*/
		
		function getProcedure($procedure, $params = "")
        {
			$proce = "Call " . $procedure;
			$paran1 = "(";
			$paran2 = ")";
			$arrayVal = "";
			
			if (count($params) > 0)
            {
				if (is_array($params))
                {
					foreach ($params as $key => $val)
                    {
						if ($arrayVal == "")
                        {
							$arrayVal = '"' . $val . '"';
						}
						else
                        {
							$arrayVal .= ',"' . $val . '"';
						}
					}
				}
				
				$getproce = $proce . $paran1 . $arrayVal . $paran2;
			}
			else
            {
				$getproce = $proce . $paran1 . $params . $paran2;
			}
			
			
			$this->ressel = mysqli_query($this->dbconnection1, $getproce);
			if (!$this->ressel)
            {
				die(mysqli_error($this->dbconnection1));
			}
		}
		
		function Query($query)
        {
			$this->ressel = mysqli_query($query);
		}
		
		function SimpleSelectQuery($selqry)
        {
			$qrysel = $selqry;
			$this->ressel = mysqli_query($this->dbconnection1, $qrysel) or die(mysqli_error($this->dbconnection1));
		}
		
		/*
			This functions is used for get the number of rows on the current query.
		*/
		
		function getNumRows()
        {
			$totalnumberofrows = mysqli_num_rows($this->ressel);
			return $totalnumberofrows;
		}
		
		/*
			This functions is used for get the number of rows on the current query.
		*/
		
		function getFetchArray()
        {
			$returnarray = mysqli_fetch_array($this->ressel, MYSQLI_ASSOC);
			return $returnarray;
		}
		
		/*
			This functions is used for get the number of rows on the current query.
		*/
		
		function getAffectedRows()
        {
			$affectedrows = mysqli_affected_rows($this->ressel);
			return $affectedrows;
		}
		
		/*
			This functions is used for get the number of rows on the current query.
			@tablename = Table name
			@fieldsarray = Fields array with key and values - key = fieldname - value = value
		*/
		
		function InsertQuery($tablename, $fieldsarray)
        {
           	//mysqli_query("INSERT INTO UserMaster('FirstName') VALUES ('alok')");	
			$qryins = "Insert into $tablename ";
			
			$allkeys = "(";
			$allvalues = "(";
			
			foreach ($fieldsarray as $key => $value)
            {
				$allkeys .= $key . ",";
				$allvalues .= "'" . addslashes($value) . "',";
			}
			$allkeys = substr($allkeys, 0, -1) . ")";
			$allvalues = substr($allvalues, 0, -1) . ")";
			
			$qryins .= $allkeys . " VALUES " . $allvalues;
			//mysqli_query($qryins) or die(mysqli_error());
			$this->ressel = mysqli_query($this->dbconnection1, $qryins) or die(mysqli_error($this->dbconnection1));
			
		}
		
		/*
			This functions is used for get the last inserted id from the insert query.
		*/
		
		function getLastInsertedId()
        {
			
			$lastinsertid = mysqli_insert_id($this->dbconnection1);
			return $lastinsertid;
		}
/*created by alok*/
		function getallreview($id)
        {

		
	 	$query="SELECT RestaurantDetails.RestaurantCode,RestaurantReview.Review,RestaurantReview.ReviewImage1,(case when floor(time_to_sec(timediff(now(),RestaurantReview.ReviewDate))/60) >= '60' && floor(time_to_sec(timediff(now(),RestaurantReview.ReviewDate))/3600) < '24' then concat(floor(time_to_sec(timediff(now(),RestaurantReview.ReviewDate))/3600),' hr(s) ago') when floor(time_to_sec(timediff(now(),RestaurantReview.ReviewDate))/3600) >= '24' then concat(floor(time_to_sec(TIMEDIFF(now(),RestaurantReview.ReviewDate))/86400),' day(s) ago') WHEN floor(time_to_sec(timediff(now(),RestaurantReview.ReviewDate))/60) = '0' then 'Just now'  else concat(floor(time_to_sec(timediff(now(),RestaurantReview.ReviewDate))/60),' min(s) ago') end) as ReviewDate,RestaurantReview.RestaurantReviewId,concat_ws(' ',UserMaster.FirstName,UserMaster.LastName) AS Name,UserMaster.UserId,
UserMaster.ProfileImage,UserMaster.UserName FROM RestaurantDetails INNER JOIN RestaurantReview ON RestaurantDetails.RestaurantId=RestaurantReview.RestaurantId INNER JOIN UserMaster ON RestaurantReview.UserId=UserMaster.UserId WHERE  RestaurantReview.RestaurantId=$id AND UserMaster.IsActive='1' AND RestaurantReview.IsActive='1' AND RestaurantReview.IsDeleted='0' and UserMaster.IsDeleted='0' order by RestaurantReview.RestaurantReviewId DESC";

			$result = mysqli_query($this->dbconnection1, $query) or die(mysqli_error($this->dbconnection1));
			while($returnarray = mysqli_fetch_assoc($result))
			{
			  $data['returnarray']=$returnarray;
			  $value[]=$data;
			}
			return $value;
		 
}		
	function getcheckedIn($id)
        {
		
		$query=("SELECT RestaurantDetails.RestaurantCode,RestaurantDetails.RestaurantId,concat_ws(' ',UserMaster.FirstName,UserMaster.LastName) AS Name,UserMaster.UserId,
UserMaster.ProfileImage,UserMaster.UserName,CheckIn.CheckInId,(case when floor(time_to_sec(timediff(now(),CheckIn.CheckedInTime))/60) >= '60' && floor(time_to_sec(timediff(now(),CheckIn.CheckedInTime))/3600) < '24' then concat(floor(time_to_sec(timediff(now(),CheckIn.CheckedInTime))/3600),' hr(s) ago') when floor(time_to_sec(timediff(now(),CheckIn.CheckedInTime))/3600) >= '24' then concat(floor(time_to_sec(TIMEDIFF(now(),CheckIn.CheckedInTime))/86400),' day(s) ago') WHEN floor(time_to_sec(timediff(now(),CheckIn.CheckedInTime))/60) = '0' then 'Just now'  else concat(floor(time_to_sec(timediff(now(),CheckIn.CheckedInTime))/60),' min(s) ago') end) AS CheckedInTime FROM RestaurantDetails INNER JOIN CheckIn ON RestaurantDetails.RestaurantId=CheckIn.RestaurantId INNER JOIN UserMaster ON CheckIn.UserId=UserMaster.UserId WHERE CheckIn.RestaurantId=$id AND CheckIn.IsCheckedIn='1' AND UserMaster.IsActive='1' AND UserMaster.IsDeleted='0' ORDER BY CheckIn.CheckInId DESC");

			$result = mysqli_query($this->dbconnection1, $query) or die(mysqli_error($this->dbconnection1));
			while($returnarray = mysqli_fetch_assoc($result))
			{
			  $data['returnarray']=$returnarray;
			  $value[]=$data;
			}
			return $value;
}		


/*created by alok*/
		
		/* function getLastInsertedId()
			{
			$lastinsertid = mysqli_insert_id();
			return $lastinsertid;
		} */
		
		/*
			This functions is used for get the number of rows on the current query.
			@tablename = Table name
			@fieldsarray = Fields array with key and values - key = fieldname - value = value
		*/
		
		function UpdateQuery($tablename, $fieldsarray, $where = '')
        {
		
			$qryupd = "Update $tablename set ";
			
			foreach ($fieldsarray as $key => $value)
            {
				//$qryupd .= $key . "='" . mysqli_real_escape_string($value) . "',";
				$qryupd .= $key . "='" .$value. "',";
			}
			
			$qryupd = substr($qryupd, 0, -1) . " " . ($where != "" ? "Where $where" : "");
			$this->ressel = mysqli_query($this->dbconnection1, $qryupd) or die(mysqli_error($this->dbconnection1));
		}
		
		function UpdaterestaurantQuery($tablename, $fieldsarray, $where = '')
        {
		
			$qryupd = "Update $tablename set ";
			
			foreach ($fieldsarray as $key => $value)
            {
				$qryupd .= $key . "='" . mysqli_real_escape_string($value) . "',";
				//$qryupd .= $key . "='" .$value. "',";
			}
			
			$qryupd = substr($qryupd, 0, -1) . " " . ($where != "" ? "Where $where" : "");
			$this->ressel = mysqli_query($this->dbconnection1, $qryupd) or die(mysqli_error($this->dbconnection1));
		}
		
		
		
		function db_safe()
        {
			$arguments = func_get_args();
			$returnquery = $arguments[0];
			for ($i = 1; $i < count($arguments); $i++)
            {
				$returnquery = str_replace("%" . $i, mysqli_real_escape_string($arguments[$i]), $returnquery);
			}
			return $returnquery;
		}
		
		function getOutPutParamArrayData($Query, $OutPutParam = FALSE)
        {
			$this->SimpleSelectQuery("$Query");
			if ($OutPutParam === false)
            {
				$numRows = $this->getNumRows();
				if ($numRows > 0)
                {
					$ArrayData = array();
					foreach ($this->getFetchArray() as $key => $val)
                    {
						$ArrayData[$key] = $val;
					}
					return $ArrayData;
				}
				else
                {
					return FALSE;
				}
			}
			else
            {
				$this->SimpleSelectQuery("$OutPutParam");
				$ArrayData = array();
				$AllData = $this->getFetchArray();
				foreach ($AllData as $key => $val)
                {
					$ArrayData[$key] = $val;
				}
				return $ArrayData;
			}
		}
		
		function __destruct()
        {
			@mysqli_close();
		}
		
		function DeleteQuery($tablename, $where = '')
        {
			$this->sql = "DELETE FROM  " . $tablename;
			
			if ($where != '')
            {
				$this->sql .=" WHERE " . $where . " ";
			}
			//echo $this->sql;echo"<br>";exit;
			$this->result = mysqli_query($this->sql);
			return $this->result;
		}
		
		function getArrayData($Query, $OutPutParam = FALSE)
        {
			$this->SimpleSelectQuery("$Query");
			if ($OutPutParam === false)
            {
				$numRows = $this->getNumRows();
				if ($numRows > 0)
                {
					$ArrayData = array();
					foreach ($this->getFetchArray() as $key => $val)
                    {
						$ArrayData[$key] = $val;
					}
					return $ArrayData;
				}
				else
                {
					return FALSE;
				}
			}
			else
            {
				//            only for single row not for multiple row
				$this->SimpleSelectQuery("$OutPutParam");
				$ArrayData = array();
				$AllData = $this->getFetchArray();
				//            put the return int always first
				if (intval(current($AllData)) > 0)
                {
					foreach ($AllData as $key => $val)
                    {
						$ArrayData[$key] = $val;
					}
				}
				else
                {
					return FALSE;
				}
				return $ArrayData;
			}
		}
		function getCountryArrayData($Query,$DictionaryKey)
        {
			$this->SimpleSelectQuery("$Query");
			$numRows = $this->getNumRows();
			$array[$DictionaryKey] = array();
			if ($numRows > 0)
            {
				$i = 0;
				while ($countryData = $this->getFetchArray())
                {
					$keyname = array_keys($countryData);
					foreach ($keyname as $key => $val)
                    {
						$array[$DictionaryKey][$i][$val] = trim($countryData[$val],"\n");
					}
					$i++;
				}
				return $array;
			}
			else
			{
				return FALSE;
			}
		}
		function getTravelImagesArrayData($Query, $DictionaryKey)
        {
			$this->SimpleSelectQuery("$Query");
			$numRows = $this->getNumRows();
			$array[$DictionaryKey] = array();
			$arrays = array();
			if ($numRows > 0)
            {
				$i = 0;
				while ($countryData = $this->getFetchArray())
                {
					$keyname = array_keys($countryData);
					// $array[$DictionaryKey]['TheatreDetails'][$countryData['TheatreId']]['MovieDetails'] = array();
					foreach ($keyname as $key => $val)
                    {
						// $array[$DictionaryKey][$val] = $countryData[$val];
						$array[$DictionaryKey]['TheatreDetails'][$countryData['TheatreId']]['TheatreId'] = $countryData['TheatreId'];
						$array[$DictionaryKey]['TheatreDetails'][$countryData['TheatreId']]['Latitude'] = $countryData['Latitude'];
						$array[$DictionaryKey]['TheatreDetails'][$countryData['TheatreId']]['Longitude'] = $countryData['Longitude'];
						$array[$DictionaryKey]['TheatreDetails'][$countryData['TheatreId']]['TheatreAddress'] = $countryData['TheatreAddress'];
						
						$array[$DictionaryKey]['TheatreDetails'][$countryData['TheatreId']]['MovieDetails'][$i]['MovieName'] = $countryData['MovieName'];
						/*$array[$DictionaryKey]['TheatreDetails'][$countryData['TheatreId']]['MovieDetails'][$countryData['TheatreId']][$i]['MovieUrl'] = $countryData['MovieUrl'];
							$array[$DictionaryKey]['TheatreDetails'][$countryData['TheatreId']]['MovieDetails'][$countryData['TheatreId']][$i]['MovieDesc'] = $countryData['MovieDesc'];
							$array[$DictionaryKey]['TheatreDetails'][$countryData['TheatreId']]['MovieDetails'][$countryData['TheatreId']][$i]['ReleaseDate'] = $countryData['ReleaseDate'];
							$array[$DictionaryKey]['TheatreDetails'][$countryData['TheatreId']]['MovieDetails'][$countryData['TheatreId']][$i]['MovieId'] = $countryData['MovieId'];
						$array[$DictionaryKey]['TheatreDetails'][$countryData['TheatreId']]['MovieDetails'][$countryData['TheatreId']][$i]['ShowTime'] = $countryData['ShowTime'];*/
						// if(intval($countryData['TravelImagesId']) > 0)
						// {
						// $array[$DictionaryKey]['VisitedImages'][$countryData['TravelImagesId']]['TravelImagesId'] = $countryData['TravelImagesId'];
						// $array[$DictionaryKey]['VisitedImages'][$countryData['TravelImagesId']]['TravelImages'] = $countryData['TravelImages'];
						// }
					}
					$i++;
				}
				return $array;
			}
			else
			{
				return FALSE;
			}
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