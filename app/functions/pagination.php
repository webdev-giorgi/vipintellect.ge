<?php 
namespace functions;

class pagination
{
	public function index($total, $itemPerPage)
	{
		$pages = ceil($total / $itemPerPage);

		$out = '<ul class="pagination margin-top-40">';
		
		$back = (isset($_GET['pn']) && $_GET['pn']>1) ? '?pn='.($_GET['pn']-1) : '?pn=1';
		$out .= sprintf('<li><a href="%s"><i class="material-icons">chevron_left</i></a></li>', $back);
		
		for($i = 1; $i<=$pages; $i++)
		{
			$pn_get = (isset($_GET['pn']) && $_GET['pn']>0) ? $_GET['pn'] : 1;
			$active = ($i==$pn_get) ? ' active' : '';			
			$pn = '?pn='.$i;			
			$out .= sprintf('<li class="waves-effect%s"><a href="%s">%d</a></li>', $active, $pn, $i);
		}
		
		$next = (isset($_GET['pn']) && $_GET['pn']<$pages) ? '?pn='.($_GET['pn']+1) : '?pn='.$pages;
		$out .= sprintf('<li><a href="%s"><i class="material-icons">chevron_right</i></a></li>', $next);
		
		$out .= '</ul>';
		return $out;
	}

	public function intellect_pagination($total, $itemPerPage)
	{
		$pages = ceil($total / $itemPerPage);

		$out = '<ul class="pagination glakho">';
		for($i = 1; $i<=$pages; $i++)
		{
			$pn_get = (isset($_GET['pn']) && $_GET['pn']>0) ? $_GET['pn'] : 1;
			$active = ($i==$pn_get) ? 'background-color: #ff0000; color: #ffffff; margin-bottom:10px;     border: solid 2px #000;' : 'margin-bottom:10px; border: solid 2px #000;';		
			if(
				isset($_GET['y']) && 
				is_numeric($_GET['y'])
			){	
				$pn = "?y={$_GET['y']}&pn={$i}";
			}else{
				$pn = '?pn='.$i;	
			}	
			$out .= sprintf('<li><a href="%s" style="%s">%d</a></li>', $pn, $active, $i);
		}
		$out .= '</ul>';
		return $out;
	}


	public function web($total, $itemPerPage)
	{
		require_once("app/functions/request.php"); 
		if($total<=0){
			return "";
		}
		$link = "?";
		if(request::index("GET", "title")){
			$link .= "title=".htmlentities(request::index("GET", "title"))."&";
		}

		if(request::index("GET", "destination")){
			$link .= "destination=".htmlentities(request::index("GET", "destination"))."&";
		}

		if(request::index("GET", "tourtype")){
			$link .= "tourtype=".htmlentities(request::index("GET", "tourtype"))."&";
		}

		if(request::index("GET", "arrival")){
			$link .= "arrival=".htmlentities(request::index("GET", "arrival"))."&";
		}

		if(request::index("GET", "departure")){
			$link .= "departure=".htmlentities(request::index("GET", "departure"))."&";
		}

		if(request::index("GET", "price")){
			$link .= "price=".htmlentities(request::index("GET", "price"))."&";
		}
		$pages = ceil($total / $itemPerPage);

		$out = '<nav aria-label="Page navigation">';
		$out .= '<ul class="pagination">';
		
		$back = (isset($_GET['pn']) && $_GET['pn']>1) ? $link.'&pn='.((int)$_GET['pn']-1) : $link.'&pn=1';
		$out .= sprintf('<li><a href="%s" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>', $back);
		
		for($i = 1; $i<=$pages; $i++)
		{
			$pn_get = (isset($_GET['pn']) && $_GET['pn']>0) ? (int)$_GET['pn'] : 1;
			$active = ($i==$pn_get) ? 'active' : '';			
			$pn = $link.'&pn='.$i;			
			$out .= sprintf('<li><a href="%s" class="%s">%d</a></li>', $pn, $active, $i);
		}
		
		$next = (isset($_GET['pn']) && $_GET['pn']<$pages) ? $link.'&pn='.((int)$_GET['pn']+1) : $link.'&pn='.$pages;
		$out .= sprintf('<li><a href="%s" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>', $next);
		
		$out .= '</ul>';
		$out .= '</nav>';
		return $out;
	}

	public function myaccount($total, $itemPerPage)
	{
		require_once("app/functions/request.php"); 
		if($total<=0){
			return "";
		}
		$link = "?";
		if(request::index("GET", "view")){
			$link .= "view=".request::index("GET", "view");
		}

		$pages = ceil($total / $itemPerPage);

		$out = '<nav aria-label="Page navigation">';
		$out .= '<ul class="pagination">';
		
		$back = (isset($_GET['pn']) && $_GET['pn']>1) ? $link.'&pn='.($_GET['pn']-1) : $link.'&pn=1';
		$out .= sprintf('<li><a href="%s" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>', $back);
		
		for($i = 1; $i<=$pages; $i++)
		{
			$pn_get = (isset($_GET['pn']) && $_GET['pn']>0) ? $_GET['pn'] : 1;
			$active = ($i==$pn_get) ? 'active' : '';			
			$pn = $link.'&pn='.$i;			
			$out .= sprintf('<li><a href="%s" class="%s">%d</a></li>', $pn, $active, $i);
		}
		
		$next = (isset($_GET['pn']) && $_GET['pn']<$pages) ? $link.'&pn='.($_GET['pn']+1) : $link.'&pn='.$pages;
		$out .= sprintf('<li><a href="%s" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>', $next);
		
		$out .= '</ul>';
		$out .= '</nav>';
		return $out;
	}
}