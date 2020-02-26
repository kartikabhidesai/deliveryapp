<?php
class pagingfront
{
	var $sql,$records,$pages;
	/*
	Variables that are passed via constructor parameters
	*/
	var $page_no,$total,$limit,$first,$previous,$next,$last,$start,$end, $totalrecords;
	/*
	Variables that will be computed inside constructor
	*/
	var $dbfunction;
	function pagingfront($sqlarray,$records=15,$pages=10)
	{
		$sqlarray4  = "";
		if(isset($sqlarray[4]) && $sqlarray[4] != "")
		{
			$sqlarray4 = $sqlarray[4];
		}
		$sql = "SELECT ".$sqlarray[1]." FROM ".$sqlarray[0]." ".($sqlarray[2]!=""?" WHERE ".$sqlarray[2]."":"")." ".$sqlarray[3]." ".$sqlarray4;

		if($pages%2==0)
			$pages++;
		/*
		The pages should be odd not even
		*/
		$res=mysql_query($sql) or die($sql." - ".mysql_error());
		$total=mysql_num_rows($res);
		$totalrecords = $total;
		$page_no = isset($_GET["pgno"])? (int) $_GET["pgno"]:1;
		if($page_no<=0){ $page_no = 1; }
		if($page_no>ceil($total/$records)){ $page_no = 1; }
		/*
		Checking the current page
		If there is no current page then the default is 1
		*/
		$limit=($page_no-1)*$records;
		$limit1 = ($page_no)*$records;
		$sql.=" LIMIT $limit,$records";
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
	function show_paging($url,$params="",$additionaltext = "")
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
				$params="?pgno=";
			else
				$params="?$params&pgno=";

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

			$paging .= '<div style="position:relative; clear:both"> <div class="gird_footer css3"><span class="pading_box"><span class="list_paging">';
			if($this->last > 1){
				$paging .= '<a class="prev" title="Previous" href="'.$url.$params.$this->previous.'"></a>';
				$paging .= $additionaltext;
				for($p=$start;$p<=$end;$p++)
				{
					if($page_no==$p)
						$paging.="<a class=\"active\" title=\"$p\" href='$url$params$p'>$p</a>";
					else
						$paging.="<a class='' title=\"$p\" href='$url$params$p'>$p</a>";
				}
				$paging .= '<a class="next" href="'.$url.$params.$this->next.'" title="Next" alt="Next"></a>';
	
				//$paging .= '</span></span><div class="w300" style="padding-left: 30px; width: 270px;">';
			 
			}
			$paging .= '</span></span><div class="w300" style="padding-left: 30px; width: 270px;"></div>
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
				$paging.="<li class='paging-first paging-disabled'><a href='javascript:void(0)'>&nbsp;First&nbsp;</a></li>";
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
				$paging.="<li class='paging-next paging-disabled'><a href='javascript:void(0)'>&nbsp;Next&nbsp;</a></li>";
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