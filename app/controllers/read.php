<?php 
class Read extends Controller
{
	
	public function __construct()
	{
		
	}

	public function index($name = '')
	{
		/* view */
		$this->view('read/index', [
			"header"=>array(
				"website"=>Config::WEBSITE,
				"public"=>Config::PUBLIC_FOLDER
			)
		]);
	}

}