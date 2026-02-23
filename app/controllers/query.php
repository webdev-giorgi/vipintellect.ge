<?php
class Query extends Controller
{
	public function __construct()
	{
		
	}

	public function index($name = '')
	{
		/* DATABASE */
		$db_query = new Database("changedb", array(
			"method"=>"selectandinsert"
		));
	}
}