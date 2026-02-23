<?php
class Pay extends Controller
{
	public $out;



	public function index($name = "")
	{
		echo $this->transaction_id(100, 981, "108.179.232.164", "EN", "UFCTEST");
		//echo $_SERVER["REMOTE_ADDR"];

		/* view */
		$this->view('pay/index', [
			"header"=>array(
				"website"=>Config::WEBSITE,
				"public"=>Config::PUBLIC_FOLDER
			)
		]);
	}

	private function transaction_id($amount, $currency, $client_ip_addr, $language, $description)
	{
		$curl = curl_init();
		$post_fields = sprintf(
			"command=v&amount=%s&currency=%s&client_ip_addr=%s&language=%s&description=%s&msg_type=SMS",
			$amount, // ტრანზაქციის თანხა
			$currency, // 981 ვალუტის ნომერი
			$client_ip_addr, // 127.0.0.1 მომხმარებლის ip მისამართი
			$language, // გადახდის ინტერფეისის ელა
			$description
		);
		$submit_url = "https://securepay.ufc.ge:18443/ecomm2/MerchantHandler";
		curl_setopt($curl, CURLOPT_SSLVERSION, 0);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
		curl_setopt($curl, CURLOPT_VERBOSE, '1');
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, '0');
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, '0');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 120);
		curl_setopt($curl, CURLOPT_SSLCERT, getcwd().'/securepay.ufc.ge_5301590_merchant_wp.pem'); // სერთიფიკატის მისამართი
		curl_setopt($curl, CURLOPT_SSLKEYPASSWD,  'pprSs-khkBa'); // სერთიფიკატის პაროლი
		curl_setopt($curl, CURLOPT_URL, $submit_url);
		$result = curl_exec($curl);
		$info = curl_getinfo($curl);
				
		if(curl_errno($curl))
		{
		    echo 'curl error:' . curl_error($curl)."<BR>";
		}

		curl_close($curl);
		return $result; 
		// substr($result, -28)
	}
}
?>