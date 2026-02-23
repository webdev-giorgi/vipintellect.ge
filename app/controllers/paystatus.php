<?php 
class Paystatus extends Controller
{
	private $paymentId; 
	private $PayerID; 

	public function __construct()
	{
		
	}

	public function index($name = "")
	{
		require_once("app/functions/redirect.php"); 

		if(!isset($_GET["success"]) || $_GET["success"] === "false"){
			functions\redirect::url(Config::WEBSITE.$_SESSION["LANG"]."/fail");	
		}

		if(!isset($_GET["paymentId"], $_GET["PayerID"])){
			die("Sorry, you dont have a permittion !");
		}

		$this->paymentId = $_GET["paymentId"];
		$this->PayerID = $_GET["PayerID"];


		$paypal = new PayPal\Rest\ApiContext(
			new PayPal\Auth\OAuthTokenCredential(
				Config::PAYPAL_CLIENT, 
				Config::PAYPAL_SECRET
			)
		);

		$paypal->setConfig(
			array(
				"mode"=>"live"
			)
		);

		$payment = PayPal\Api\Payment::get($this->paymentId, $paypal);

		$transactions = $payment->getTransactions();
		$invoiceId = $transactions[0]->invoice_number;

		$execute = new PayPal\Api\PaymentExecution();
		$execute->setPayerId($this->PayerID);

		try{
			$result = $payment->execute($execute, $paypal);

			$payments = new Database("payments", array(
				"method"=>"setpayed_paypal", 
				"paypal_trans_id"=>$invoiceId,
				"result_text"=>"Payment Made !",
			));
			$redirectMe = "/myaccount/?view=purchases";
		}catch(Exception $e){
			$payments = new Database("payments", array(
				"method"=>"setunpayed_paypal", 
				"paypal_trans_id"=>$invoiceId,
				"result_text"=>"Payment not Made !",
			));
			$redirectMe = "/fail";
		}

		functions\redirect::url(Config::WEBSITE.$_SESSION["LANG"].$redirectMe);		
	}
}