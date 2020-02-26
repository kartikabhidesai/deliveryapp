<?php

class Export{
		function exportff($rows,$excelheader)
			{
			    $filename="failedimportdata";
				$file_ending = "xls";
				header("Content-Type: application/xls");    
				header("Content-Disposition: attachment; filename=$filename.xls");  
				header("Pragma: no-cache"); 
				header("Expires: 0");
				$sep="\t";
				//print "\n";
				for ($i = 0; $i < count($excelheader); $i++) {
						echo trim($excelheader[$i]) . "\t";
				}
				print "\n";
				foreach($rows as $row)
						{
							$schema_insert = "";
							$lenrow = count($row);
							for($x=0;$x<$lenrow;$x++)
								{
								
									if(!isset($row[$x]))
										 $schema_insert .= "NULL".$sep;
									else if ($row[$x] != "")
										$schema_insert .= "$row[$x]".$sep;
									else
										$schema_insert .= "".$sep;
								}
							
							$schema_insert = str_replace($sep."$", " ", $schema_insert);
							$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
							$schema_insert .= "\t";	
							print($schema_insert);
							print "\n";
						}	exit;									
			}
			}