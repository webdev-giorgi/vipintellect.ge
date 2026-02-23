<?php 
class Catalog extends Controller
{
	public function __construct()
	{

	}

	public function index($name = "")
	{
		/* DATABASE */
		$db_langs = new Database("language", array(
			"method"=>"select"
		));
		
		$db_socials = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"social"
		));

		$db_navigation = new Database("page", array(
			"method"=>"select", 
			"cid"=>0, 
			"nav_type"=>0,
			"lang"=>$_SESSION['LANG'],
			"status"=>0 
		));

		$db_footerHelpNav = new Database("page", array(
			"method"=>"selecteByCid", 
			"cid"=>5, 
			"lang"=>$_SESSION['LANG']
		));

		$db_destinations = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"destination"
		));

		$db_tourtypes = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"tourtypes"
		));		

		$itemPerPage = 10;
		$countTours = 0;
		$db_tourlist = new Database("products", array(
			"method"=>"select", 
			"itemPerPage"=>$itemPerPage,
			"lang"=>$_SESSION['LANG'],
			"showwebsite"=>2
		));
		$list = $db_tourlist->getter();
		$countTours = (isset($list[0]["counted"])) ? $list[0]["counted"] : 0;

		$db_tourMaxMin = new Database("products", array(
			"method"=>"tourMaxMin"
		));
		$tourMaxMin = $db_tourMaxMin->getter();


		$s = (isset($_SESSION["URL"][1])) ? $_SESSION["URL"][1] : Config::MAIN_CLASS;
		$db_pagedata = new Database("page", array(
			"method"=>"selecteBySlug", 
			"slug"=>$s,
			"lang"=>$_SESSION['LANG'], 
			"all"=>true
		));

		/* HEDARE */
		$header = $this->model('_header');
		$header->public = Config::PUBLIC_FOLDER; 
		$header->lang = $_SESSION["LANG"]; 
		$header->pagedata = $db_pagedata; 

		/* SOCIAL */
		$social = $this->model('_social');
		$social->networks = $db_socials->getter(); 

		/* DESTINATIONS */
		$destinations = $this->model('_destinations');
		$destinations->data = $db_destinations->getter(); 

		/* Tour Types */
		$tourtypes = $this->model('_tourtypes');
		$tourtypes->data = $db_tourtypes->getter(); 

		/* LANGUAGES */
		$languages = $this->model('_lang'); 
		$languages->langs = $db_langs->getter();

		/* NAVIGATION */
		$navigation = $this->model('_navigation');
		$navigation->data = $db_navigation->getter();

		/* Tour List */
		$tourlist = $this->model('_tourlist');
		$tourlist->data = $db_tourlist->getter();

		/* header top */
		$headertop = $this->model('_top');
		$headertop->data["socialNetworksModule"] = $social->index();
		$headertop->data["languagesModule"] = $languages->index();
		$headertop->data["navigationModule"] = $navigation->index();

		/*footer */
		$footer = $this->model('_footer');
		$footer->data["socialNetworksModule"] = $social->index();
		$footer->data["footerHelpNav"] = $db_footerHelpNav->getter();

		/* view */
		$this->view('catalog/index', [
			"header"=>array(
				"website"=>Config::WEBSITE,
				"public"=>Config::PUBLIC_FOLDER
			),
			"headerModule"=>$header->index(), 
			"headertop"=>$headertop->index(), 
			"pageData"=>$db_pagedata->getter(), 
			"destinations"=>$db_destinations->getter(), 
			"destinationsOptions"=>$destinations->options(), 
			"tourtypesOptions"=>$tourtypes->options(), 
			"tourlist_db"=>$db_tourlist->getter(), 
			"tourlist"=>$tourlist->index(), 
			"itemPerPage"=>$itemPerPage, 
			"countTours"=>$countTours, 
			"tourMaxMin"=>$tourMaxMin, 
			"footer"=>$footer->index() 
		]);
	}
}