<?php 
class Event extends Controller
{
	public function __construct()
	{
		
	}

	public function index($lang = '', $newsId = '')
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

		$s = (isset($_SESSION["URL"][1])) ? $_SESSION["URL"][1] : Config::MAIN_CLASS;
		$db_pagedata = new Database("page", array(
			"method"=>"selecteBySlug", 
			"slug"=>$s,
			"lang"=>$_SESSION['LANG'], 
			"all"=>true
		));
		$db_footer = new Database("modules", array(
			"method"=>"selectById", 
			"idx"=>18,
			"lang"=>$_SESSION['LANG']
		));

		$db_publicationss = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"publications",
			"from"=>0, 
			"num"=>4
		));

		/* HEDARE */
		$header = $this->model('_header');
		$header->public = Config::PUBLIC_FOLDER; 
		$header->lang = $_SESSION["LANG"]; 
		

		/* SOCIAL */
		$social = $this->model('_social');
		$social->networks = $db_socials->getter(); 

		/* LANGUAGES */
		$languages = $this->model('_lang'); 
		$languages->langs = $db_langs->getter();

		/* NAVIGATION */
		$navigation = $this->model('_navigation');
		$navigation->data = $db_navigation->getter();

		/* publications */
		$publications = $this->model('_publications');
		$publications->data = $db_publicationss->getter(); 

		/* header top */
		$headertop = $this->model('_top');
		$headertop->data["socialNetworksModule"] = $social->index();
		$headertop->data["languagesModule"] = $languages->index();
		$headertop->data["navigationModule"] = $navigation->index();

		/*footer */
		$footer = $this->model('_footer');
		$footer->data = $db_footer->getter(); 

		if(isset($newsId) && is_numeric($newsId)){

			$db_events = new Database("modules", array(
				"method"=>"selectById", 
				"lang"=>$_SESSION['LANG'],  
				"idx"=>$newsId 
			));
			$header->pagedata = $db_events; 
			/* MAIN NEWS */
			$mainevents = $this->model('_mainevents');
			$mainevents->data = $db_events->getter();
			/* view */
			$this->view('event/index', [
				"header"=>array(
					"website"=>Config::WEBSITE,
					"public"=>Config::PUBLIC_FOLDER
				),
				"headerModule"=>$header->index(), 
				"headertop"=>$headertop->index(), 
				"pageData"=>$db_pagedata->getter(), 
				"mainevents"=>$mainevents->index(), 
				"publications"=>$publications->index(), 
				"footer"=>$footer->index() 
			]);	
		}else{
			require_once('app/functions/redirect.php'); 
			functions\redirect::url(Config::WEBSITE);
		}
	}
}