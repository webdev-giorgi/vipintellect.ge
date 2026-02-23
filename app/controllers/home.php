<?php
class Home extends Controller
{
	public function __construct()
	{
		
	}

	public function index($name = '')
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
			"type"=>"howfindus",
			"order"=>"`date`",
			"by"=>"DESC"
		));

		$db_slider = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"slider",
			"order"=>"`date`",
			"by"=>"DESC",
			"from"=>0, 
			"num"=>15
		));

		$db_usefulllinks = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"usefulllinks",
			"order"=>"`date`",
			"by"=>"DESC"
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
			"cid"=>7, 
			"lang"=>$_SESSION['LANG']
		));

		$db_slogan = new Database("modules", array(
			"method"=>"selectById", 
			"idx"=>7,
			"lang"=>$_SESSION["LANG"]
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

		$db_news = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"news",
			"order"=>"`date`",
			"by"=>"DESC",
			"from"=>0,
			"num"=>Config::HOME_PAGE_NEWS_NUM
		));

		$db_vacancies = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"vacancies",
			"order"=>"`date`",
			"by"=>"DESC",
			"from"=>0,
			"num"=>Config::HOME_PAGE_VACANCIES_NUM
		));

		$db_staff = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"staff",
			"order"=>"`date`",
			"by"=>"DESC",
			"from"=>0,
			"num"=>Config::HOME_PAGE_STAFF_NUM
		));

		$db_partners = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"partners",
			"order"=>"`date`",
			"by"=>"DESC",
			"from"=>0,
			"num"=>Config::HOME_PAGE_PARTNERS_NUM
		));

		$db_gallery = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"gallery",
			"order"=>"`date`",
			"by"=>"DESC",
			"from"=>0,
			"num"=>Config::HOME_PAGE_GALLERY_NUM
		));

		/* gallery module */
		$gallery = $this->model('_gallery');
		$gallery->data = $db_gallery->getter();
		

		/* HEDARE */
		$header = $this->model('_header');
		$header->public = Config::PUBLIC_FOLDER; 
		$header->lang = $_SESSION["LANG"]; 
		$header->pagedata = $db_pagedata; 

		/* NAVIGATION */
		$navigation = $this->model('_navigation');
		$navigation->data = $db_navigation->getter();

		/* Home page news */
		$news = $this->model('_homenews');
		$news->data = $db_news->getter();

		/* Home page staff */
		$staff = $this->model('_homestaff');
		$staff->data = $db_staff->getter();

		/* Home page staff */
		$vacancies = $this->model('_homevacancies');
		$vacancies->data = $db_vacancies->getter();

		/* slidr */
		$slider = $this->model('_slider');
		$slider->data = $db_slider->getter(); 

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

		/* view */
		$this->view('home/index', array(
			"header"=>array(
				"website"=>Config::WEBSITE,
				"public"=>Config::PUBLIC_FOLDER
			),
			"headerModule"=>$header->index(),
			"headertop"=>$headertop->index(),
			"pageData"=>$db_pagedata->getter(),
			"slogan"=>$db_slogan->getter(),
			"howfindus"=>$db_howfindus->getter(),
			"news"=>$news->index(),
			"staff"=>$staff->index(),
			"gallery"=>$gallery->index(),
			"vacancies"=>$vacancies->index(),
			"slider"=>$slider->index(),
			"partners"=>$db_partners->getter(),
			"footer"=>$footer->index()
		));
	}

}