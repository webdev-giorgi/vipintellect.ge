<?php 
class Search extends Controller
{
	public function __construct()
	{

	}

	public function index($lang)
	{
		require_once("app/functions/request.php");
		$word = "";
		if(functions\request::index("GET","w")){
			$word = strip_tags(functions\request::index("GET","w"));
			$word = str_replace(
				array("-", "%20", "'", '"'),
				array(" ", " ", "", ""),
				$word
			); 
		}

		$db_search = new Database("searchBy", array(
			"method"=>"select", 
			"word"=>$word,
			"lang"=>$_SESSION['LANG']
		));

		/* DATABASE */
		$db_langs = new Database("language", array(
			"method"=>"select"
		));

		$db_contactdetails = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"contactdetails"
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

		$pageDatax = $db_pagedata->getter();
		
		$db_dub_navigation = new Database("page", array(
			"method"=>"select", 
			"cid"=>$pageDatax['idx'], 
			"nav_type"=>0,
			"lang"=>$_SESSION['LANG'],
			"status"=>0 
		));

		
	
		/* view */
		$this->view('search/index', [
			"header"=>array(
				"website"=>Config::WEBSITE,
				"public"=>Config::PUBLIC_FOLDER
			),
			"headerModule"=>$header->index(), 
			"pageData"=>$pageDatax, 
			"headertop"=>$headertop->index(), 
			"sub_navigation"=>$db_dub_navigation->getter(), 
			"word"=>$word, 
			"search"=>$db_search->getter(), 
			"footer"=>$footer->index() 
		]);
	}
}