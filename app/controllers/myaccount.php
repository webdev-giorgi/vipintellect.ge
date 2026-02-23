<?php 
class Myaccount extends Controller
{
	public function __construct()
	{
		require_once("app/functions/request.php"); 
		if(
			!isset($_SESSION[Config::SESSION_PREFIX."web_username"]) || 
			!functions\request::index("GET", "view") ||
			(
				functions\request::index("GET", "view")!="purchases" && 
				functions\request::index("GET", "view")!="favourites" && 
				functions\request::index("GET", "view")!="profile" && 
				functions\request::index("GET", "view")!="changepassword" 
			)
		)
		{
			require_once 'app/functions/redirect.php';
			functions\redirect::url(Config::WEBSITE.$_SESSION["LANG"]."/home");
		}
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


		$db_myaccountnav = new Database("page", array(
			"method"=>"selecteMyaccountNav", 
			"lang"=>$_SESSION['LANG']
		));

		$db_userdata = new Database("user", array(
			"method"=>"select", 
			"email"=>$_SESSION[Config::SESSION_PREFIX."web_username"]
		));

		$s = (isset($_SESSION["URL"][1])) ? $_SESSION["URL"][1] : Config::MAIN_CLASS;
		$db_pagedata = new Database("page", array(
			"method"=>"selecteBySlug", 
			"slug"=>$s,
			"lang"=>$_SESSION['LANG'], 
			"all"=>true
		));

		$itemPerPage = 10;
		$db_favourites = new Database("favourites", array(
			"method"=>"select", 
			"itemPerPage"=>$itemPerPage,
			"user"=>$_SESSION[Config::SESSION_PREFIX."web_username"],
			"lang"=>$_SESSION['LANG']
		));
		$list = $db_favourites->getter();
		$countFavourites = (isset($list[0]["counted"])) ? $list[0]["counted"] : 0;

		$itemPerPage2 = 10;
		$db_payments = new Database("payments", array(
			"method"=>"select", 
			"itemPerPage"=>$itemPerPage,
			"user"=>$_SESSION[Config::SESSION_PREFIX."web_username"],
			"lang"=>$_SESSION['LANG']
		));
		$list2 = $db_payments->getter();
		$countPayments = (isset($list2[0]["counted"])) ? $list2[0]["counted"] : 0;

		/* HEDARE */
		$header = $this->model('_header');
		$header->public = Config::PUBLIC_FOLDER; 
		$header->lang = $_SESSION["LANG"]; 
		$header->pagedata = $db_pagedata; 

		/* SOCIAL */
		$social = $this->model('_social');
		$social->networks = $db_socials->getter(); 


		/* LANGUAGES */
		$languages = $this->model('_lang'); 
		$languages->langs = $db_langs->getter();

		/* NAVIGATION */
		$navigation = $this->model('_navigation');
		$navigation->data = $db_navigation->getter();

		/* My account navigation */
		$myaccountnav = $this->model('_myaccountnav');
		$myaccountnav->data = $db_myaccountnav->getter();

		/* favourites List */
		$favouriteslist = $this->model('_favouriteslist');
		$favouriteslist->data = $list;

		/* purchases List */
		$purchaseslist = $this->model('_purchaseslist');
		$purchaseslist->data = $list2;

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
		$this->view('myaccount/index', [
			"header"=>array(
				"website"=>Config::WEBSITE,
				"public"=>Config::PUBLIC_FOLDER
			),
			"headerModule"=>$header->index(), 
			"headertop"=>$headertop->index(), 
			"pageData"=>$db_pagedata->getter(), 
			"myaccountnav"=>$myaccountnav->index(), 
			"userdata"=>$db_userdata->getter(), 
			"favouriteslist"=>$favouriteslist->index(), 
			"itemPerPage"=>$itemPerPage, 
			"countFavourites"=>$countFavourites, 
			"purchaseslist"=>$purchaseslist->index(),
			"footer"=>$footer->index() 
		]);
	}
}