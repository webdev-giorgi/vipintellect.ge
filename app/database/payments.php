<?php 
class payments
{
	private $conn;

	public function index($conn, $args)
	{
		$out = 0;
		$this->conn = $conn;
		if (
			isset($args['method']) && 
			is_string($args['method']) && 
			method_exists($this, $args['method'])
		) {
			$method = $args['method'];
			$out = $this->$method($args);
		}
		return $out;
	}

	private function selectIdByTransId($args)
	{
		$fetch = array();
		$sql = "SELECT `id` FROM `payments` WHERE `tbc_trans_id`=:tbc_trans_id";
		$prepare = $this->conn->prepare($sql);
		$prepare->execute(array(
			":tbc_trans_id"=>$args["trans_id"]
		));
		if($prepare->rowCount()){
			$fetch = $prepare->fetch(PDO::FETCH_ASSOC); 
		}
		return $fetch;
	}

	private function selectAll($args)
	{
		$fetch = array();
		$itemPerPage = $args['itemPerPage'];
		$from = (isset($_GET['pn']) && $_GET['pn']>0) ? (($_GET['pn']-1)*$itemPerPage) : 0;
		
		$select = "SELECT 
		(SELECT COUNT(`id`) FROM `payments`) as counted, 
		`payments`.* 
		FROM 
		`payments` 
		ORDER BY `date` DESC LIMIT ".$from.",".$itemPerPage;	
		$prepare = $this->conn->prepare($select); 
		$prepare->execute();
		if($prepare->rowCount()){
			$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);
		}
		return $fetch;
	}

	private function selectById($args)
	{
		$fetch = array();
		$select = "SELECT 
		(SELECT `users_website`.`firstname` FROM `users_website` WHERE `users_website`.`email`=`payments`.`username` AND `users_website`.`status`!=1) as firstname, 
		(SELECT `users_website`.`lastname` FROM `users_website` WHERE `users_website`.`email`=`payments`.`username` AND `users_website`.`status`!=1) as lastname, 
		(SELECT `users_website`.`phone` FROM `users_website` WHERE `users_website`.`email`=`payments`.`username` AND `users_website`.`status`!=1) as phone, 
		(SELECT `users_website`.`gender` FROM `users_website` WHERE `users_website`.`email`=`payments`.`username` AND `users_website`.`status`!=1) as gender, 
		(SELECT `products`.`title` FROM `products` WHERE `products`.`idx`=`payments`.`tour_id` AND `products`.`lang`=:lang AND `products`.`status`!=1) as product_title, 
		`payments`.* 
		FROM 
		`payments` 
		WHERE `id`=:id";	
		$prepare = $this->conn->prepare($select); 
		$prepare->execute(array(
			":id"=>$args["id"], 
			":lang"=>$_SESSION["LANG"]
		));
		if($prepare->rowCount()){
			$fetch = $prepare->fetch(PDO::FETCH_ASSOC);
		}
		return $fetch;
	}

	private function select($args)
	{
		require_once("app/functions/request.php"); 

		$fetch = "[]";
		$itemPerPage = $args['itemPerPage'];
		$from = (isset($_GET['pn']) && (int)$_GET['pn']>0) ? (((int)$_GET['pn']-1)*$itemPerPage) : 0;

		$json = Config::CACHE."payments_select_".str_replace(array("-"," "), "", implode("_",$_SESSION['URL'])).$from.$itemPerPage.".json";

		if(file_exists($json)){
			$fetch = @file_get_contents($json); 
		}else{

			$select = "SELECT 
			(SELECT COUNT(`payments`.`id`) FROM `payments` WHERE `payments`.`username`=:username) as counted, 
			`payments`.`id`, 
			`payments`.`date`, 
			`payments`.`tour_id`, 
			`payments`.`checkin_checkout`, 
			`payments`.`tbc_trans_id`, 
			`payments`.`tour_services`, 
			`payments`.`adults`, 
			`payments`.`children`, 
			`payments`.`children_ages`, 
			`payments`.`total_price`, 
			`payments`.`payment_status`, 
			`products`.`title`,
			(SELECT `photos`.`path` FROM `photos` WHERE `photos`.`parent`=`payments`.`tour_id` AND `photos`.`type`='products' AND `photos`.`lang`=:lang AND `photos`.`status`!=:one ORDER BY `photos`.`id` ASC LIMIT 1) AS photo
			FROM 
			`payments`, `products`
			WHERE 
			`payments`.`username`=:username AND 
			`payments`.`payment_status`=3 AND 
			`payments`.`tour_id`=`products`.`idx` AND 
			`products`.`pid`=:pid AND
			`products`.`lang`=:lang AND
			`products`.`status`!=:one AND 
			`products`.`showwebsite`=:two
			ORDER BY `payments`.`id` DESC LIMIT ".$from.",".$itemPerPage;


			$prepare = $this->conn->prepare($select); 
			$prepare->execute(array(
				":username"=>$args['user'], 
				":pid"=>3, 
				":lang"=>$args['lang'],
				":one"=>1,
				":two"=>2
			));

			// echo $prepare->debugDumpParams();

			if($prepare->rowCount()){
				$db_fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);

				$fh = @fopen($json, 'w') or die("Error opening output file");
				@fwrite($fh, json_encode($db_fetch,JSON_UNESCAPED_UNICODE));
				@fclose($fh);

				$fetch = @file_get_contents($json);
			}
		}
		return json_decode($fetch, true);
	}

	private function insert($args)
	{
		$insert = "INSERT INTO `payments` SET 
		`date`=:date, 
		`ip_address`=:ip_address, 
		`os`=:os, 
		`browser`=:browser, 
		`username`=:username, 
		`tbc_trans_id`=:tbc_trans_id, 
		`tour_id`=:tour_id, 
		`checkin_checkout`=:checkinCheckout, 
		`tour_services`=:tour_services, 
		`adults`=:adults, 
		`children`=:children, 
		`children_ages`=:children_ages, 
		`total_price`=:total_price, 
		`payment_status`=:payment_status, 
		`status`=:status";
		$prepare = $this->conn->prepare($insert);
		$prepare->execute(array(
			":date"=>time(),
			":ip_address"=>$args["ip_address"],
			":os"=>$args["os"],
			":browser"=>$args["browser"],
			":username"=>$_SESSION[Config::SESSION_PREFIX."web_username"],
			":tbc_trans_id"=>urlencode($args["tbc_trans_id"]),
			":tour_id"=>$args["tour_id"],
			":checkinCheckout"=>$args["checkinCheckout"],
			":tour_services"=>$args["tour_services"],
			":adults"=>$args["adults"],
			":children"=>$args["children"],
			":children_ages"=>$args["children_ages"],
			":total_price"=>$args["total_price"],
			":payment_status"=>1,
			":status"=>1
		)); 

		$this->clearCache();
		return 1;
	}

	private function setunpayed($args)
	{
		$update = "UPDATE `payments` SET 
		`payment_status`=:payment_status,
		`return_result_text`=:return_result_text, 
		`status`=2
		WHERE 
		`tbc_trans_id`=:tbc_trans_id AND 
		`username`=:username AND 
		`status`!=2
		";
		$prepare = $this->conn->prepare($update);
		$prepare->execute(array(
			":tbc_trans_id"=>$args["tbc_trans_id"],			
			":username"=>$_SESSION[Config::SESSION_PREFIX."web_username"],	
			":return_result_text"=>$args["result_text"],		
			":payment_status"=>2
		)); 
		return 1;
	}


	private function setpayed($args)
	{
		$update = "UPDATE `payments` SET 
		`payment_status`=:payment_status, 
		`return_result_text`=:return_result_text, 
		`status`=2
		WHERE 
		`tbc_trans_id`=:tbc_trans_id AND 
		`username`=:username AND 
		`status`!=2
		";
		$prepare = $this->conn->prepare($update);
		$prepare->execute(array(
			":tbc_trans_id"=>$args["tbc_trans_id"],	
			":username"=>$_SESSION[Config::SESSION_PREFIX."web_username"],		
			":return_result_text"=>$args["result_text"],		
			":payment_status"=>3
		)); 
		return 1;
	}

	private function setpayed_paypal($args)
	{
		$update = "UPDATE `payments` SET 
		`payment_status`=:payment_status, 
		`return_result_text`=:return_result_text, 
		`status`=2
		WHERE 
		`tbc_trans_id`=:paypal_trans_id AND 
		`username`=:username AND 
		`status`!=2
		";
		$prepare = $this->conn->prepare($update);
		$prepare->execute(array(
			":paypal_trans_id"=>$args["paypal_trans_id"],	
			":username"=>$_SESSION[Config::SESSION_PREFIX."web_username"],		
			":return_result_text"=>$args["result_text"],		
			":payment_status"=>3
		)); 
		return 1;
	}

	private function setunpayed_paypal($args)
	{
		$update = "UPDATE `payments` SET 
		`payment_status`=:payment_status,
		`return_result_text`=:return_result_text, 
		`status`=2
		WHERE 
		`tbc_trans_id`=:paypal_trans_id AND 
		`username`=:username AND 
		`status`!=2
		";
		$prepare = $this->conn->prepare($update);
		$prepare->execute(array(
			":paypal_trans_id"=>$args["paypal_trans_id"],			
			":username"=>$_SESSION[Config::SESSION_PREFIX."web_username"],	
			":return_result_text"=>$args["result_text"],		
			":payment_status"=>2
		)); 
		return 1;
	}

	private function paymentclose($args)
	{
		$insert = "INSERT INTO `payments_close` SET 
		`date`=:date, 
		`result`=:result";
		$prepare = $this->conn->prepare($insert);
		$prepare->execute(array(
			":date"=>time(),
			":result"=>$args["result"]
		));
		return 1;
	}

	private function clearCache()
	{
		$mask = Config::CACHE.'payments_*.*';
		array_map('unlink', glob($mask));
	}


}