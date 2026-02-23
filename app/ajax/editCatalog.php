<?php 

class editCatalog

{

	public $out; 



	public function __construct()

	{

		require_once 'app/core/Config.php';

		if(!isset($_SESSION[Config::SESSION_PREFIX."username"]))

		{app/functions/strings.php

			exit();

		}

	}

	

	public function index(){

		require_once 'app/core/Config.php';

		require_once 'app/functions/makeForm.php';

		require_once 'app/functions/request.php';

		require_once 'app/functions/string.php';



		$this->out = array(

			"Error" => array(

				"Code"=>1, 

				"Text"=>"მოხდა შეცდომა !",

				"Details"=>"!"

			)

		);



		$idx = functions\request::index("POST","idx");

		$lang = functions\request::index("POST","lang");

		$random = functions\string::random(25);



		if($idx == "" || $lang=="")

		{

			$this->out = array(

				"Error" => array(

					"Code"=>1, 

					"Text"=>"მოხდა შეცდომა !",

					"Details"=>"!"

				)

			);

		}else{

			$Database = new Database('products', array(

					'method'=>'selectById', 

					'idx'=>$idx, 

					'lang'=>$lang

			));

			$output = $Database->getter();



			$photos = new Database('photos', array(

				'method'=>'selectByParent', 

				'idx'=>$idx, 

				'lang'=>$lang, 

				'type'=>"products"

			));

			$pictures = $photos->getter();





			$form = functions\makeForm::open(array(

				"action"=>"?",

				"method"=>"post",

				"id"=>"",

				"id"=>"",

			));



			$form .= "<input type=\"hidden\" name=\"language\" id=\"language\" value=\"".$_SESSION['LANG']."\" />";



			$form .= functions\makeForm::label(array(

				"id"=>"dateLabel", 

				"for"=>"date", 

				"name"=>"დამატების თარიღი",

				"require"=>""

			));



			$form .= functions\makeForm::inputText(array(

				"placeholder"=>"", 

				"id"=>"date", 

				"name"=>"date",

				"value"=>date("d-m-Y", $output["date"])

			));





			$form .= functions\makeForm::label(array(

				"id"=>"titleLabel", 

				"for"=>"title", 

				"name"=>"დასახელება",

				"require"=>""

			));

		

			$form .= functions\makeForm::inputText(array(

				"placeholder"=>"", 

				"id"=>"title", 

				"name"=>"title",

				"value"=>$output["title"]

			));



			$cover = (!empty($output["coverphoto"])) ? $output["coverphoto"] : "/public/img/cover.png";

			$form .= functions\makeForm::label(array(

				"id"=>"coverPhotoLabel", 

				"for"=>"coverPhoto", 

				"name"=>"Cover ფოტო ( რედაქტირებისთვის დაკლიკეთ ფოტოზე )",

				"require"=>""

			));



			$form .= sprintf(

				"<input type=\"hidden\" name=\"cover\" class=\"cover\" value=\"%s\" />", 

				$cover

			);

			$form .= sprintf(

				"<div class=\"coverphoto\" onclick=\"openFileManagerForProductCover(100000)\" style=\"background-image: url(%s)\"></div>",

				$cover

			);



			$form .= functions\makeForm::label(array(

				"id"=>"arrivaldepartureLabel", 

				"for"=>"arrivaldeparture", 

				"name"=>"ჩამოსვლა-გამგზავრება ( ფორმატი: d/m/Y-d/m/Y, ... )",

				"require"=>""

			));



			$form .= functions\makeForm::inputText(array(

				"placeholder"=>"მაგ: 22/01/2018-23/02/2018, 25/03/2018-25/04/2015", 

				"id"=>"arrivaldeparture", 

				"name"=>"arrivaldeparture",

				"value"=>$output["checkinout"]

			));





			$form .= functions\makeForm::label(array(

				"id"=>"daysAndNightsLabel", 

				"for"=>"daysAndNights", 

				"name"=>"დღე და ღამე (რაოდენობა)",

				"require"=>""

			));

			

			$form .= functions\makeForm::inputText(array(

				"placeholder"=>"", 

				"id"=>"daysAndNights", 

				"name"=>"daysAndNights",

				"value"=>$output["days_nights"]

			));





			$Database = new Database("modules", array(

				"method"=>"selectModuleByType", 

				"type"=>"destination",

				"lang"=>$lang

			));



			$options = array();

			foreach ($Database->getter() as $value) {

				$options[$value['idx']] = $value['title'];

			}



			$form .= functions\makeForm::label(array(

				"id"=>"chooseDestinationLabel", 

				"for"=>"chooseDestination", 

				"name"=>"ადგილმდებარეობა",

				"require"=>""

			));



			$form .= functions\makeForm::select(array(

				"id"=>"chooseDestination",

				"choose"=>"აირჩიეთ ადგილმდებარეობა",

				"options"=>$options,

				"selected"=>"false",

				"disabled"=>"false",

				"multiple"=>"true"

			));



			$Database2 = new Database("modules", array(

				"method"=>"selectModuleByType", 

				"type"=>"tourtypes",

				"lang"=>$lang

			));



			$options2 = array();

			foreach ($Database2->getter() as $value) {

				$options2[$value['idx']] = $value['title'];

			}



			$form .= functions\makeForm::label(array(

				"id"=>"chooseAdvantureTypeLabel", 

				"for"=>"chooseAdvantureType", 

				"name"=>"ტურის ტიპი",

				"require"=>""

			));



			$form .= functions\makeForm::select(array(

				"id"=>"chooseAdvantureType",

				"choose"=>"აირჩიეთ ტურის ტიპი",

				"options"=>$options2,

				"selected"=>"false",

				"disabled"=>"false",

				"multiple"=>"true"

			));



			// $form .= functions\makeForm::label(array(

			// 	"id"=>"arrivalLabel", 

			// 	"for"=>"arrival", 

			// 	"name"=>"ჩამოსვლა",

			// 	"require"=>""

			// ));

			// $arrival = strtotime($output['arrival']);

			// $form .= functions\makeForm::inputText(array(

			// 	"placeholder"=>"ჩამოსვლა", 

			// 	"id"=>"arrival", 

			// 	"name"=>"arrival",

			// 	"value"=>date("d-m-Y", $arrival)

			// ));



			// $form .= functions\makeForm::label(array(

			// 	"id"=>"departurelLabel", 

			// 	"for"=>"departure", 

			// 	"name"=>"გამგზავრება",

			// 	"require"=>""

			// ));



			// $departure = strtotime($output['departure']);

			// $form .= functions\makeForm::inputText(array(

			// 	"placeholder"=>"გამგზავრება", 

			// 	"id"=>"departure", 

			// 	"name"=>"departure",

			// 	"value"=>date("d-m-Y", $departure)

			// ));





			////

			$touristCount["dynamic"] = "დინამიური";

			for($x=1;$x<=10;$x++){

				$touristCount[$x] = $x;

			}



			$form .= functions\makeForm::label(array(

				"id"=>"chooseTouristCountLabel", 

				"for"=>"chooseTouristCount", 

				"name"=>"ტურისტების რაოდენობა",

				"require"=>""

			));



			$form .= functions\makeForm::select(array(

				"id"=>"chooseTouristCount",

				"choose"=>"აირჩიეთ ტურისების რაოდენობა",

				"options"=>$touristCount,

				"selected"=>$output["tourist_points"],

				"disabled"=>"false",

				"multiple"=>"false"

			));

		////



			$form .= functions\makeForm::label(array(

				"id"=>"priceLabel", 

				"for"=>"price", 

				"name"=>"ღირებულება",

				"require"=>""

			));

		

			$form .= functions\makeForm::inputText(array(

				"placeholder"=>"", 

				"id"=>"price", 

				"name"=>"price",

				"value"=>$output["price"]

			));





			$form .= functions\makeForm::label(array(

				"id"=>"shortDescriptionLabel", 

				"for"=>"shortDescription", 

				"name"=>"მოკლე აღწერა",

				"require"=>""

			));



			$form .= functions\makeForm::textarea(array(

				"id"=>"shortDescription",

				"name"=>"shortDescription",

				"placeholder"=>"",

				"value"=>$output["short_description"]

			));



			$form .= functions\makeForm::label(array(

				"id"=>"longDescriptionLabel", 

				"for"=>"longDescription", 

				"name"=>"ვრცელი აღწერა",

				"require"=>""

			));



			$form .= functions\makeForm::textarea(array(

				"id"=>"longDescription",

				"name"=>"longDescription",

				"placeholder"=>"",

				"value"=>$output["description"]

			));



			$form .= functions\makeForm::label(array(

				"id"=>"locationsLabel", 

				"for"=>"locations", 

				"name"=>"ადგილმდებარეობა/ადგილმდებარეობები",

				"require"=>""

			));

			

			$form .= functions\makeForm::inputText(array(

				"placeholder"=>"", 

				"id"=>"locations", 

				"name"=>"locations",

				"readonly"=>true,

				"value"=>$output["location"]

			));



			$form .= "<script type=\"text/javascript\">

			window.onmessage = function(e){

			    console.log(e.data);

			    $(\"#locations\").val(e.data);

			};

			</script>";

			

			$_SESSION["token"] = $random;

			$form .= sprintf(

				"<iframe class=\"locationsMap\" src=\"%s?token=%s&l=%s\"></iframe>",

				Config::PUBLIC_FOLDER."googleMap/edit.php",

				$random,

				$output["location"]

			);



			$form .= functions\makeForm::label(array(

				"id"=>"services", 

				"for"=>"services_", 

				"name"=>"სერვისები",

				"require"=>""

			));



			$services = new Database("modules", array(

				"method"=>"selectModuleByType", 

				"type"=>"services",

				"lang"=>$lang

			));



			foreach($services->getter() as $val) :

			$subServiceDb = new Database("service", array(

				"method"=>"subservicves", 

				"product_idx"=>$idx,

				"service_idx"=>$val["idx"],

				"lang"=>$lang

			));



			$subServiceGetter = $subServiceDb->getter();



			$form .= functions\makeForm::checkbox(array(

				"chackboxTitle"=>$val["title"], 

				"name"=>"service".$val["idx"], 

				"id"=>"serviceId".$val["idx"],

				"checked"=>(count($subServiceGetter)) ? "true" : "false",

				"value"=>"1"

			));



			$style = (count($subServiceGetter)) ? " style='display:block'" : "";

			$form .= sprintf(

				"<section class=\"subServices serviceId%d\"%s>", 

				$val["idx"],

				$style

			);

			

			$form .= "<section class=\"allfields\" id=\"field".$val["idx"]."\" data-service=\"".$val["idx"]."\">";

			//$form .= print_r($getter, true);



			if(count($subServiceGetter)){

				foreach($subServiceGetter as $serv) :

				$form .= "<div class=\"input-field\">";

				$form .= sprintf(

					"<input type=\"text\" placeholder=\"დასახელება\" class=\"title\" value=\"%s\" />",

					$serv["title"]

				);

				$form .= sprintf(

					"<input type=\"text\" placeholder=\"ფასი\" class=\"price\" value=\"%s\" />",

					$serv["price"]

				);

				$form .= "</div>";

				endforeach;

			}



			$form .= "</section>";

			

			$form .= "<a href=\"javascript:void(0)\" class=\"addField\" data-field=\"field".$val["idx"]."\">ფასის დამატება</a>";

			$form .= "</section>";

			endforeach;



			$options3 = array(

				1=>"არა",

				2=>"კი"

			);



			$form .= functions\makeForm::label(array(

				"id"=>"special_offerLabel", 

				"for"=>"chooseSpecial_offer", 

				"name"=>"სპეციალური შემოთავაზება",

				"require"=>""

			));



			$form .= functions\makeForm::select(array(

				"id"=>"chooseSpecial_offer",

				"choose"=>"სპეციალური შემოთავაზება",

				"options"=>$options3,

				"selected"=>1,

				"disabled"=>"false"

			));





			$options4 = array(

				1=>"დამალვა",

				2=>"გამოჩენა"

			);



			$form .= functions\makeForm::label(array(

				"id"=>"visibilitiTypeLabel", 

				"for"=>"choosevisibiliti", 

				"name"=>"ხილვადობა",

				"require"=>""

			));



			$form .= functions\makeForm::select(array(

				"id"=>"choosevisibiliti",

				"choose"=>"აირჩიეთ ხილვადობა",

				"options"=>$options4,

				"selected"=>1,

				"disabled"=>"false"

			));





			$form .= "<script type=\"text/javascript\">";

			$dests = explode(",", $output['destination']);

			foreach($dests as $d):

			$form .= sprintf(

				"$('#chooseDestination').find('option[value=\"%d\"]').prop('selected', true);",

				$d

			);

			endforeach;

			$form .= "$('#chooseDestination').material_select();";





			$adv = explode(",", $output['advanture_type']);

			foreach($adv as $a):

			$form .= sprintf(

				"$('#chooseAdvantureType').find('option[value=\"%d\"]').prop('selected', true);",

				$a

			);

			endforeach;

			$form .= "$('#chooseAdvantureType').material_select();";



			$form .= sprintf(

				"$('#chooseSpecial_offer').find('option[value=\"%d\"]').prop('selected', true);",

				$output['special_offer']

			);

			$form .= "$('#chooseSpecial_offer').material_select();";





			$form .= sprintf(

				"$('#choosevisibiliti').find('option[value=\"%d\"]').prop('selected', true);",

				$output['showwebsite']

			);

			$form .= "$('#choosevisibiliti').material_select();";







			$form .= "</script>";



			$form .= "<div class=\"row\" id=\"photoUploaderBox\" style=\"margin:0 -10px\">";



			if(count($pictures)){

				$i = 2;

				

				foreach($pictures as $picture) {

					$form .= "<div class=\"col s4 imageItem\" id=\"img".$i."\">

						<div class=\"card\">

				    		<div class=\"card-image waves-effect waves-block waves-light\">

				    			<input type=\"hidden\" name=\"managerFiles[]\" class=\"managerFiles\" value=\"".$picture['path']."\" />

				      			<img class=\"activator\" src=\"".Config::WEBSITE.Config::MAIN_LANG."/image/loadimage?f=".Config::WEBSITE_.$picture["path"]."&w=215&h=173\" />

				    		</div>



				    		<div class=\"card-content\">

			                	<p>

			                		<a href=\"javascript:void(0)\" onclick=\"openFileManager('photoUploaderBox', 'img".$i."')\" class=\"large material-icons\">mode_edit</a>

			                		<a href=\"javascript:void(0)\" onclick=\"removePhotoItem('img".$i."')\" class=\"large material-icons\">delete</a>

			                	</p>

			              	</div>



			    		</div>

			  		</div>";

			  		$i++;

				}

			}



			$form .= "<div class=\"col s4 imageItem\" id=\"img1\">

				<div class=\"card\">

		    		<div class=\"card-image waves-effect waves-block waves-light\">

		    			<input type=\"hidden\" name=\"managerFiles[]\" class=\"managerFiles\" value=\"\" />

		      			<img class=\"activator\" src=\"/public/img/noimage.png\" />

		    		</div>



		    		<div class=\"card-content\">

	                	<p>

	                		<a href=\"javascript:void(0)\" onclick=\"openFileManager('photoUploaderBox', 'img1')\" class=\"large material-icons\">mode_edit</a>

	                		<a href=\"javascript:void(0)\" onclick=\"removePhotoItem('img1')\" class=\"large material-icons\">delete</a>

	                	</p>

	              	</div>



	    		</div>

	  		</div>";



	  		$form .= "</div>";



			$form .= functions\makeForm::close();



			

			$this->out = array(

				"Error" => array(

					"Code"=>0, 

					"Text"=>"ოპერაცია შესრულდა წარმატებით !",

					"Details"=>""

				),

				"form" => $form,

				"attr" => "formCatalogEdit('".$idx."','".$lang."')"

			);	

		}



		



		return $this->out;

	}

}