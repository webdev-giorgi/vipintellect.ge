<?php 
class Payout extends Controller{
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

	public function __construct()
	{
		require_once("app/functions/redirect.php");
		require_once("app/functions/request.php"); 
		require_once("app/functions/server.php"); 

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
			}

			$server = new functions\server();
			$this->user_ip = $server->ip();
			$this->user_os = $server->os();
			$this->user_browser = $server->browser();
		}
	}

	public function index($name = "")
	{
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

		/* GENERATE TRANSATION ID start */
		require_once("app/functions/tbcbank.php"); 
		$tbcbank = new functions\tbcbank();
		$tbcbank->client_ip_addr = $this->user_ip;
		$tbcbank->amount = ceil($this->total_price) * 100;
		$tbcbank->description = urlencode($booked["title"]);
		$this->TRANS_ID = $tbcbank->transitionid();
		/* GENERATE TRANSATION ID end */

		$payments = new Database("payments", array(
			"method"=>"insert", 
			"ip_address"=>$this->user_ip,
			"os"=>$this->user_os,
			"browser"=>$this->user_browser,
			"tbc_trans_id"=>$this->TRANS_ID,
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

		/* view */
		$this->view('payout/redirecttopay', [
			"header"=>array(
				"website"=>Config::WEBSITE,
				"public"=>Config::PUBLIC_FOLDER
			),
			"trans_id"=>$this->TRANS_ID
		]);

		// echo $payments->getter();
	}
}