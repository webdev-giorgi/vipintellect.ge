<?php

class _favouriteslist
{
	public $data;

	public function index()
	{
		require_once("app/functions/string.php");
		require_once("app/functions/l.php");
		$l = new functions\l();

		$out = "";

		if (count($this->data)) {
			foreach ($this->data as $value) {
				$out .= "<section class=\"purchase-item\">\n";

				$out .= sprintf(
					"<a href=\"%s%s/view/%s/?id=%d\" target=\"_blank\">\n",
					Config::WEBSITE,
					$_SESSION["LANG"],
					str_replace(array(" ", "'"), "-", strip_tags($value["title"])),
					(int)$value["idx"]
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

				$out .= sprintf(
					"<section class=\"pu-description\">%s</section>\n",
					functions\string::cutstatic(strip_tags($value["description"]), 520)
				);

				// $out .= "<section class=\"pu-links\">\n";
				// $out .= "<ul>\n";
				// $out .= "<li>\n";
				// $out .= "</li>\n";
				// $out .= "<li>\n";
				// $out .= "<i class=\"fa fa-eye\" aria-hidden=\"true\"></i> \n";
				// $out .= sprintf(
				// 	"<span>%s</span>\n",
				// 	$l->translate("view")
				// );
				// $out .= "</li>\n";
				// $out .= "</ul>\n";
				// $out .= "</section>\n";

				$out .= "<section class=\"clearer\"></section>\n";
				$out .= "</a>\n";

				$out .= sprintf(
					"<a href=\"javascript:void(0)\" class=\"removefavourite\" data-tourid=\"%d\">\n",
					(int)$value["idx"]
				);
				$out .= "<i class=\"fa fa-heart\" aria-hidden=\"true\"></i> \n";
				$out .= sprintf(
					"<span>%s</span>\n",
					$l->translate("removefavourite")
				);
				$out .= "</a>\n";
				$out .= "</section>\n";
			}
		} else {
			$out = sprintf(
				"<section class=\"alert alert-warning\" role=\"alert\">
				<i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> %s 
				</section>",
				$l->translate("nodata")
			);
		}

		return $out;
	}
}