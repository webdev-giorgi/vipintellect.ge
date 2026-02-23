<?php 
class _purchaseslist
{
	public $data;
	public function index()
	{
		require_once("app/functions/string.php");
		require_once("app/functions/l.php");
		$l = new functions\l();
		$out = "";
		if(count($this->data)){
			// echo count($this->data);
			foreach($this->data as $value) {
				$out .= "<section class=\"purchase-item\">\n";
				$out .= sprintf(
					"<a href=\"%s%s/view/%s/?id=%d\" target=\"_blank\">\n",
					Config::WEBSITE,
					$_SESSION["LANG"],
					str_replace(array(" ", "'"), "-", strip_tags($value["title"])),
					(int)$value["tour_id"]
				);
				$out .= sprintf(
					"<section class=\"image\" style=\"background-image: url('%s%s/image/loadimage?f=%s%s&w=400&h=280')\"></section>\n",
					Config::WEBSITE, 
					$_SESSION["LANG"],
					Config::WEBSITE_,
					$value["photo"]
				);
				$out .= "<section class=\"header\">\n";
				$out .= sprintf(
					"<section class=\"pu-title\">%s</section>\n", 
					functions\string::cutstatic($value["title"], 120)
				);
				$out .= "</section>\n";
				$out .= "<section class=\"pu-links\">";
				$out .= "<ul>";
				$out .= "<li>";
				$out .= "<i class=\"fa fa-clock-o\" aria-hidden=\"true\"></i> ";
				$out .= sprintf(
					"<span title=\"Book Time\">%s</span>",
					date("d/m/Y h:s", $value["date"])
				);
				$out .= "</li>";
				$out .= "<li>";
				$out .= "<i class=\"fa fa-male\" aria-hidden=\"true\"></i> ";
				$out .= sprintf(
					"<span title=\"Book Time\">%s</span>",
					$value["adults"]
				);
				$out .= "</li>";
				$out .= "<li>";
				$out .= "<i class=\"fa fa-child\" aria-hidden=\"true\"></i> ";
				$out .= sprintf(
					"<span title=\"Book Time\">%s</span>",
					$value["children"]
				);
				$out .= "</li>";
				$out .= "<li>";
				$out .= "<i class=\"fa fa-credit-card\" aria-hidden=\"true\"></i> ";
				$out .= sprintf(
					"<span title=\"Book Time\">%s &euro; %s</span>",
					$value["total_price"],
					"Votre réservation a bien était pris en compte"
				);
				$out .= "</li>";
				$out .= "</ul>";
				/* აქ უნდა ჩავსვა ეს
								<a href="">
									<i class="fa fa-usd" aria-hidden="true"></i> 
									<span title="Price">700$</span>
								</a>
							</li>
							<li>
								<a href="">
									<i class="fa fa-print" aria-hidden="true"></i> 
									<span title="Print">Print</span>
								</a>
							</li>
				*/
				$out .= "</section>";
				$out .= "<section class=\"clearer\"></section>\n";
				$out .= "</a>\n";
				$out .= sprintf(
					"<a href=\"javascript:void(0)\" class=\"removefavourite\">\n"
				);
				$out .= sprintf("# %d", $value["id"]);
				$out .= "</a>\n";
				$out .= "</section>\n";
			}
		}else{  
			$out = sprintf(
				"<section class=\"alert alert-warning\" role=\"alert\">
				<i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> %s 
				</section>
				", 
				$l->translate("nodata")
			);
		}
		return $out;
	}
}