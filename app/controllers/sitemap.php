<?php
class Sitemap extends Controller
{

	public function __construct()
	{

	}

	public function index($lang)
	{

		$navigation1 = new Database("page", array(
			"method"=>"select", 
			"cid"=>0, 
			"nav_type"=>0,
			"lang"=>$_SESSION['LANG'],
			"status"=>0 
		));

		/* SITEMAP */
		$sitemap = $this->model('_sitemap');
		$sitemap->data = $navigation1->getter();

		header("Content-Type: application/xml; charset=UTF-8");

		/* view */
		$this->view('sitemap/index', [
			"header"=>array(
				"website"=>Config::WEBSITE,
				"public"=>Config::PUBLIC_FOLDER
			),
			"sitemap"=>$sitemap->index()
		]);
	}
}