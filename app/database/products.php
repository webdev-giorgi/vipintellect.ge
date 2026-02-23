<?php 
class products
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

	private function select($args)
	{
		require_once("app/functions/request.php"); 
		$destinationSql = (functions\request::index("GET", "destination")) ? " FIND_IN_SET(".(int)functions\request::index("GET", "destination").", `products`.`destination`) AND " : "";
		$destinationJson = (functions\request::index("GET", "destination")) ? (int)functions\request::index("GET", "destination") : "";

		$tourtypeSql = (functions\request::index("GET", "tourtype")) ? " FIND_IN_SET(".(int)functions\request::index("GET", "tourtype").", `products`.`advanture_type`) AND " : "";
		$tourtypeJson = (functions\request::index("GET", "tourtype")) ? (int)functions\request::index("GET", "tourtype") : "";

		$postTitle = (functions\request::index("GET", "title")) ? str_replace(array('"',"'"),"",stripslashes(strip_tags(functions\request::index("GET", "title")))) : "";

		$titleSql = (functions\request::index("GET", "title")) ? " `products`.`title` LIKE '%".$postTitle."%' AND " : "";
		$titleJson = (functions\request::index("GET", "title")) ? $postTitle : "";

		$priceSql = "";
		$priceJson = "";
		if(functions\request::index("GET", "price")){
			$price = explode(",", functions\request::index("GET", "price"));
			$from_price = (isset($price[0])) ? (int)$price[0] : 0;
			$to_price = (isset($price[1])) ? (int)$price[1] : 0;

			$priceSql = " (`products`.`price`>=".$from_price." AND `products`.`price`<=".$to_price.") AND ";
			$priceJson = $from_price.$to_price;
		}

		$arrivalJson = "";
		$table_products_dates = "";
		$select_products_dates_distinct = "";
		$where_product_dates = "";		
		if(functions\request::index("GET", "arrival") || functions\request::index("GET", "departure")){

			$arrival = (functions\request::index("GET", "arrival")) ? functions\request::index("GET", "arrival") : "";
			$departure = (functions\request::index("GET", "departure")) ? functions\request::index("GET", "departure") : "";

			// 21-03-2018
			$exDate = @explode("-", $arrival);
			if(isset($exDate[0]) && isset($exDate[1]) && isset($exDate[2])){
				$string1 = trim($exDate[1])."/".trim($exDate[0])."/".trim($exDate[2]);
				$from = strtotime($string1);
			}			
			$arrival_strtotime = (isset($from)) ? $from : 0;
			
			$exDate2 = @explode("-", $departure);
			if(isset($exDate2[0]) && isset($exDate2[1]) && isset($exDate2[2])){
				$string2 = trim($exDate2[1])."/".trim($exDate2[0])."/".trim($exDate2[2]);
				$to = strtotime($string2);
			}
			$departure_strtotime = (isset($to)) ? $to : 0;

			$arrivalJson = "inout2".$arrival_strtotime.$departure_strtotime;

			if(functions\request::index("GET", "arrival") && functions\request::index("GET", "departure") && $arrival_strtotime <= $departure_strtotime){
				$select_products_dates_distinct = "DISTINCT `products_dates`.`pid`, ";
				$table_products_dates = "`products_dates`, ";
				$where_product_dates = "`products_dates`.`checkin`>=".$arrival_strtotime." AND "; 
				$where_product_dates .= "`products_dates`.`checkout`<=".$departure_strtotime." AND "; 
				$where_product_dates .= "`products_dates`.`pid`=`products`.`idx` AND "; 
			}else if(functions\request::index("GET", "arrival")){
				$select_products_dates_distinct = "DISTINCT `products_dates`.`pid`, ";
				$table_products_dates = "`products_dates`, ";
				$where_product_dates = "`products_dates`.`checkin`>=".$arrival_strtotime." AND "; 
				$where_product_dates .= "`products_dates`.`pid`=`products`.`idx` AND "; 
			}else if(functions\request::index("GET", "departure")){ 
				$select_products_dates_distinct = "DISTINCT `products_dates`.`pid`, ";
				$table_products_dates = "`products_dates`, ";
				$where_product_dates = "`products_dates`.`checkout`<=".$departure_strtotime." AND "; 
				$where_product_dates .= "`products_dates`.`pid`=`products`.`idx` AND ";
			}
		}

		$fetch = "[]";
		$itemPerPage = $args['itemPerPage'];
		$from = (isset($_GET['pn']) && $_GET['pn']>0) ? (((int)$_GET['pn']-1)*$itemPerPage) : 0;

		// $parsed_url = $args['parsed_url'];
		if(isset($args['showwebsite'])){ $show="2"; }
		else{  $show="1"; }
		$json = Config::CACHE."products_".str_replace(array("-"," "), "", implode("_",$_SESSION['URL'])).$show.$destinationJson.$titleJson.$tourtypeJson.$priceJson.$arrivalJson.$itemPerPage.$from.".json";

		if(file_exists($json)){
			$fetch = @file_get_contents($json); 
		}else{
			$showwebsiteSql = "";
			if(isset($args['showwebsite'])){
				$showwebsiteSql = ' `products`.`showwebsite`=:showwebsite AND ';
			}
			$select = "SELECT ".$select_products_dates_distinct ."
			(SELECT COUNT(`products`.`id`) FROM ".$table_products_dates."`products` WHERE ".$where_product_dates." `products`.`pid`=:pid AND `products`.`lang`=:lang AND" . $showwebsiteSql . $destinationSql . $titleSql . $tourtypeSql . $priceSql ."`products`.`status`!=:one) as counted, 
			`products`.`idx`, 
			`products`.`title`,
			`products`.`price`,
			`products`.`days_nights`,
			`products`.`short_description`, 
			`products`.`description`,
			`products`.`tourist_points`,
			`products`.`showwebsite`,
			(SELECT `photos`.`path` FROM `photos` WHERE `photos`.`parent`=`products`.`idx` AND `photos`.`type`='products' AND `photos`.`lang`=`products`.`lang` AND `photos`.`status`!=:one ORDER BY `photos`.`id` ASC LIMIT 1) AS photo
			FROM 
			".$table_products_dates."`products` 
			WHERE ".$where_product_dates."
			`products`.`pid`=:pid AND 
			`products`.`lang`=:lang AND " . $showwebsiteSql . $destinationSql . $titleSql . $tourtypeSql . $priceSql ."
			`products`.`status`!=:one 
			ORDER BY `products`.`id` DESC LIMIT ".$from.",".$itemPerPage;


			$prepare = $this->conn->prepare($select); 
			if(isset($args['showwebsite'])){
				$prepare->execute(array(
					":pid"=>3, 
					":lang"=>$args['lang'],
					":showwebsite"=>$args['showwebsite'],
					":one"=>1
				));
			}else{
				$prepare->execute(array(
					":pid"=>3, 
					":lang"=>$args['lang'],
					":one"=>1
				));
			}
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

	private function tourMaxMin(){
		$out["min"] = 0;
		$out["max"] = 0;

		$select = "SELECT MIN(CAST(`products`.`price` AS DECIMAL(8,0))) as min,  MAX(CAST(`products`.`price` AS DECIMAL(8,0))) as max FROM `products` WHERE `products`.`pid`=:pid AND `products`.`lang`=:lang AND `products`.`showwebsite`=2 AND `products`.`status`!=:one";
		$prepare = $this->conn->prepare($select); 
		$prepare->execute(array(
			":pid"=>3, 
			":lang"=>$_SESSION["LANG"],
			":one"=>1
		));
		if($prepare->rowCount()){
			$fetch = $prepare->fetch(PDO::FETCH_ASSOC);
			$out["min"] = $fetch["min"];
			$out["max"] = $fetch["max"];
		}

		return $out;
	}

	private function selectProductDates($args)
	{
		$fetch = "[]";

		$json = Config::CACHE."selectProductDates".str_replace(array("-"," "), "", implode("_",$_SESSION['URL'])).".json";

		if(file_exists($json)){
			$fetch = @file_get_contents($json); 
		}else{
			$select = "SELECT * FROM `products_dates` WHERE `products_dates`.`pid`=:pid ORDER BY `id` ASC";
			$prepare = $this->conn->prepare($select); 
			$prepare->execute(array(
				":pid"=>$args['idx']
			));
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

	private function selectTop($args){
		$fetch = "[]";

		$json = Config::CACHE."products_homepagetopshow".str_replace(array("-"," "), "", implode("_",$_SESSION['URL'])).".json";

		if(file_exists($json)){
			$fetch = @file_get_contents($json); 
		}else{
			$select = "SELECT 
			`products`.`idx`, 
			`products`.`title`, 
			`products`.`lang`, 
			(SELECT `photos`.`path` FROM `photos` WHERE `photos`.`parent`=`products`.`idx` AND `photos`.`type`='products' AND `photos`.`lang`=`products`.`lang` AND `photos`.`status`!=:one ORDER BY `photos`.`id` ASC LIMIT 1) AS photo
			FROM `products` 
			WHERE
			 `products`.`pid`=:pid AND 
			 `products`.`lang`=:lang AND 
			 `products`.`showwebsite`=:showwebsite AND 
			 `products`.`status`!=:one ORDER BY `products`.`views` DESC LIMIT 10";
			$prepare = $this->conn->prepare($select); 
			$prepare->execute(array(
				":pid"=>3, 
				":showwebsite"=>2, 
				":lang"=>$_SESSION["LANG"],
				":one"=>1
			));
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

	private function selectSpecial($args){
		$fetch = "[]";

		$limit = (isset($args["limit"])) ? $args["limit"] : 3;
		$json = Config::CACHE."products_homepageSpecialshow".str_replace(array("-"," "), "", implode("_",$_SESSION['URL'])).".json";

		if(file_exists($json)){
			$fetch = @file_get_contents($json); 
		}else{
			$select = "SELECT 
			`products`.`idx`, 
			`products`.`title`, 
			`products`.`price`, 
			`products`.`short_description`, 
			`products`.`lang`, 
			(SELECT `photos`.`path` FROM `photos` WHERE `photos`.`parent`=`products`.`idx` AND `photos`.`type`='products' AND `photos`.`lang`=`products`.`lang` AND `photos`.`status`!=:one ORDER BY `photos`.`id` ASC LIMIT 1) AS photo
			FROM `products` 
			WHERE
			 `products`.`pid`=:pid AND 
			 `products`.`lang`=:lang AND 
			 `products`.`showwebsite`=:showwebsite AND 
			 `products`.`special_offer`=:special_offer AND 
			 `products`.`status`!=:one ORDER BY `products`.`views` DESC LIMIT ".$limit;
			$prepare = $this->conn->prepare($select); 
			$prepare->execute(array(
				":pid"=>3, 
				":showwebsite"=>2, 
				":special_offer"=>2, 
				":lang"=>$_SESSION["LANG"],
				":one"=>1
			));
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

	private function add($args)
	{
		$current_lang = $args["lang"];
		$catalogId = (int)$args["catalogId"];
		$date = strtotime($args['date']);
		$title = $args["title"];
		$cover = $args["cover"];
		$destination = implode(",",$args["chooseDestination"]);
		$advanture_type = implode(",",$args["chooseAdvantureType"]);
		$checkinout = $args["checkinout"];
		
		$days_nights = $args["daysAndNights"];
		$tourist_points = $args["tourist_points"];
		$price = $args["price"];
		$short_description = $args["shortDescription"];
		$description = $args["longDescription"];
		$location = $args["locations"];
		$showwebsite = $args["showwebsite"];
		$special_offer = $args["chooseSpecial_offer"];
	

		$select = "SELECT `title` FROM `languages`";
		$prepare = $this->conn->prepare($select);
		$prepare->execute();
		$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);

		$max = "SELECT MAX(`idx`) as maxidx FROM `products`";
		$prepare2 = $this->conn->prepare($max);
		$prepare2->execute();
		$fetch2 = $prepare2->fetch(PDO::FETCH_ASSOC);
		$maxId = ($fetch2["maxidx"]) ? $fetch2["maxidx"] + 1 : 1;

		$services = array();
		foreach ($args["serialServices"] as $s) {
			$ex = explode("@@",$s);
			$services[] = $ex[0];
		}	


		foreach ($fetch as $val) {
			$insert = "INSERT INTO `products` SET 
			`idx`=:idx, 
			`pid`=:pid, 
			`date`=:datex, 
			`title`=:title, 
			`coverphoto`=:cover, 
			`destination`=:destination, 
			`advanture_type`=:advanture_type, 
			`checkinout`=:checkinout, 
			`days_nights`=:days_nights, 
			`tourist_points`=:tourist_points, 
			`price`=:price, 
			`short_description`=:short_description, 
			`description`=:description, 
			`location`=:location, 
			`special_offer`=:special_offer,
			`showwebsite`=:showwebsite,
			`services`=:services,
			`visibility`=:visibility,  
			`lang`=:lang";
			$prepare3 = $this->conn->prepare($insert);
			$prepare3->execute(array(
				":idx"=>$maxId, 
				":pid"=>$catalogId, 
				":datex"=>$date, 
				":title"=>$title, 
				":cover"=>$cover, 
				":destination"=>$destination, 
				":advanture_type"=>$advanture_type, 
				":checkinout"=>$checkinout, 
				":days_nights"=>$days_nights,
				":tourist_points"=>$tourist_points,
				":price"=>$price,
				":short_description"=>$short_description,
				":description"=>$description,
				":location"=>$location,
				":special_offer"=>$special_offer,
				":showwebsite"=>$showwebsite,
				":services"=>implode(",", $services),
				":visibility"=>0,
				":lang"=>$val['title']
			)); 

			if(count($args["serialPhotos"])){
				foreach ($args["serialPhotos"] as $pic) {
					if(!empty($pic)):
					$photo = 'INSERT INTO `photos` SET `parent`=:parent, `path`=:pathx, `type`=:type, `lang`=:lang, `status`=:zero';
					$photoPerpare = $this->conn->prepare($photo);
					$photoPerpare->execute(array(
						":parent"=>$maxId, 
						":pathx"=>$pic, 
						":type"=>"products", 
						":lang"=>$val['title'], 
						":zero"=>0
					));
					endif;
				}
			}
		}

		if(preg_match_all("/\d{2}\/\d{2}\/\d{4}-\d{2}\/\d{2}\/\d{4}/", $checkinout, $matches)){
			foreach ($matches[0] as $key => $value) {
				$ex = @explode("-", $value);

				if(isset($ex[0]) && isset($ex[1])){
					$exDate = explode("/", $ex[0]);
					$string1 = trim($exDate[1])."/".trim($exDate[0])."/".trim($exDate[2]);
					$strtotimeFrom = strtotime($string1);

					$exDate2 = explode("/", $ex[1]);
					$string2 = trim($exDate2[1])."/".trim($exDate2[0])."/".trim($exDate2[2]);
					$strtotimeTo = strtotime($string2);

					$products_dates = 'INSERT INTO `products_dates` SET `pid`=:pid, `checkin`=:checkin, `checkout`=:checkout';
					$products_datesPerpare = $this->conn->prepare($products_dates);
					$products_datesPerpare->execute(array(
						":pid"=>$maxId, 
						":checkin"=>$strtotimeFrom, 
						":checkout"=>$strtotimeTo
					));
				}
			}
		}


		if(count($args["serialServices"])){
			foreach ($args["serialServices"] as $subService) {
				$ex = explode("@@",$subService);

				if(empty($ex[0]) || empty($ex[1])){
					continue;
				}

				$id = (isset($ex[0])) ? $ex[0] : "";
				$title = (isset($ex[1])) ? $ex[1] : "";
				$price = (isset($ex[2])) ? $ex[2] : "";

				$subserviceSql = 'INSERT INTO `subservices` SET 
				`product_idx`=:product_idx, 
				`service_idx`=:service_idx, 
				`title`=:title, 
				`price`=:price, 
				`lang`=:lang';
				$subservicePerpare = $this->conn->prepare($subserviceSql);
				$subservicePerpare->execute(array(
					":product_idx"=>$maxId, 
					":service_idx"=>$id, 
					":title"=>$title, 
					":price"=>$price, 
					":lang"=>$current_lang
				));
			}
		}

		$this->clearCache();
	}

	private function remove($args)
	{
		$val = $args['val'];
		$update = "UPDATE `products` SET `status`=:one WHERE `idx`=:idx";
		$prepare = $this->conn->prepare($update); 
		$prepare->execute(array(
			":one"=>1,
			":idx"=>$val
		));
		if($prepare->rowCount()){
			$update2 = "UPDATE `photos` SET `status`=:one WHERE `parent`=:parent AND `type`=:type";
			$prepare2 = $this->conn->prepare($update2); 
			$prepare2->execute(array(
				":one"=>1,
				":parent"=>$val,
				":type"=>"products"
			));

			$delete = "DELETE FROM `subservices` WHERE `product_idx`=:product_idx";
			$prepare3 = $this->conn->prepare($delete); 
			$prepare3->execute(array(
				":product_idx"=>$val
			));
		}
		$this->clearCache();
		return 1;
	}

	private function selectById($args)
	{
		$fetch = "";
		if(isset($args['showwebsite'])){ $show="2"; }
		else{  $show="1"; }

		if(isset($args["increament"])){
			$increament = "UPDATE `products` SET `views` = `views` + 1 WHERE `idx`=:idx";
			$in_prepare = $this->conn->prepare($increament); 
			$in_prepare->execute(array(
				":idx"=>$args['idx']
			));
		}

		$json = Config::CACHE."products_byxid_".str_replace(array("-"," "), "", implode("_",$_SESSION['URL'])).$show.$args['idx'].".json";
		if(file_exists($json)){
			$fetch = @file_get_contents($json); 
		}else{	
			$showwebsiteSql = "";
			if(isset($args['showwebsite'])){
				$showwebsiteSql = ' `showwebsite`=:showwebsite AND ';
			}

			$select = "SELECT * FROM `products` WHERE `idx`=:idx AND `lang`=:lang AND ".$showwebsiteSql."`status`!=:one";
			$prepare = $this->conn->prepare($select); 
			if(isset($args['showwebsite'])){
				$prepare->execute(array(
					":idx"=>$args['idx'], 
					":lang"=>$args['lang'], 
					":showwebsite"=>$args['showwebsite'], 
					":one"=>1
				));
			}else{
				$prepare->execute(array(
					":idx"=>$args['idx'], 
					":lang"=>$args['lang'], 
					":one"=>1
				));
			}
			if($prepare->rowCount()){
				$db_fetch = $prepare->fetch(PDO::FETCH_ASSOC);
				$fh = @fopen($json, 'w') or die("Error opening output file");
				@fwrite($fh, json_encode($db_fetch,JSON_UNESCAPED_UNICODE));
				@fclose($fh);
				$fetch = @file_get_contents($json);
			}

		}
		return json_decode($fetch, true);
	}

	private function edit($args)
	{
		$current_lang = $args["lang"];
		$idx = (int)$args["idx"];
		$date = strtotime($args['date']);
		$title = $args["title"];
		$cover = $args["cover"];
		
		$destination = implode(",",$args["chooseDestination"]);
		$advanture_type = implode(",",$args["chooseAdvantureType"]);
		$checkinout = $args["checkinout"];
		
		$days_nights = $args["daysAndNights"];
		$tourist_points = $args["tourist_points"];
		$price = $args["price"];
		$short_description = $args["shortDescription"];
		$description = $args["longDescription"];
		$location = $args["locations"];
		
		$showwebsite = $args["showwebsite"];
		$special_offer = $args["chooseSpecial_offer"];

		$serviceDelete = new Database('service', array(
			'method'=>'remove', 
			'idx'=>$args['idx'], 
			'lang'=>$args['lang']
		));

		$services = array();
		foreach ($args["serialServices"] as $s) {
			$ex = explode("@@",$s);
			$services[] = $ex[0];
		}
		$services = implode(",", $services);
		
		$update = "UPDATE `products` SET 
		`title`=:title, 
		`days_nights`=:days_nights,  
		`short_description`=:short_description, 
		`description`=:description,
		`services`=:services
		WHERE `idx`=:idx AND `lang`=:lang";
		$prepare = $this->conn->prepare($update);
		$prepare->execute(array(
			":title"=>$title,
			":days_nights"=>$days_nights,   
			":short_description"=>$short_description,  
			":description"=>$description,  
			":services"=>$services,  
			":idx"=>$args['idx'],  
			":lang"=>$args['lang']   
		));	

		// update in all language
		$updateShow = "UPDATE `products` SET `date`=:datex, `checkinout`=:checkinout, `coverphoto`=:cover, `destination`=:destination, `tourist_points`=:tourist_points, `price`=:price, `advanture_type`=:advanture_type, `special_offer`=:special_offer, `showwebsite`=:showwebsite, `location`=:location WHERE `idx`=:idx";
		$prepareShow = $this->conn->prepare($updateShow);
		$prepareShow->execute(array(
			":datex"=>$date, 
			":cover"=>$cover, 
			":checkinout"=>$checkinout,
			":destination"=>$destination,
			":tourist_points"=>$tourist_points,
			":advanture_type"=>$advanture_type,
			":price"=>$price, 
			":showwebsite"=>$showwebsite, 
			":location"=>$location, 
			":special_offer"=>$special_offer, 
			":idx"=>$args['idx']
		));


		$photos = new Database('photos', array(
			'method'=>'deleteByParent', 
			'idx'=>$args['idx'], 
			'type'=>"products",
			'lang'=>$args['lang'] 
		));

		if(count($args["serialPhotos"])){

			foreach($args["serialPhotos"] as $pic) {
				if(!empty($pic)):
				$photo = 'INSERT INTO `photos` SET `parent`=:parent, `path`=:pathx, `type`=:type, `lang`=:lang, `status`=:zero';
				$photoPerpare = $this->conn->prepare($photo);
				$photoPerpare->execute(array(
					":parent"=>$args['idx'], 
					":pathx"=>$pic, 
					":type"=>"products", 
					":lang"=>$args['lang'], 
					":zero"=>0
				));
				endif;
			}
		}



		if(count($args["serialServices"])){

			foreach ($args["serialServices"] as $subService) {
				$ex = explode("@@", $subService);

				if(empty($ex[0]) || empty($ex[1])){
					continue;
				}

				$id = (isset($ex[0])) ? $ex[0] : "";
				$title = (isset($ex[1])) ? $ex[1] : "";
				$price = (isset($ex[2])) ? $ex[2] : "";

				$subserviceSql = 'INSERT INTO `subservices` SET 
				`product_idx`=:product_idx, 
				`service_idx`=:service_idx, 
				`title`=:title, 
				`price`=:price, 
				`lang`=:lang';
				$subservicePerpare = $this->conn->prepare($subserviceSql);
				$subservicePerpare->execute(array(
					":product_idx"=>$args['idx'], 
					":service_idx"=>$id, 
					":title"=>$title, 
					":price"=>$price, 
					":lang"=>$args['lang']
				));
			}
		}

		$deleteOld = "DELETE FROM `products_dates` WHERE `pid`=".(int)$args['idx'];
		$this->conn->query($deleteOld);

		if(preg_match_all("/\d{2}\/\d{2}\/\d{4}-\d{2}\/\d{2}\/\d{4}/", $checkinout, $matches)){

			foreach ($matches[0] as $key => $value) {
				$ex = @explode("-", $value);

				if(isset($ex[0]) && isset($ex[1])){
					$exDate = explode("/", $ex[0]);
					$string1 = trim($exDate[1])."/".trim($exDate[0])."/".trim($exDate[2]);
					$strtotimeFrom = strtotime($string1);

					$exDate2 = explode("/", $ex[1]);
					$string2 = trim($exDate2[1])."/".trim($exDate2[0])."/".trim($exDate2[2]);
					$strtotimeTo = strtotime($string2);

					$products_dates = 'INSERT INTO `products_dates` SET `pid`=:pid, `checkin`=:checkin, `checkout`=:checkout';
					$products_datesPerpare = $this->conn->prepare($products_dates);
					$products_datesPerpare->execute(array(
						":pid"=>$args['idx'], 
						":checkin"=>$strtotimeFrom, 
						":checkout"=>$strtotimeTo
					));
				}
			}
		}

		$this->clearCache();
	}

	public function countRegions($args){
		$count = 0;
		$sql = "SELECT DISTINCT `region` FROM `products` WHERE `lang`='ge' AND `status`!=1"; 
		$prepare = $this->conn->prepare($sql); 
		$prepare->execute();
		if($prepare->rowCount()){
			$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);
			$count = count($fetch);
		}
		return $count;
	}

	private function countByDestination($args)
	{
		$count = 0;
		$select = "SELECT COUNT(`id`) as counted FROM `products` WHERE FIND_IN_SET(:numberx, `destination`) AND `showwebsite`=2 AND `lang`=:lang AND `status`!=1";
		$prepare = $this->conn->prepare($select); 
		$prepare->execute(array(
			":numberx"=>$args["numberx"],
			":lang"=>$args["lang"],
		));
		if($prepare->rowCount()){
			$fetch = $prepare->fetch(PDO::FETCH_ASSOC);
			$count = $fetch["counted"];
		}
		return $count;
	}

	private function clearCache()
	{
		$mask = Config::CACHE.'products_*.*';
		array_map('unlink', glob($mask));

		$mask = Config::CACHE.'selectProductDates*.*';
		array_map('unlink', glob($mask));

		$mask2 = Config::CACHE.'subservicves_*.*';
		array_map('unlink', glob($mask2));

		$mask3 = Config::CACHE.'module_*.*';
		array_map('unlink', glob($mask3));	
	}
}