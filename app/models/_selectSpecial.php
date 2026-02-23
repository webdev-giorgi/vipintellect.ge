<?php 
class _selectSpecial
{
	public $data;
	public function index()
	{
		require_once("app/functions/string.php");
		$out = "<section class=\"col-lg-8 leftside\">\n";
		if(count($this->data)){
			$big = array_slice($this->data, 0, 1);
			if($big){
				$out .= "<section class=\"scaleBox\">\n";
				$out .= sprintf(
					"<section class=\"price\">%s &euro;</section>\n",
					(int)$big[0]["price"]
				);
				$out .= sprintf(
					"<a href=\"%s%s/view/%s/?id=%d\" style=\"background-image: url('%s%s/image/loadimage?f=%s%s&w=1400&h=1200')\" alt=\"%s\"></a>\n",
					Config::WEBSITE,
					$_SESSION["LANG"],
					str_replace(array(" ", "'"), "-", strip_tags($big[0]["title"])),
					(int)$big[0]["idx"],
					Config::WEBSITE, 
					$_SESSION["LANG"],
					Config::WEBSITE_,
					$big[0]["photo"],
					htmlentities(strip_tags($big[0]["title"]))
				);
				$out .= "<section class=\"description\">\n";
				$out .= sprintf(
					"<h3>%s</h3>\n",
					functions\string::cutstatic($big[0]["title"], 45)
				);
				$out .= sprintf(
					"<p>%s</p>\n",
					functions\string::cutstatic($big[0]["short_description"], 130)
				);
				$out .= "</section>\n";
				$out .= "</section>\n";
			}
		}
		$out .= "</section>\n";
		$out .= "<section class=\"col-lg-4 rightside\">\n";
		if(count($this->data)){	
			$smaill1 = array_slice($this->data, 1, 1);
			$smaill2 = array_slice($this->data, 2, 1);	
			if($smaill1){
				$out .= "<section class=\"scaleBox posTop\">\n";
				$out .= sprintf(
					"<section class=\"price\">%s &euro;</section>\n",
					$smaill1[0]["price"]
				);
				// $out .= "<a href=\"\" style=\"background-image: url('')\" alt=\"Ilia Lake In Kakheti\"></a>\n";
				$out .= sprintf(
					"<a href=\"%s%s/view/%s/?id=%d\" style=\"background-image: url('%s%s/image/loadimage?f=%s%s&w=800&h=600')\" alt=\"%s\"></a>\n",
					Config::WEBSITE,
					$_SESSION["LANG"],
					str_replace(array(" ", "'"), "-", strip_tags($smaill1[0]["title"])),
					(int)$smaill1[0]["idx"],
					Config::WEBSITE, 
					$_SESSION["LANG"],
					Config::WEBSITE_,
					$smaill1[0]["photo"],
					htmlentities(strip_tags($smaill1[0]["title"]))
				);
				$out .= "<section class=\"description\">\n";
				$out .= sprintf(
					"<h3>%s</h3>\n",
					functions\string::cutstatic($smaill1[0]["title"], 45)
				);
				$out .= sprintf(
					"<p>%s</p>\n",
					functions\string::cutstatic($smaill1[0]["short_description"], 130)
				);
				$out .= "</section>\n";
				$out .= "</section>\n";
			}	
			if($smaill2){
				// echo "<pre>";
				// print_r($smaill2);
				// echo "</pre>";
				$out .= "<section class=\"scaleBox posBottom\">\n";
				$out .= sprintf(
					"<section class=\"price\">%s &euro;</section>\n",
					$smaill2[0]["price"]
				);
				$out .= sprintf(
					"<a href=\"%s%s/view/%s/?id=%d\" style=\"background-image: url('%s%s/image/loadimage?f=%s%s&w=800&h=600')\" alt=\"%s\"></a>\n",
					Config::WEBSITE,
					$_SESSION["LANG"],
					str_replace(array(" ", "'"), "-", strip_tags($smaill2[0]["title"])),
					(int)$smaill2[0]["idx"],
					Config::WEBSITE, 
					$_SESSION["LANG"],
					Config::WEBSITE_,
					$smaill2[0]["photo"],
					htmlentities(strip_tags($smaill2[0]["title"]))
				);
				$out .= "<section class=\"description\">\n";	
				$out .= sprintf(
					"<h3>%s</h3>\n",
					functions\string::cutstatic($smaill2[0]["title"], 45)
				);
				$out .= sprintf(
					"<p>%s</p>\n",
					functions\string::cutstatic($smaill2[0]["short_description"], 130)
				);
				$out .= "</section>\n";	
				$out .= "</section>\n";	
			}
		}
		$out .= "</section>\n";
		return $out;
	}
}