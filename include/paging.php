<?php
/************************************************
*	========================================	*
*	Perfect MySQL Paging						*
*	========================================	*
*	Script Name: class.paging.php				*
*	Developed By: Khurram Adeeb Noorani			*
*	Email: khurramnoorani@gmail.com				*
*	My CV: http://www.visualcv.com/kanoorani	*
*	Twitter: http://www.twitter.com/kanoorani	*
*	Date Created: 08-JULY-2009					*
*	Last Modified: 08-JULY-2009					*
************************************************/
?>
<?php
class paging
{
	var $sql,$records,$pages;
	/*
	Variables that are passed via constructor parameters
	*/
	var $page_no,$total,$limit,$first,$previous,$next,$last,$start,$end;
	/*
	Variables that will be computed inside constructor
	*/
	function paging($tablename, $fieldlist, $where = '', $orderby = '', $groupby = '', $records=15, $pages=9)
	{
		$converter = new encryption();
		$dbfunctions = new dbfunctions();
		if($pages%2==0)
			$pages++;
		/*
		The pages should be odd not even
		*/
		$sql = $dbfunctions->GenerateSelectQuery($tablename, $fieldlist, $where, $orderby, $groupby);
		$dbfunctions->SimpleSelectQuery($sql);
		$total = $dbfunctions->getNumRows();
		$page_no = (int) isset($_GET["page_no"])?$converter->decode($_GET["page_no"]):1;
		/*
		Checking the current page
		If there is no current page then the default is 1
		*/
		$limit = ($page_no-1)*$records;
		$sql = $dbfunctions->GenerateSelectQuery($tablename, $fieldlist, $where, $orderby, $groupby, " limit $limit,$records");
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
		$this->first=$first;
		$this->previous=$previous;
		$this->next=$next;
		$this->last=$last;
		$this->start=$start;
		$this->end=$end;
	}
	function show_paging($url,$params="", $colspan)
	{
		$converter = new encryption();
		$paging="";
		if($this->total>$this->records)
		{
			$page_no = $this->page_no;
			$first= $this->first;
			$previous = $this->previous;
			$next = $this->next;
			$last = $this->last;
			$start = $this->start;
			$end = $this->end;
			if($params=="")
				$params="?page_no=";
			else
				$params="?$params&page_no=";
			
			$paging .= '<tr class="gradeX">
							<td colspan="'.$colspan.'">
								<div class="row-fluid">
									<div class="span3">
										<div class="dataTables_info infomargin">Showing '.($this->limit+1).' to '.(($this->limit+$this->records)>$this->total?$this->total:($this->limit+$this->records)).' of '.$this->total.' entries</div>
									</div>
								
								<div class="span9">
									<div class="dataTables_paginate paging_bootstrap pagination">
										<ul>';
										if($page_no==$first)
											$paging .= '<li class="prev disabled" title="Previous"><a href="#">Previous</a></li>';
										else
											$paging.= '<li class="prev"><a title="Previous" href="'.$url.$params.$converter->encode($previous).'">Previous</a></li>';
											
										for($p=$start;$p<=$end;$p++)
										{
											$paging.="<li";
											if($page_no==$p)
												$paging.=" class='active'";
											$paging.="><a title='".$p."' href='".$url.$params.$converter->encode($p)."'>$p</a></li>";
										}

			if($page_no==$next)
				$paging .= '<li class="next disabled"><a href="#" title="Next">Next</a></li>';
			else
				$paging .= '<li class="next"><a title="Next" href="'.$url.$params.$converter->encode($next).'">Next</a></li>';

			$paging.="</ul></div></div></div></td></tr>";
		}
		return $paging;
	}
}
?>