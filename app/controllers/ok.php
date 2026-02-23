<?php 
class Ok extends Controller
{
	public function __construct()
	{
		
	}

	public function index($name = '')
	{
		require_once("app/functions/redirect.php");
		require_once("app/functions/tbcbank.php");
		require_once("app/functions/getpayment.php");
		require_once("app/functions/send.php");

		if(!isset($_REQUEST["trans_id"]) || empty($_REQUEST["trans_id"])){
			functions\redirect::url(Config::WEBSITE.$_SESSION["LANG"]."/fail");
			exit();
		}

		$tbcbank = new functions\tbcbank();
		$result = $tbcbank->getStatus($_REQUEST["trans_id"]);

		if(preg_match('/RESULT_CODE: 000/', $result)){
			$payments = new Database("payments", array(
				"method"=>"setpayed",
				"tbc_trans_id"=>urlencode($_REQUEST["trans_id"]),
				"result_text"=>$result
			));
			$redirectMe = "/myaccount/?view=purchases";
		}else{
			$payments = new Database("payments", array(
				"method"=>"setunpayed",
				"tbc_trans_id"=>urlencode($_REQUEST["trans_id"]), 
				"result_text"=>$result
			));
			$redirectMe = "/fail";
		}

		/* SEND EMAIL SELECTION start */
		$paymentId = new Database("payments", array(
			"method"=>"selectIdByTransId", 
			"trans_id"=>urlencode($_REQUEST["trans_id"])
		)); 
		$fetch = $paymentId->getter();

		$getpayment = new functions\getpayment();
		$send = new functions\send();
		$table = $getpayment->index($fetch["id"]);
		$table .= "<br /><br />";
		$table .= "<strong>Result:</strong><br />";
		$table .= $result;

		$send->index(array(
			"sendTo"=>Config::RECIEVER_EMAIL,
			"subject"=>Config::NAME." - TBC PAYMENT TRY",
			"body"=>$table
		));
		/* SEND EMAIL SELECTION end */

		functions\redirect::url(Config::WEBSITE.$_SESSION["LANG"].$redirectMe);
	}
}