<?php 
class updateCatalog
{
	public $out; 
	
	public function __construct()
	{
		
	}
	
	public function index(){
		require_once 'app/core/Config.php';
		require_once 'app/functions/request.php';

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>"მოხდა შეცდომა !",
				"Details"=>"!"
			),
			"Success" => array(
				"Code"=>0, 
				"Text"=>"",
				"Details"=>"!"
			)
		);

		$idx = filter_var(functions\request::index("POST","idx"), FILTER_SANITIZE_NUMBER_INT);
		$date = functions\request::index("POST","date");
		$title = functions\request::index("POST","title");
		$cover = functions\request::index("POST","cover");
		$chooseDestination = unserialize(functions\request::index("POST","chooseDestination"));
		$chooseAdvantureType = unserialize(functions\request::index("POST","chooseAdvantureType"));
		$arrivaldeparture = functions\request::index("POST","arrivaldeparture");
		$daysAndNights = functions\request::index("POST","daysAndNights");
		$chooseTouristCount = functions\request::index("POST","chooseTouristCount");
		$price = functions\request::index("POST","price");
		$shortDescription = functions\request::index("POST","shortDescription");
		$longDescription = functions\request::index("POST","longDescription");
		$locations = functions\request::index("POST","locations");
		
		$showwebsite = functions\request::index("POST","choosevisibiliti");
		$chooseSpecial_offer = functions\request::index("POST","chooseSpecial_offer");
		$serialServices = unserialize(functions\request::index("POST","serialServices"));
		
		$lang = functions\request::index("POST","lang");


		$serialPhotos = unserialize(functions\request::index("POST","serialPhotos"));

		$Database = new Database('products', array(
			'method'=>'edit', 
			'idx'=>$idx, 
			'date'=>$date, 
			'title'=>$title, 
			'cover'=>$cover, 
			'chooseDestination'=>$chooseDestination, 
			'chooseAdvantureType'=>$chooseAdvantureType, 
			'checkinout'=>$arrivaldeparture, 
			'daysAndNights'=>$daysAndNights, 
			'tourist_points'=>$chooseTouristCount, 
			'price'=>$price, 
			'shortDescription'=>$shortDescription, 
			'longDescription'=>$longDescription, 
			'locations'=>$locations, 
			'showwebsite'=>$showwebsite, 
			'chooseSpecial_offer'=>$chooseSpecial_offer, 
			'serialServices'=>$serialServices, 
			'lang'=>$lang, 
			'serialPhotos'=>$serialPhotos 
		));

		$this->out = array(
			"Error" => array(
				"Code"=>0, 
				"Text"=>"",
				"Details"=>""
			),
			"Success"=>array(
				"Code"=>1, 
				"Text"=>"ოპერაცია შესრულდა წარმატებით !",
				"Details"=>""
			)
		);

		return $this->out;
	}
}