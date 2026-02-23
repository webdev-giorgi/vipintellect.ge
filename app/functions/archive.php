<?php 
namespace functions;

class archive{
	public function index()
	{
		require_once("app/functions/l.php");
		$l = new l();
		$y = date("Y");

		$out = "<ul class=\"list-links glakho\">";
		for($i=$y; $i>=($y-4); $i--){
			$active = "";
			if(
				isset($_GET['y']) &&
				is_numeric($_GET['y']) &&
				$_GET['y']==$i 
			){
				$active = ' style="color: #ff0000;"';
			}

			$out .= sprintf(
				"<li><a href=\"%s/%s?y=%d\"%s>%s %s</a></li>", 
				\Config::WEBSITE.$_SESSION["LANG"],
				$_SESSION["URL"][1],
				$i,
				$active, 
				$i,
				$l->translate("year")
			);
		}
		$out .= "</ul>";

		return $out;
	}
}