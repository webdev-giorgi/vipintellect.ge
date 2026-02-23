<?php 
require_once("app/functions/l.php"); 
require_once("app/functions/strip_output.php"); 
require_once("app/functions/strings.php"); 
require_once 'app/functions/request.php';
require_once 'app/functions/pagination.php';

$l = new functions\l(); 
$pagination = new functions\pagination(); 
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
		<section class="title"><?=(isset($data["pageData"]["title"])) ? $data["pageData"]["title"] : ""?></section>
		<section class="col-lg-3 leftside">
			<section class="searchBox">
				<h3><?=$l->translate("searchtours")?></h3>
				<h4><?=$l->translate("findyourdreamtourtoday")?>!</h4>
				<form action="javascript:void(0)" method="get">
					<div class="input-group">
 						<input type="text" class="form-control catalogpagesearch_title" name="title" placeholder="<?=$l->translate("typetourtitle")?>" value="<?=(functions\request::index("GET", "title") ? htmlentities(functions\request::index("GET", "title")) : "")?>" />
					</div>

					<input type="hidden" class="pop-destination catalogpagesearch_destination" name="destination" value="" />
					<select class="selectpicker pop-dest" data-live-search="true">
						<option value=""><?=$l->translate("destination")?></option>
						<?=$data["destinationsOptions"]?>
					</select>

					<input type="hidden" class="pop-tourtypes catalogpagesearch_tourtypes" name="tourtype" value="" />
					<select class="selectpicker pop-tour" data-live-search="true">
						<option value=""><?=$l->translate("advanturetype")?></option>
						<?=$data["tourtypesOptions"]?>
					</select>

					<section class="dateBox">
						<input type="text" class="form-control date arrival catalogpagesearch_arrival" name="arrival" value="" placeholder="<?=$l->translate("arrival")?>" readonly="readonly" />
					</section>

					<section class="dateBox">
						<input type="text" class="form-control date departure catalogpagesearch_departure" name="departure" value="" placeholder="<?=$l->translate("departure")?>" readonly="readonly" />
					</section>

					<?php 
					if(!isset($data["tourMaxMin"]["min"]) || !isset($data["tourMaxMin"]["max"])){
						$price = "0,0";
					}else{
						$price = (functions\request::index("GET", "price")) ? functions\request::index("GET", "price") : $data["tourMaxMin"]["min"].",".$data["tourMaxMin"]["max"];
					}
					$price = explode(",", $price);

					?>

					<section class="range-slider">
						<label><?=$l->translate("pricerange")?></label><section class="clearer"></section>
						<input id="range" class="catalogpagesearch_range" type="text" name="price" value="<?=(int)$price[0]?>" />
						<section class="maxValues">
							<label id="minValue"><?=(isset($price[0])) ? (int)$price[0] : 0?></label>
							<label id="maxValue"><?=(isset($price[1])) ? (int)$price[1] : 0?></label>
						</section>
					</section>
					<section class="clearer"></section>

					<button class="search catalogpagesearch"><?=$l->translate("search")?></button>

					<script type="text/javascript">
					$('.selectpicker').selectpicker();
					$('.pop-dest').on('changed.bs.select', function (e) {
		            	$(".pop-destination").val(e.target.value);
		            });

		            $('.pop-tour').on('changed.bs.select', function (e) {
		            	$(".pop-tourtypes").val(e.target.value);
		            });

					$(".date").datepicker({
						format: 'dd/mm/yyyy', 
						autoclose: true
					});

					/* Range Slider START */
					var range = document.getElementById('range');
					<?php 
					if(functions\request::index("GET", "price")){
						$price = explode(",", functions\request::index("GET", "price"));
						// echo functions\request::index("GET", "price");
						$from = (isset($price[0])) ? (int)$price[0] : 0;
						$to = (isset($price[1])) ? (int)$price[1] : 0;
					?>
					$("#range").slider({ min: <?=(isset($data["tourMaxMin"]["min"])) ? $data["tourMaxMin"]["min"] : 0?>, max: <?=(isset($data["tourMaxMin"]["max"])) ? $data["tourMaxMin"]["max"] : 0?>, value: [<?=$from?>, <?=$to?>], focus: true });
					<?php
					}else{
					?>
					$("#range").slider({ min: <?=(isset($data["tourMaxMin"]["min"])) ? $data["tourMaxMin"]["min"] : 0?>, max: <?=(isset($data["tourMaxMin"]["max"])) ? $data["tourMaxMin"]["max"] : 0?>, value: [<?=(isset($data["tourMaxMin"]["min"])) ? $data["tourMaxMin"]["min"] : 0?>, <?=(isset($data["tourMaxMin"]["max"])) ? $data["tourMaxMin"]["max"] : 0?>], focus: true });
					<?php
					}
					?>					
					$(document).on("change", "#range", function(e){
						var vals = $("#range").get();
						var v = vals[0].value;
						var exp = v.split(",");
						var min = exp[0];
						var max = exp[1];
						$("#minValue").text(min);
						$("#maxValue").text(max);
					});
					/* Range Slider END */

					<?php 
					if(functions\request::index("GET", "destination")):
					?>
						$('.pop-destination').val(<?=(int)functions\request::index("GET", "destination")?>);
						$('.pop-dest').val(<?=(int)functions\request::index("GET", "destination")?>);
						$('.pop-dest').selectpicker('refresh');
					<?php
					endif;
					?>

					<?php 
					if(functions\request::index("GET", "tourtype")):
					?>
						$('.pop-tourtypes').val(<?=(int)functions\request::index("GET", "tourtype")?>);
						$('.pop-tour').val(<?=(int)functions\request::index("GET", "tourtype")?>);
						$('.pop-tour').selectpicker('refresh');
					<?php
					endif;
					?>

					<?php 
					if(functions\request::index("GET", "arrival")):
					?>
						$(".arrival").datepicker("update", "<?=htmlentities(functions\request::index("GET", "arrival"))?>");
					<?php
					endif;
					?>

					<?php 
					if(functions\request::index("GET", "departure")):
					?>
						$(".departure").datepicker("update", "<?=htmlentities(functions\request::index("GET", "departure"))?>");
					<?php
					endif;
					?>

					

					</script>
				</form>
			</section>
		</section>

		<section class="col-lg-9 rightside">
			<section class="row">
				<?=$data["tourlist"]?>	

				<section class="clearer"></section>
				<?=$pagination->web($data["countTours"], $data["itemPerPage"])?>
				

			</section>
		</section>
	</section>
</main>

<section class="clearer"></section>

<?=$data['footer']?>