<?php 

class _tourlist

{

	public $data;


	public function index()

	{

		require_once("app/functions/string.php");

		require_once("app/functions/l.php");

		$l = new functions\l();

		

		$out = "";



		// echo "<pre>";

		// print_r($this->data);

		// echo "</pre>";



		if(count($this->data)){

			

			foreach($this->data as $value) {

				$out .= "<section class=\"col-lg-4 tour-item\">\n";

				$out .= "<section class=\"imageBox\">\n";



				//http://lemi.404.ge/fr/image/loadimage?f=http://lemi.404.ge/public/filemanager/tours/tbilisi/tbilisi1.jpg&w=215&h=173

				$out .= sprintf(

					"<section class=\"image\" style=\"background-image: url('%s%s/image/loadimage?f=%s%s&w=480&h=400')\"></section>\n",

					Config::WEBSITE, 

					$_SESSION["LANG"],

					Config::WEBSITE_,

					$value["photo"]

				);



				if($value["tourist_points"]=="dynamic"){

					$out .= sprintf(

						"<section class=\"price\">%s %s &euro;</section>\n",

						$l->translate("from"),

						$value["price"]

					);

				}else{

					$out .= sprintf(

						"<section class=\"price\">%s &euro; - <i class=\"fa fa-user\" aria-hidden=\"true\"></i> %s</section>\n",

						$value["price"], 

						$value["tourist_points"]

					);

				}

				$out .= "</section>\n";

				$out .= "<section class=\"tour-description\">\n";

				$out .= sprintf(

					"<section class=\"tour-title\">%s</section>\n", 

					functions\string::cutstatic($value["title"], 20)

				);

				$out .= sprintf(

					"<section class=\"dayNights\"><i class=\"fa fa-clock-o\" aria-hidden=\"true\"></i> %s</section>\n", 

					functions\string::cutstatic($value["days_nights"], 20)

				);

				$out .= "<section class=\"text\">\n";

				$out .= sprintf(

					"<p>%s</p>\n",

					functions\string::cutstatic($value["short_description"], 20)

				);

				$out .= "</section>\n";



				$out .= sprintf(

					"<a href=\"%s%s/view/%s/?id=%d\">%s</a>\n",

					Config::WEBSITE,

					$_SESSION["LANG"],

					str_replace(array(" ", "'"), "-", strip_tags($value["title"])),

					(int)$value["idx"],

					$l->translate("readmore")

				);



				

				$out .= "</section>\n";

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