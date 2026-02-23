<?php 
class Vacancies extends Controller
{
	public function __construct()
	{

	}

	public function index($name = "", $vacanciesId = "")
	{
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
			"type"=>"usefulllinks",
			"order"=>"`date`",
			"by"=>"DESC"
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
			"type"=>"socialnetworks",
			"order"=>"`date`",
			"by"=>"DESC"
		));
		
		$db_footerHelpNav = new Database("page", array(
			"method"=>"selecteByCid", 
			"cid"=>7, 
			"lang"=>$_SESSION['LANG']
		));

		$db_news = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"news",
			"order"=>"`date`",
			"by"=>"DESC",
			"from"=>0,
			"num"=>Config::LEFTSIDE_NEWS_NUM
		));

		/* HEDARE */
		$header = $this->model('_header');
		$header->public = Config::PUBLIC_FOLDER; 
		$header->lang = $_SESSION["LANG"]; 		

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

		if(!isset($vacanciesId) || !is_numeric($vacanciesId)){ 
			$header->pagedata = $db_pagedata; 
			
			$fromvacancies = (isset($_GET['pn']) && is_numeric($_GET['pn']) && $_GET['pn']>0) ? ($_GET['pn']-1)*Config::VACANCIES_PER_PAGE : 0;
			
			$db_vacancies = new Database("modules", array(
				"method"=>"selectModuleByType", 
				"type"=>"vacancies",
				"from"=>$fromvacancies,
				"order"=>"`date`",
				"by"=>"DESC",
				"num"=>Config::VACANCIES_PER_PAGE
			));

			/* vacancies */
			$vacancies = $this->model('_vacancies');
			$vacancies->data = $db_vacancies->getter();

			/* view */
			$this->view('vacancies/index', [
				"header"=>array(
					"website"=>Config::WEBSITE,
					"public"=>Config::PUBLIC_FOLDER
				),
				"headerModule"=>$header->index(), 
				"pageData"=>$db_pagedata->getter(), 
				"vacancies"=>$vacancies->index(), 
				"vacanciesId"=>$vacanciesId,
				"headertop"=>$headertop->index(),
				"news"=>$news->index(),
				"footer"=>$footer->index() 
			]);
		}else{
			$db_vacancies = new Database("modules", array(
				"method"=>"selectById", 
				"lang"=>$_SESSION['LANG'],  
				"idx"=>$vacanciesId
			));
			$header->pagedata = $db_vacancies; 
			
			/* view */
			$this->view('vacancies/index', [
				"header"=>array(
					"website"=>Config::WEBSITE,
					"public"=>Config::PUBLIC_FOLDER
				),
				"headerModule"=>$header->index(), 
				"pageData"=>$db_pagedata->getter(), 
				"headertop"=>$headertop->index(), 
				"vacanciesId"=>$vacanciesId,
				"vacancies_inside"=>$db_vacancies->getter(),
				"news"=>$news->index(),
				"footer"=>$footer->index() 
			]);
		}
	}

}