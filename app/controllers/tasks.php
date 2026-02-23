<?php 
class tasks extends Controller
{

	public function __construct()
	{
		if(!isset($_SESSION[Config::SESSION_PREFIX."username"]))
		{
			require_once 'app/functions/redirect.php';
			functions\redirect::url("/".$_SESSION["LANG"]."/manager/index");
		}

		
	}

	public function index()
	{
		/* view */
		$this->view('tasks/index', [
			"header" => array(
				"website" => Config::WEBSITE,
				"public" => Config::PUBLIC_FOLDER
			)
		]);
	}

}