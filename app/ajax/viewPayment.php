<?php 
class viewPayment
{
	public $out; 

	public function __construct()
	{
		require_once("app/core/Config.php");
		if(!isset($_SESSION[Config::SESSION_PREFIX."username"]))
		{
			exit();
		}
	}
	
	public function index(){
		require_once("app/core/Config.php");
		require_once("app/functions/request.php");
		require_once("app/functions/getpayment.php");

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>"მოხდა შეცდომა !",
				"Details"=>"!"
			)
		);

		$id = functions\request::index("POST","id");
		
		$getpayment = new functions\getpayment();
		$table = $getpayment->index($id);		

		$this->out = array(
			"Error" => array(
				"Code"=>0, 
				"Text"=>"ოპერაცია შესრულდა წარმატებით !",
				"Details"=>""
			),
			"table" => $table
		);	

		return $this->out;
	}
}