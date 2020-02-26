<?php
	class paging_review
	{
		var $sql,$records,$pages,$sqlarray,$TableName,$FieldsName,$Condition,$ByOrder,$RecLimit;
		/*
			
			Variables that are passed via constructor parameters
			
		*/
		
		var $page_no,$total,$limit,$first,$previous,$next,$last,$start,$end, $totalrecords;
		
		/*
			
			Variables that will be computed inside constructor
			
		*/
		
		var $dbfunction;
		
		function paging_review($sqlarray=array(),$records=15,$pages=10,$short="")
		
		{
			$RecLimit = "";
			
			if(isset($sqlarray[0]) && $sqlarray[0] != "")
			{
				$TableName = $sqlarray[0];
			}
			if(isset($sqlarray[1]) && $sqlarray[1] != "")
			{
				$FieldsName = $sqlarray[1];
			}
			if(isset($sqlarray[2]) && $sqlarray[2] != "")
			{
				$Condition = $sqlarray[2];
			}
			if(isset($sqlarray[3]) && $sqlarray[3] != "")
			{
				$ByOrder = $sqlarray[3];
			}
			if(isset($sqlarray[4]) && $sqlarray[4] != "")
			{
				$RecLimit = $sqlarray[4];
			}
			$sql = "SELECT ".$FieldsName." FROM ".$TableName." ".($Condition!=""?" WHERE ".$Condition."":"")." ".$ByOrder." ".$RecLimit;
			
			
			if($pages%2==0)
			
			$pages++;
			
			/*
				
				The pages should be odd not even
				
			*/
			
			$res=mysql_query($sql) or die($sql." - ".mysql_error());
			
			$total=mysql_num_rows($res);
			
			$totalrecords = $total;
			
			$page_no = isset($_GET["pgno1"])? (int) ($_GET["pgno1"]):1;
			
			if($page_no<=0){ $page_no = 1; }
			
			if($page_no>ceil($total/$records)){ if(ceil($total/$records) > 0) { $page_no = ceil($total/$records);} else{ $page_no = 1; } }
			
			/*
				
				Checking the current page
				
				If there is no current page then the default is 1
				
			*/
			$limit=($page_no-1)*$records;
			
			$limit1 = ($page_no)*$records;
			
			$sql.=" limit $limit,$records";
			
			/*
				
				The starting limit of the query
				
			*/
			
			$first=1;
			
			$previous=$page_no>1?$page_no-1:1;
			
			$next=$page_no+1;
			
			$last=ceil($total/$records);
			
			if($next>$last)
			
			$next=$last;
			
			/*
				
				The first, previous, next and last page numbers have been calculated
				
			*/
			
			$start=$page_no;
			
			$end=$start+$pages-1;
			
			if($end>$last)
			
			$end=$last;
			
			/*
				
				The starting and ending page numbers for the paging
				
			*/
			
			if(($end-$start+1)<$pages)
			
			{
				
				$start-=$pages-($end-$start+1);
				
				if($start<1)
				
				$start=1;
				
			}
			
			if(($end-$start+1)==$pages)
			
			{
				
				$start=$page_no-floor($pages/2);
				
				$end=$page_no+floor($pages/2);
				
				while($start<$first)
				
				{
					
					$start++;
					
					$end++;
					
				}
				
				while($end>$last)
				
				{
					
					$start--;
					
					$end--;
					
				}
				
			}
			
			/*
				
				The above two IF statements are kinda optional
				
				These IF statements bring the current page in center
				
			*/
			
			$this->sql=$sql;
			
			$this->records=$records;
			
			$this->pages=$pages;
			
			$this->page_no=$page_no;
			
			$this->total=$total;
			
			$this->limit=$limit;
			
			$this->limit1=$limit1;
			
			$this->first=$first;
			
			$this->previous=$previous;
			
			$this->next=$next;
			
			$this->last=$last;
			
			$this->start=$start;
			
			$this->end=$end;
			
			$this->totalrecords=$totalrecords;
			
		}
		
		function show_paging($url,$params="",$additionaltext = "",$short="")
		
		{
			
			$paging="";
			
			if($this->total>$this->records || $this->total>0)
			
			{
				
				$page_no=$this->page_no;
				
				$first=$this->first;
				
				$previous=$this->previous;
				
				$next=$this->next;
				
				$last=$this->last;
				
				$start=$this->start;
				
				$end=$this->end;
				
				if($params=="")
				{
					$params="?displaytab=tab2&pgno1=";	
				}				
				else
				{
					$params="?$params&displaytab=tab2&pgno1=";
				}
				/*if($page_no==$first)
					
					$paging.="<li class='paging-first paging-disabled'><a href='javascript:void(0)'>&nbsp;First&nbsp;</a></li>";
					
					else
					
					$paging.="<li class='paging-first'><a href='$url$params$first'>&nbsp;First&nbsp;</a></li>";
					
					if($page_no==$previous)
					
					$paging.="<li class='paging-previous paging-disabled'><a href='javascript:void(0)'>&nbsp;Prev&nbsp;</a></li>";
					
					else
					
				$paging.="<li class='paging-previous'><a href='$url$params$previous'>&nbsp;Prev&nbsp;</a></li>";*/
				
				
				
				if($this->limit1 < $this->totalrecords){
					
					$displayval = $this->limit1;
					
					} else {
					
					$displayval = $this->totalrecords;
					
				}			
			
				$paging .= '<div  class="fulldiv"> <div class="toppagingdiv css3"><div class="rowsperpage">Rows '.($this->start + $this->limit).'-'.$displayval.' of '.$this->totalrecords.' (Page '.$page_no.' of '.$this->last.') </div><div id="paging2" class="w262 pageingimg" style="padding-top: 3px;"><span>';
				
				if($this->last > 1){
					$paging .= '<input type="hidden" id="totalpg" value="'.$this->last.'&displaytab=tab2" />';
					if($page_no>1)
					{	
						$paging .= '<a href="'.$url.$params.$this->first.'&perpage1='.$this->records.'" title="First" alt="First" class="previous"><img src="images/spacer.png" alt="previous" border="0" /></a>
						
						<a href="'.$url.$params.$this->previous.'&perpage1='.$this->records.'" title="Previous" class="previous2"><img src="images/spacer.png" alt="previous" border="0" /></a>';
					}
					$paging .= $additionaltext;
					
					for($p=$start;$p<=$end;$p++)
					
					{
						
						if($page_no==$p)
						
						$paging.="<a class=\"active\" title=\"$p\" href='javascript:void(0)'>$p</a>";
						
						else
						
						$paging.="<a class='numpaging' title=\"$p\" href='$url$params".$p."&perpage1=".$this->records."&displaytab=tab2'>$p</a>";
						
					}
					if($page_no<$last)
					{
						$paging .= '<a href="'.$url.$params.$this->next.'&perpage1='.$this->records.'" title="Next" alt="Next" class="next2"><img src="images/spacer.png" title="Next" alt="Next" border="0" /></a>';
						
						$paging .= '<a href="'.$url.$params.$this->last.'&perpage1='.$this->records.'" title="Last" alt="Last"  class="next"><img src="images/spacer.png" alt="Next" border="0" title="Next" /></a>';
					}
					$paging .= '</span></div><div class="w300" style="padding-left: 35px; width: 265px;*padding-left:0px;"><div class="frd" style="padding-top: 2px;width:348px"><div style="float:left;width:127px">Go to page: <input type="text" name="gotopage1" id="gotopage1" size="1" maxlength="4" onkeydown="if(event && event.keyCode == 13){ if(document.getElementById(\'gotopage1\').value != \'\'){ if(Number(document.getElementById(\'gotopage1\').value) > Number(document.getElementById(\'totalpg\').value)){ alert(\'Page not exists\');}
					else if(isNaN(document.getElementById(\'gotopage1\').value)==true) { alert(\'Please enter only number.\'); document.getElementById(\'gotopage1\').focus();document.getElementById(\'gotopage1\').value=\'\'; }
					else { window.location.href=\''.$url.$params.'\'+(document.getElementById(\'gotopage1\').value);}} else { alert(\'Please enter Go to page\'); document.getElementById(\'gotopage1\').focus(); } }" style="border:1px solid #DFDFDF;color:#676767; font-family:Arial,Helvetica,sans-serif;font-size:12px"/></div><span class="bluebtnl"></span><input type="button" class="bluebtnma" value="Go" name="gosubmit1" id="gosubmit1" title="Go" onclick="if(document.getElementById(\'gotopage1\').value != \'\'){if(Number(document.getElementById(\'gotopage1\').value) > Number(document.getElementById(\'totalpg\').value)){ alert(\'Page not exists\'); }
					else if(isNaN(document.getElementById(\'gotopage1\').value)==true) { alert(\'Please enter only number.\'); document.getElementById(\'gotopage1\').focus();document.getElementById(\'gotopage1\').value=\'\'; }
					else {window.location.href=\''.$url.$params.'\'+(document.getElementById(\'gotopage1\').value);}} else { alert(\'Please enter Go to page\'); document.getElementById(\'gotopage1\').focus(); }" /><span class="bluebtnr"></span>';
					
					} else {
					$paging .= '</span></div>';
					if($this->last > 1){
						$paging .= '<div style="width:138px; float:left">&nbsp;</div>';
					}else{
						$paging .= '<div style="width:138px; float:left">&nbsp;</div>';
					}
				}
				
				$paging .= '&nbsp;&nbsp;&nbsp;&nbsp;Records/Page: <select id="combo" class="comboclasspaging" onchange="window.location.href=\''.$url.$params.'&perpage1=\'+this.value">
				
				<option value="10" '.($this->records=="10"?"selected":"").'>10</option>
				
				<option value="20" '.($this->records=="20"?"selected":"").'>20</option>
				
				<option value="50" '.($this->records=="50"?"selected":"").'>50</option>
				
				<option value="100" '.($this->records=="100"?"selected":"").'>100</option>
				
				</select></div>
				
				</div>
				
				</div>
				
				</div>';
				
			}
			
			return $paging;
			
		}
		
		function show_pagingHTML($url,$params="")
		
		{
			
			$paging="";
			
			if($this->total>$this->records)
			
			{
				
				$page_no=$this->page_no;
				
				$first=$this->first;
				
				$previous=$this->previous;
				
				$next=$this->next;
				
				$last=$this->last;
				
				$start=$this->start;
				
				$end=$this->end;
				
				if($params=="")
				
				$params="_";
				
				else
				
				$params="_";
				
				$paging.="<ul class='paging'>";
				
				if($page_no==$first)
				{
					
					$paging.="<li class='paging-first paging-disabled'><a href='javascript:void(0)'>&nbsp;First&nbsp;</a></li>";
				}
				else
				
				$paging.="<li class='paging-first'><a href='$url$params$first.html'>&nbsp;First&nbsp;</a></li>";
				
				if($page_no==$previous)
				
				$paging.="<li class='paging-previous paging-disabled'><a href='javascript:void(0)'>&nbsp;Prev&nbsp;</a></li>";
				
				else
				
				$paging.="<li class='paging-previous'><a href='$url$params$previous.html'>&nbsp;Prev&nbsp;</a></li>";
				
				for($p=$start;$p<=$end;$p++)
				
				{
					
					$paging.="<li";
					
					if($page_no==$p)
					
					$paging.=" class='paging-active'";
					
					$paging.="><a href='$url$params$p.html'>$p</a></li>";
					
				}
				
				if($page_no==$next)
				
				$paging.="<li class='paging-next paging-disabled' ><a href='javascript:void(0)'>&nbsp;Next&nbsp;</a></li>";
				
				else
				
				$paging.="<li class='paging-next'><a href='$url$params$next.html'>&nbsp;Next&nbsp;</a></li>";
				
				if($page_no==$last)
				
				$paging.="<li class='paging-last paging-disabled'><a href='javascript:void(0)'>&nbsp;Last&nbsp;</a></li>";
				
				else
				
				$paging.="<li class='paging-last'><a href='$url$params$last.html'>&nbsp;Last&nbsp;</a></li>";
				
				$paging.="</ul>";
				
			}
			
			return $paging;
			
		}
		
		
		
		function GetTotalRecords()
		
		{
			
			return $this->totalrecords;
			
		}
		
	}
	
?>