<?php 
require_once("app/functions/l.php"); 
require_once("app/functions/strip_output.php"); 
require_once("app/functions/strings.php"); 
$l = new functions\l(); 
echo $data['headerModule']; 
echo $data['headertop']; 
$photo = (isset($data["pageData"]["photo"])) ? Config::WEBSITE_.$data["pageData"]["photo"] : "";
?>

<section class="breadcrups" style="background-image: url('<?=$photo?>')">
	<section class="content">
		<h3><?=(isset($data["pageData"]["title"])) ? $data["pageData"]["title"] : ""?></h3>
		<ul>
			<li><a href="<?=Config::WEBSITE.$_SESSION["LANG"]."/".Config::MAIN_CLASS?>"><?=$l->translate("home")?></a></li>
			<li><a href=""><?=(isset($data["pageData"]["title"])) ? $data["pageData"]["title"] : ""?></a></li>
		</ul>
	</section>
</section>

<main>
	<section class="center"> 
		<section class="title"><?=strip_tags($data["pageData"]["title"])?></section>
		<section class="row">
			<section class="col-lg-12 faq">				
				<!-- Panel start -->
				<section class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">				  
				 <?php 
				 	$_SESSION["payment_token"] = functions\string::random(25);
				  ?>
				  <form action="/<?=$_SESSION["LANG"]?>/paypal" id="payoutform" method="post">
				  	<input type="hidden" name="token" value="<?=$_SESSION["payment_token"]?>" />
				  	<input type="hidden" name="tour_id" value="<?=(int)strip_tags(functions\request::index("POST", "bookid"))?>" />

				  	<input type="hidden" name="checkinCheckout" value="<?=htmlentities($_POST["arriveDepartureSelectorValue"])?>" />
				  	
				  	<?php 
				  // print_r(functions\request::index("POST", "booksubservice"));
				  	if(isset($_POST["booksubservice"]) && is_array($_POST["booksubservice"])): ?>
				  	<input type="hidden" name="tour_services" value="<?=htmlentities(implode(",", $_POST["booksubservice"]))?>" />
				  	<?php endif; ?>
				  	<input type="hidden" name="adults" value="<?=(int)functions\request::index("POST", "bookadult")?>" />
				  	<input type="hidden" name="children" value="<?=(int)functions\request::index("POST", "bookchild")?>" />


				  	
				  	<?php if(isset($_POST["bookchilds"]) && is_array($_POST["bookchilds"])): ?>
				  	<input type="hidden" name="childrens_ages" value="<?=htmlentities(implode(",", $_POST["bookchilds"]))?>" />
				  	<?php endif; ?>

				  	<input type="hidden" name="paymentMethod" id="paymentMethod" value="visa" />

				  </form>

				  <section style="border:solid 1px #f2f2f2; padding: 20px 5px;">
				  	<section class="col-lg-4">
				  		<img src="<?=$data["photos"][0]["path"]?>" alt="" style="width: 100%; margin-bottom: 10px;" />
				  	</section>
				  	<section class="col-lg-8">
				  		<h2 style="margin:0"><?=$data["selectbooked"]["title"]?></h2>
				  		<p style="padding:10px 0">
				  			<i class="fa fa-clock-o" aria-hidden="true"></i> 
				  			<?=$_POST["arriveDepartureSelectorValue"]?>
				  		</p>
				  		<p><b><?=$l->translate("mainprice")?>:</b> <?=strip_tags(ceil($data["selectbooked"]["price"]))?> &euro;</p>
				  		
				  		<?php 
				  		$servPrices = 0;
				  		if(isset($_POST["booksubservice"])) { 
				  		?>
					  		<section style="margin-bottom:10px; padding:10px; border: solid 1px #f2f2f2">
					  		<p><b style="background-color: #ffcc00;"><?=$l->translate("services")?></b></p>
							<?php 
					  		if(isset($_POST["booksubservice"]) && !empty($_POST["booksubservice"]))
					  		{
					  			// echo "<pre>";
					  			// print_r($_POST["booksubservice"]);
					  			// echo "</pre>";
					  			foreach ($_POST["booksubservice"] as $value):
					  				$ex = explode(":", $value);
							  		$db_service = new Database("service", array(
										"method"=>"subservicvesbyid", 
										"id"=>(int)$ex[2],
										"lang"=>$_SESSION["LANG"]
									));
									if($fetch = $db_service->getter()){ 
										echo sprintf("<p style='margin:0'><b>%s: </b>%s &euro;</p>", $fetch[0]["title"], ceil($fetch[0]["price"]));
										$servPrices += (int)$fetch[0]["price"];
									}
						  		endforeach;
					  		}
					  		echo "</section>";
				  		}
				  		// if price is not dynamic
				  		if($data["selectbooked"]["tourist_points"]!="dynamic"){
				  			$bookadultForMulty = 1;
				  		}else{
				  			$bookadultForMulty = $_POST["bookadult"];
				  		}
				  		$totalPriceAdult = ((int)$data["selectbooked"]["price"]*(int)$bookadultForMulty)+($servPrices*(int)$bookadultForMulty);
					  	$totalPriceChild = (((int)$data["selectbooked"]["price"]/2)*(int)$_POST["bookchild"])+(($servPrices/2)*(int)$_POST["bookchild"]);
				  		?>
				  		
				  		<p><b><?=$l->translate("adults")?>: </b><?=(int)strip_tags($_POST["bookadult"])?></p>
				  		<p><b><?=$l->translate("children")?>: </b><?=(int)strip_tags($_POST["bookchild"])?></p>
				  		<p><b><?=$l->translate("adultprice")?>: </b><?php 
				  		echo ceil($totalPriceAdult)." &euro;";
				  		?></p>
				  		<p><b><?=$l->translate("childprice")?>: </b><?php 
				  		echo ceil($totalPriceChild)." &euro;";
				  		?></p>

				  		<p style="font-size: 28px; font-family: "Roboto";"><b><?=$l->translate("totalprice")?>: </b><?php 
				  		echo ceil($totalPriceAdult+$totalPriceChild)." &euro;";
				  		?></p>
				  		<?php 
				  		if(!isset($_SESSION[Config::SESSION_PREFIX."web_username"])){
				  			$class = "bookNowButtonnoLogin";
				  			$buttonText = $l->translate("signin");
				  		}else{
				  			$class = "bookNowButton";
				  			$buttonText = $l->translate("paynow");
				  		}
				  		?>
				  		<form action="?" method="post" class="paymentForm">
				  			<label><?=$l->translate("choosepayment")?>: </label>
				  			<select id="choosepayment" class="selectpicker">
				  				<option value="paypal">Paypal</option>
				  				<option value="visa" disabled="disabled">Visa &amp; Mastercard</option>
				  			</select>
				  		</form>

				  		<button class="<?=$class?>"><?=$buttonText?></button>

				  	</section>
				  	<section style="clear: both"></section>
				  </section>

				</section>
				<!-- Panel End -->
			</section>
		</section>
	</section>
</main>

<section class="clearer"></section>

<?=$data['footer']?>