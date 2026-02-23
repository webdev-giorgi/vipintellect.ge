<?php 
class Closeday extends Controller
{

	public function __construct()
	{
		
	}

	public function index($name = '')
	{
		require_once("app/functions/tbcbank.php");
		
		$tbcbank = new functions\tbcbank();
		$result = $tbcbank->closeDay();

		if(preg_match('/RESULT_CODE: 500/', $result)){
			$payments = new Database("payments", array(
				"method"=>"paymentclose", 
				"result"=>$result
			));
			echo $result;			
		}
	}

}