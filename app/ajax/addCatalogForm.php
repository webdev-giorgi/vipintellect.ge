<?php 
class addCatalogForm
{
	public $out; 

	public function __construct()
	{
		require_once 'app/core/Config.php';
		if(!isset($_SESSION[Config::SESSION_PREFIX."username"]))
		{
			exit();
		}
	}
	
	public function index(){
		require_once 'app/core/Config.php';
		require_once 'app/functions/makeForm.php';
		require_once 'app/functions/request.php';
		require_once 'app/functions/strings.php';

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>"მოხდა შეცდომა !",
				"Details"=>"!"
			)
		);

		$catalogId = functions\request::index("POST","catalogId");
		$lang = functions\request::index("POST","lang");
		$random = functions\strings::random(25);

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
			"value"=>date("d-m-Y")
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
			"value"=>""
		));


		$form .= functions\makeForm::label(array(
			"id"=>"coverPhotoLabel", 
			"for"=>"coverPhoto", 
			"name"=>"Cover ფოტო ( რედაქტირებისთვის დაკლიკეთ ფოტოზე )",
			"require"=>""
		));
		$form .= "<input type=\"hidden\" name=\"cover\" class=\"cover\" value=\"\" />";
		$form .= "<div class=\"coverphoto\" onclick=\"openFileManagerForProductCover(100000)\"></div>";


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
			"value"=>""
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
			"value"=>""
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
			"selected"=>"dynamic",
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
			"value"=>""
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
			"value"=>""
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
			"value"=>""
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
			"value"=>""
		));

		$form .= "<script type=\"text/javascript\">
		window.onmessage = function(e){
		    console.log(e.data);
		    $(\"#locations\").val(e.data);
		};
		</script>";
		
		$_SESSION["token"] = $random;
		$form .= sprintf(
			"<iframe class=\"locationsMap\" src=\"%s?token=%s\"></iframe>",
			Config::PUBLIC_FOLDER."googleMap/index.php",
			$random
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
		$form .= functions\makeForm::checkbox(array(
			"chackboxTitle"=>$val["title"], 
			"name"=>"service".$val["idx"], 
			"id"=>"serviceId".$val["idx"],
			"value"=>"1"
		));

		$form .= "<section class=\"subServices serviceId".$val["idx"]."\">";
		$form .= "<section class=\"allfields\" id=\"field".$val["idx"]."\" data-service=\"".$val["idx"]."\"></section>";
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
			"choose"=>"აირჩიეთ ხილვადობა",
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

		$form .= "<div class=\"row\" id=\"photoUploaderBox\" style=\"margin:0 -10px\">";
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
			"attr" => "formCatalogAdd('".$catalogId."', '".$lang."')"
		);



		return $this->out;
	}
}