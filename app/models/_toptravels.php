<?php 

class _toptravels

{

	public $data;


	public function index()

	{

		require_once("app/functions/string.php"); 

		$out = "";

		if(count($this->data)){

			foreach ($this->data as $travel) {

				// $shareUrl = sprintf(

				// 	"http://www.facebook.com/sharer.php?s=100&p[title]=%s&p[url]=%s%s/view/%s/?id=%d&p[summary]=%s&p[images][0]=%s%s",

				// 	urlencode($travel["title"]),

				// 	Config::WEBSITE,

				// 	$_SESSION["LANG"],

				// 	str_replace(array(" ", "'"), "-", strip_tags($travel["title"])),

				// 	$travel["idx"],

				// 	urlencode($travel["title"]),

				// 	Config::WEBSITE_,

				// 	$travel["photo"]

				// );



				$out .= sprintf(

					"<a href=\"%s%s/view/%s/?id=%d\" class=\"owlItem\" title=\"%s\">\n",

					Config::WEBSITE,

					$_SESSION["LANG"],

					str_replace(array(" ", "'"), "-", strip_tags($travel["title"])),

					$travel["idx"],

					htmlentities(strip_tags($travel["title"]))

				);

				$out .= sprintf(

					"<section class=\"title\">%s</section>\n",

					functions\string::cutstatic($travel["title"], 35)

				);

				$out .= sprintf(

					"<section class=\"image\" style=\"background-image: url('%s%s/image/loadimage?f=%s%s&w=480&h=400')\"></section>\n",

					Config::WEBSITE, 

					$_SESSION["LANG"],

					Config::WEBSITE_,

					$travel["photo"]

				);

				// $out .= "<section class=\"share\">\n";

				// $out .= "<ul>\n";

				// $out .= "<li class=\"transitions\"><i class=\"fa fa-heart-o transitions\" aria-hidden=\"true\"></i></li>\n";

				// $out .= sprintf(

				// 	"<li class=\"transitions\" onclick=\"goto('%s')\"><i class=\"fa fa-share-alt transitions\" aria-hidden=\"true\"></i></li>\n",

				// 	$shareUrl

				// );

				// $out .= "<li class=\"transitions\"><i class=\"fa fa-tripadvisor transitions\" aria-hidden=\"true\"></i></li>\n";

				// $out .= "</ul>\n";

				// $out .= "</section>\n";

				$out .= "</a>\n";

			}

		}



		return $out;

	}

}