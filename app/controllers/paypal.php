<?php 
class Paypal extends Controller
{
	private $TRANS_ID = "";
	private $user_ip;
	private $user_os;
	private $user_browser;
	private $tour_id;
	private $checkinCheckout;
	private $tour_services = "";
	private $adults = 0;
	private $children = 0;
	private $childrens_ages = "";
	private $total_price = 0;

	private $client = "";
	private $secret = "";
	public $product;
	public $price;
	public $shipping = 0.00;
	public $total = 0;
	public $currency = "EUR";
	public $description = "Pay for Lemi voyage tour";
	public $invoiceNumber = 0;

	public function __construct()
	{

	}

	public function index($name = '')
	{
		require_once("app/functions/redirect.php");
		require_once("app/functions/request.php"); 
		require_once("app/functions/server.php"); 
		require_once("app/functions/strings.php"); 
		$this->invoiceNumber = functions\strings::random(15);
		if( 
			!functions\request::index("POST", "token") || 
			!isset($_SESSION["payment_token"]) || 
			functions\request::index("POST", "token") != $_SESSION["payment_token"] || 
			!functions\request::index("POST", "tour_id") || 
			!functions\request::index("POST", "checkinCheckout") || 
			!functions\request::index("POST", "adults") ||
			functions\request::index("POST", "adults")<=0
		){
			functions\redirect::url(Config::WEBSITE.$_SESSION["LANG"]."/home");
		}else{
			$this->tour_id = functions\request::index("POST", "tour_id");
			$this->checkinCheckout = functions\request::index("POST", "checkinCheckout");
			$this->adults = (int)functions\request::index("POST", "adults");
			$this->children = (int)functions\request::index("POST", "children");
			
			if(functions\request::index("POST", "tour_services")){
				$this->tour_services = functions\request::index("POST", "tour_services");
			}

			if(functions\request::index("POST", "childrens_ages")){
				$this->childrens_ages = functions\request::index("POST", "childrens_ages");
			}else{
				$this->childrens_ages = 0;
			}

			$server = new functions\server();
			$this->user_ip = $server->ip();
			$this->user_os = $server->os();
			$this->user_browser = $server->browser();
		}

		$db_selectbooked = new Database("products", array(
				"method"=>"selectById",
				"idx"=>(int)$this->tour_id, 
				"lang"=>$_SESSION['LANG']
		));	
		$booked = $db_selectbooked->getter();

		$price = $booked["price"];
		$servPrices = 0;
		if(!empty($this->tour_services)){
			$explode = explode(",", $this->tour_services); 
			foreach ($explode as $val) {
				$ex = explode(":", $val);
				$subid = (isset($ex[2])) ? $ex[2] : "";
				$db_service = new Database("service", array(
					"method"=>"subservicvesbyid", 
					"id"=>(int)$subid,
					"lang"=>$_SESSION["LANG"]
				));
				$fetch = $db_service->getter(); 
				$servPrices += (isset($fetch[0]["price"])) ? $fetch[0]["price"] : "";
			}
		}

		if($booked["tourist_points"]!="dynamic"){
  			$bookadultForMulty = 1;
  		}else{
  			$bookadultForMulty = $_POST["adults"];
  		}

		$totalPriceAdult = ((int)$price*(int)$bookadultForMulty)+($servPrices*(int)$bookadultForMulty);
		$totalPriceChild = (((int)$price/2)*(int)$_POST["children"])+(($servPrices/2)*(int)$_POST["children"]);

		$this->total_price = ceil($totalPriceAdult+$totalPriceChild);

		////////////////// PAYPAL CODE ////////////////////////////

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

		$this->product = (string)$booked["title"];
		$this->price = (float)$this->total_price;
		$this->total = $this->price + $this->shipping;

		$payer = new PayPal\Api\Payer();
		$payer->setPaymentMethod("paypal");

		$item = new PayPal\Api\Item();
		$item->setName($this->product)
		->setCurrency($this->currency)
		->setQuantity(1)
		->setPrice($this->price);

		$itemList = new PayPal\Api\ItemList();
		$itemList->setItems([$item]);

		$details = new PayPal\Api\Details();
		$details->setShipping($this->shipping)
		->setSubtotal($this->price);
		
		$amount = new PayPal\Api\Amount();
		$amount->setCurrency($this->currency)
		->setTotal($this->total)
		->setDetails($details);

		$transaction = new PayPal\Api\Transaction();
		$transaction->setAmount($amount)
		->setItemList($itemList)
		->setDescription($this->description)
		->setInvoiceNumber($this->invoiceNumber);

		$redirectUrls = new PayPal\Api\RedirectUrls();
		$redirectUrls->setReturnUrl(Config::WEBSITE.$_SESSION["LANG"]."/paystatus?success=true")
		->setCancelUrl(Config::WEBSITE.$_SESSION["LANG"]."/paystatus?success=false");

		$payment = new PayPal\Api\Payment();
		$payment->setIntent("sale")
		->setPayer($payer)
		->setRedirectUrls($redirectUrls)
		->setTransactions([$transaction]);

		try{
			$payment->create($paypal);
		}catch(Exception $e){
			echo $e;
			exit();
		}

		$approvalUrl = $payment->getApprovalLink();

		$payments = new Database("payments", array(
			"method"=>"insert", 
			"ip_address"=>$this->user_ip,
			"os"=>$this->user_os,
			"browser"=>$this->user_browser,
			"tbc_trans_id"=>$this->invoiceNumber,
			"tour_id"=>$this->tour_id,
			"checkinCheckout"=>$this->checkinCheckout,
			"tour_services"=>$this->tour_services,
			"adults"=>$this->adults,
			"children"=>$this->children,
			"children_ages"=>$this->childrens_ages,
			"total_price"=>$this->total_price,
			"payment_status"=>1,
			"status"=>0
		));

		header("Location: {$approvalUrl}");
	}
}