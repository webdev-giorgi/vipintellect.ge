<?php 
namespace functions;

class tbcbank
{
	//private vars
	private $merchanthandler = "https://securepay.ufc.ge:18443/ecomm2/MerchantHandler";
	private $commandtype = "v";
	private $currency = 978; // Euro	
	private $msg_type = "SMS";
	private $certificate_path = "";
	private $certificate_password = "mH~1?7?d!kskoh";

	//public vars
	public $client_ip_addr = ""; 
	public $amount = 0;	
	public $language = "EN";
	public $description = "LemiVoyage";

	function __construct(){
		$this->certificate_path =  getcwd()."/sert.pem";
	}

	public function transitionid()
	{
		if(empty($this->client_ip_addr) || $this->client_ip_addr=="")
		{
			require_once("app/functions/server.php"); 
			$server = new \functions\server(); 
			$this->client_ip_addr = $server->ip();
		}

		$curl = curl_init();
		$post_fields = sprintf(
			"command=%s&amount=%s&currency=%d&client_ip_addr=%s&language=%s&description=%smsg_type=%s",
			$this->commandtype,
			$this->amount,
			$this->currency,
			$this->client_ip_addr,
			$this->language,
			$this->description,
			$this->msg_type
		);
		curl_setopt($curl, CURLOPT_SSLVERSION, 0);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
		curl_setopt($curl, CURLOPT_VERBOSE, '1');
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, '0');
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, '0');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 120);
		curl_setopt($curl, CURLOPT_SSLCERT, $this->certificate_path);
		curl_setopt($curl, CURLOPT_SSLKEYPASSWD, $this->certificate_password);
		curl_setopt($curl, CURLOPT_URL, $this->merchanthandler);
		$result = curl_exec($curl);
		$info = curl_getinfo($curl);

		if(curl_errno($curl)){
			echo 'Error:' . curl_errno($curl) . '<br />';
			return false;
		}
		curl_close($curl);

		return substr($result, -28);
	}

	public function getStatus($trans_id)
	{
		require_once("app/functions/server.php"); 
		$server = new \functions\server(); 

		$curl = curl_init();
		$post_fields = sprintf(
			"command=c&trans_id=%s&client_ip_addr=%s",
			urlencode($trans_id),
			$server->ip()
		);
		curl_setopt($curl, CURLOPT_SSLVERSION, 0);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
		curl_setopt($curl, CURLOPT_VERBOSE, '1');
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, '0');
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, '0');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 120);
		curl_setopt($curl, CURLOPT_SSLCERT, $this->certificate_path);
		curl_setopt($curl, CURLOPT_SSLKEYPASSWD, $this->certificate_password);
		curl_setopt($curl, CURLOPT_URL, $this->merchanthandler);
		$result = curl_exec($curl);
		$info = curl_getinfo($curl);

		if(curl_errno($curl)){
			echo 'Error:' . curl_errno($curl) . '<br />';
			return false;
		}
		curl_close($curl);

		return $result;
	}

	public function closeDay()
	{
		require_once("app/functions/server.php"); 
		$server = new \functions\server(); 

		$curl = curl_init();
		$post_fields = sprintf(
			"command=b&client_ip_addr=%s",
			$server->ip()
		);
		curl_setopt($curl, CURLOPT_SSLVERSION, 0);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
		curl_setopt($curl, CURLOPT_VERBOSE, '1');
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, '0');
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, '0');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 120);
		curl_setopt($curl, CURLOPT_SSLCERT, $this->certificate_path);
		curl_setopt($curl, CURLOPT_SSLKEYPASSWD, $this->certificate_password);
		curl_setopt($curl, CURLOPT_URL, $this->merchanthandler);
		$result = curl_exec($curl);
		$info = curl_getinfo($curl);

		if(curl_errno($curl)){
			echo 'Error:' . curl_errno($curl) . '<br />';
			return false;
		}
		curl_close($curl);

		return $result;
	}
}