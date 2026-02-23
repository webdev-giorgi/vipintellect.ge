<?php 
class Register extends Controller
{
	function __construct()
	{

	}

	public function index($lang = "")
	{
		/* DATABASE */
		$db_langs = new Database("language", array(
			"method"=>"select"
		));

		$db_contactdetails = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"contactdetails"
		));

		$db_howfindus = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"howfindus"
		));
	

		$db_navigation = new Database("page", array(
			"method"=>"select", 
			"cid"=>0, 
			"nav_type"=>0,
			"lang"=>$_SESSION['LANG'],
			"status"=>0 
		));

		$db_usefulllinks = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"usefulllinks"
		));

		$s = (isset($_SESSION["URL"][1])) ? $_SESSION["URL"][1] : Config::MAIN_CLASS;
		$db_pagedata = new Database("page", array(
			"method"=>"selecteBySlug", 
			"slug"=>$s,
			"lang"=>$_SESSION['LANG'], 
			"all"=>true
		));

		$db_socialnetworks = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"socialnetworks"
		));
		
		$db_footerHelpNav = new Database("page", array(
			"method"=>"selecteByCid", 
			"cid"=>7, 
			"lang"=>$_SESSION['LANG']
		));

		$db_news = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"news",
			"from"=>0,
			"num"=>Config::LEFTSIDE_NEWS_NUM
		));

		$db_staff = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"staff",
			"from"=>0,
			"num"=>Config::HOME_PAGE_STAFF_NUM
		));

		/* HEDARE */
		$header = $this->model('_header');
		$header->public = Config::PUBLIC_FOLDER; 
		$header->lang = $_SESSION["LANG"]; 	
		$header->pagedata = $db_pagedata; 	

		/* NAVIGATION */
		$navigation = $this->model('_navigation');
		$navigation->data = $db_navigation->getter();

		/* header top */
		$headertop = $this->model('_top');
		$headertop->data["contactdetails"] = $db_contactdetails->getter();
		$headertop->data["navigationModule"] = $navigation->index();

		/*footer */
		$footer = $this->model('_footer');
		$footer->data["contactdetails"] = $db_contactdetails->getter();
		$footer->data["footerHelpNav"] = $db_footerHelpNav->getter();
		$footer->data["usefulllinks"] = $db_usefulllinks->getter();
		$footer->data["socialnetworks"] = $db_socialnetworks->getter();

		/* Leftside news */
		$news = $this->model('_homenews');
		$news->data = $db_news->getter();

		/* Home page staff */
		$staff = $this->model('_homestaff');
		$staff->data = $db_staff->getter();

		$pageDatax = $db_pagedata->getter();
		
		$db_dub_navigation = new Database("page", array(
			"method"=>"select", 
			"cid"=>$pageDatax['idx'], 
			"nav_type"=>0,
			"lang"=>$_SESSION['LANG'],
			"status"=>0 
		));

	
		/* view */
		$this->view('register/index', [
			"header"=>array(
				"website"=>Config::WEBSITE,
				"public"=>Config::PUBLIC_FOLDER
			),
			"headerModule"=>$header->index(), 
			"pageData"=>$pageDatax, 
			"headertop"=>$headertop->index(), 
			"news"=>$news->index(), 
			"howfindus"=>$db_howfindus->getter(), 
			"staff"=>$staff->index(), 
			"sub_navigation"=>$db_dub_navigation->getter(), 
			"footer"=>$footer->index() 
		]);
	}
}