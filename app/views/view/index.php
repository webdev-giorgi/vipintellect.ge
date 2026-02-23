<?php 
require_once("app/functions/l.php"); 
require_once("app/functions/strip_output.php"); 
require_once("app/functions/strings.php"); 
require_once 'app/functions/request.php';
require_once 'app/functions/selectedDestinations.php';

$l = new functions\l(); 
echo $data['headerModule']; 
echo $data['headertop']; 
$photo = (!empty($data["productGetter"]["coverphoto"])) ? $data["productGetter"]["coverphoto"] : "/public/img/cover.png";

 
// $arrival = strtotime($data["productGetter"]["arrival"]); 
// $arrival = date("d/m", $arrival);

// $departure = strtotime($data["productGetter"]["departure"]); 
// $departure = date("d/m", $departure);

?>

<section class="breadcrups" style="background-image: url('<?=$photo?>')">
	<section class="content">
		<h3><?=$data["productGetter"]["title"]?></h3>
		<ul>
			<li><a href="<?=Config::WEBSITE.$_SESSION["LANG"]."/".Config::MAIN_CLASS?>"><?=$l->translate("home")?></a></li>
			<li><a href="<?=Config::WEBSITE.$_SESSION["LANG"]?>/tours"><?=$l->translate("tours")?></a></li>
			<li><a href=""><?=$data["productGetter"]["title"]?></a></li>
		</ul>
	</section>
</section>


<main>
	<section class="center tour-page">
		<section class="row">
			<section class="col-lg-8 left-details">
				<ul class="tour-ul">
					<li>
						<i class="fa fa-calendar" aria-hidden="true"></i>
						<?php 
						$arrEx = explode(",", $data["productGetter"]["checkinout"]);
						?>
						<span id="tourCheckinCheckout" title="<?=htmlentities(implode(", ", $arrEx))?>"><?php 
						if(count($arrEx)>1){
							echo $arrEx[0]."..."; 
						}else{
							echo $arrEx[0];
						}
						?></span>
					</li>
					<li>
						<i class="fa fa-clock-o" aria-hidden="true"></i>
						<span><?=$data["productGetter"]["days_nights"]?></span>
					</li>
				</ul>
				<section class="clearer"></section>

				<!-- thumb slider Start -->
				<section class="thumb-slider">
							<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="false">
							  	

								<div class="carousel-inner" role="listbox">
									
									<?php 
									$x = 1;
							  		foreach ($data["photos"] as $photo):
							  			$active = ($x==1) ? " active" : "";
								  		$bg = sprintf(
											"%s%s/image/loadimage?f=%s%s&w=720&h=380",
											Config::WEBSITE,
											$_SESSION["LANG"],
											Config::WEBSITE_,
											$photo["path"]
										);
							  		?>
										<div class="item<?=$active?>" style="background-image: url('<?=$bg?>')">
											<img src="<?=$bg?>" alt="" />
										</div>
									<?php 
										$x=2;
									endforeach;
									?>

								</div>

								<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
									<span class="fa fa-angle-left" aria-hidden="true"></span>
								</a>
								<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
									<span class="fa fa-angle-right" aria-hidden="true"></span>
								</a>
							</div>

							<section class="clearer"></section>

							<section class="sub-slider row">
							    <?php 
							    $i=0;
						  		foreach ($data["photos"] as $photo):
						  			$bg = sprintf(
										"%s%s/image/loadimage?f=%s%s&w=160&h=110",
										Config::WEBSITE,
										$_SESSION["LANG"],
										Config::WEBSITE_,
										$photo["path"]
									);
						  		?>
							    <section class="col-md-3 thumb" style="margin-top: 30px;">
							    	<section class="image<?=($i==0)?" active":""?>" style="background-image: url('<?=$bg?>'); min-height: 110px; max-height: 110px;"></section>
							    </section>
							    <?php 
							    $i=1;
								endforeach;
								?>
								 <section class="clearer"></section>
							</section>
							<section class="clearer"></section>

							<script type="text/javascript">
							$("main .tour-page .left-details .thumb-slider .sub-slider .thumb .image").click(function(){
								var n = $(this).index("main .tour-page .left-details .thumb-slider .sub-slider .thumb .image");
								$('#carousel-example-generic').carousel(n);
								$("main .tour-page .left-details .thumb-slider .sub-slider .thumb .image").removeClass("active");
								$(this).addClass("active");
							});

							$("#carousel-example-generic").carousel({
								interval: 5000
							});
							
							$('#carousel-example-generic').on('slide.bs.carousel', function (e) {
								var $active = $(this).find('.item.active'), children = $active.parent().children(), nextPos = children.index(e.relatedTarget);

								$("main .tour-page .left-details .thumb-slider .sub-slider .thumb .image").removeClass("active");
								$("main .tour-page .left-details .thumb-slider .sub-slider .thumb .image").eq(nextPos).addClass("active");
							}); 
							</script>

						</section>
				<!-- thumb slider End -->
				<section class="clearer"></section>
				<section class="tour-text">
					<section class="data-info-box">
					<p class="data-info">
						<i class="fa fa-map-marker" aria-hidden="true"></i> 
						<span>
							<?php 
							$selectedDestinations = new functions\selectedDestinations();
							echo $selectedDestinations->index($data["destinations"], $data["productGetter"]["destination"]);
							?>
						</span>
					</p>
					<p class="data-info">
						<i class="fa fa-info" aria-hidden="true"></i> 
						<span>
							<?php 
							echo $selectedDestinations->index($data["tour_types"], $data["productGetter"]["advanture_type"]);
							?>
						</span>
					</p>
					<!-- <p class="data-info">
						<i class="fa fa-eye" aria-hidden="true"></i>
						<span><?=(int)$data["productGetter"]["views"]?></span>
					</p> -->
					</section>

					<h3><?=$data["productGetter"]["title"]?></h3>
					<?=strip_tags($data["productGetter"]["description"], "<p><strong><ul><li><ol><br><a><span>")?>
				</section>

				<section class="clearer"></section>
				<section class="the-title"><?=$l->translate("services")?></section>
				<section class="services">
					<section class="row">

						<?php 
						foreach($data["services"] as $service) :
						$active = "";
						foreach ($data["sub_services"] as $sub) {
							if($sub["service_idx"]==$service["idx"]){
								$active = " active";
								break;
							}
						}
						?>
						<section class="col-lg-2 col-md-4 column" data-toggle="tooltip" title="<?=htmlentities($service["title"])?>">
							<section class="item<?=$active?>">
								<i class="<?php echo htmlentities(strip_tags($service["description"])); ?>" aria-hidden="true"></i>
								<span><?php echo functions\string::cutstatic(strip_tags($service["title"]), 12); ?></span>
							</section>
						</section>
						<?php endforeach; ?>

					</section>
				</section>

				<section class="clearer"></section>
				<section class="the-title map-title"><?=$l->translate("locations")?></section>
				<section class="map" id="map"></section>
			</section>
			<section class="col-md-4 right-details">
				<section class="price-box">
					<section class="label"><?=$l->translate("price")?></section>
					<section class="price total-price">
						<span><?=$data["productGetter"]["price"]?></span> &euro;
					</section>
					<form action="/<?=$_SESSION["LANG"]?>/booking" method="post" id="bookForm">
						<?php $_SESSION["token"] = functions\string::random(12); ?>
						<input type="hidden" id="booktoken" name="booktoken" value="<?=$_SESSION["token"]?>">
						<input type="hidden" id="bookid" name="bookid" value="<?=$data["productGetter"]["idx"]?>">
						<input type="hidden" id="bookprice" name="bookprice" readonly="readonly" value="<?=$data["productGetter"]["price"]?>">

						<input type="hidden" class="arriveDepartureSelectorValue" id="arriveDepartureSelectorValue" name="arriveDepartureSelectorValue" value="<?=date("d/m/Y", $data["productdates"][0]["checkin"])?> - <?=date("d/m/Y", $data["productdates"][0]["checkout"])?>" />						

						<?php if($data["productGetter"]["tourist_points"]=="dynamic"){  ?>
						<input type="hidden" id="bookadult" name="bookadult" value="1" />
						<?php }else{ ?>
						<input type="hidden" id="bookadult" name="bookadult" value="<?=(int)$data["productGetter"]["tourist_points"]?>" />
						<?php } ?>
						<input type="hidden" id="bookchild" name="bookchild" value="0" />

						<?php 
						$explodeSelectedServics = explode(",", $data["productGetter"]["services"]);
					
						foreach($data["services"] as $service) :
							if(!in_array($service["idx"], $explodeSelectedServics)){
								continue;
							}
						
							foreach ($data["sub_services"] as $sub) : 
								if($sub["service_idx"]!=$service["idx"]){
									continue;
								}
								if((float)$sub["price"]<=0):
								?>
									<input type="hidden" name="booksubservice[]" class="subserviceinput serv<?=$sub["service_idx"]?> subserv<?=$sub["id"]?>" value="<?=$sub["service_idx"]?>:0:<?=$sub["id"]?>" />
								<?php
								endif;
							endforeach;

						endforeach;
						
						?>
					</form>
				</section>
				<section class="booking-box">
					<?php if(!isset($_SESSION[Config::SESSION_PREFIX."web_username"])) : ?>
					<section class="alert alert-warning" role="alert">
					  <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
					  <a href="javascript:void(0)" class="signIn" data-boxtitle="Sign In">
					  	<?=$l->translate("signin")?></a> / 
					  <a href="javascript:void(0)" class="createAccount" data-boxtitle="Create New Account"><?=$l->translate("createnewaccount")?></a>
					</section>
					<?php endif;?>

					<section class="form-group" style="margin-bottom: 10px;">
						<label class="col-form-label"><?=$l->translate("arrivaldeparture")?></label>
					    <select class="selectpicker arriveDepartureSelector">
					    	<?php 
					    	foreach ($data["productdates"] as $value) {
					    		echo sprintf(
					    			"<option value=\"%s - %s\">%s - %s</option>",
					    			date("d/m/Y", $value["checkin"]),
					    			date("d/m/Y", $value["checkout"]),
					    			date("d/m/Y", $value["checkin"]),
					    			date("d/m/Y", $value["checkout"])
					    		);
					    	}
					    	?>
					    </select>
					    <script type="text/javascript">
					    	$('.arriveDepartureSelector').on('changed.bs.select', function (e) {
					    		$("#tourCheckinCheckout").html(e.target.value);
				            	$("#arriveDepartureSelectorValue").val(e.target.value);
				            });
					    </script>
				    </section>

					<?php 
					// echo print_r($data["productGetter"]);
					$explodeSelectedServics = explode(",", $data["productGetter"]["services"]);
					$sectionID = 0;
					$secID = array();
					foreach($data["services"] as $service) :
						if(!in_array($service["idx"], $explodeSelectedServics)){
							continue;
						}
					?>

						<section class="service-checkbox" id="section<?=$sectionID?>">
							<section class="main-checkbox">
								<i class="<?=strip_tags($service["description"])?>" aria-hidden="true"></i>
								<span><?=$service["title"]?></span>
								<section class="clearer"></section>
							</section>
							<section class="sub-checkbox" id="sub<?=$service["idx"]?>">
								
								<?php 
								foreach ($data["sub_services"] as $sub) : 
									if($sub["service_idx"]!=$service["idx"]){
										continue;
									}

									if((float)$sub["price"]<=0){
										$secID[] = "section".$sectionID;
										$off = "on";
									}else{
										$off = "off";
									}
									// $off = ((float)$sub["price"]<=0) ? "on" : "off";
								?>
									<section class="item">
										<span><?=$sub["title"]?> - <em class="price"><?=(float)$sub["price"]?> &euro;</em></span>
										<i class="fa fa-toggle-<?=$off?> checkbox-sub-input" data-subservid="<?=$sub["id"]?>" data-classname="<?=htmlentities($service["classname"])?>" data-turn="<?=$off?>" data-serviceid="<?=(int)$service["idx"]?>" data-sub="sub<?=(int)$service["idx"]?>" data-price="<?=(float)$sub["price"]?>" aria-hidden="true"></i>
										<section class="clearer"></section>
									</section>
								<?php endforeach; ?>

							</section>
						</section>

					<?php 
					$sectionID++;
					endforeach; 
					?>
					<script type="text/javascript">
					hideIt(<?=json_encode($secID)?>);
					</script>

					<?php 
					if($data["productGetter"]["tourist_points"]=="dynamic"){ 
					?>
						<section class="form-group">
						  <label class="col-2 col-form-label"><?=$l->translate("adults")?></label>
						  <input class="form-control" type="number" id="adults" autocomplete="off" value="1" min="1" />
						</section>

						<section class="form-group">
						 	<label class="col-2 col-form-label"><?=$l->translate("children")?></label>
						 	<input class="form-control" type="number" id="children" autocomplete="off" value="0" min="0" data-childageText="<?=$l->translate("childage")?>" />					  
						</section>
					<?php }else{ ?>
						<section class="form-group">
						  <label class="col-2 col-form-label"><?=$l->translate("adults")?></label>
						  <input class="form-control" type="number" autocomplete="off" value="<?=(int)$data["productGetter"]["tourist_points"]?>" disabled="disabled" />
						</section>
						<input class="form-control" type="hidden" id="children" autocomplete="off" value="0" min="0" />
					<?php }?>

					<section class="childernsAges">
					</section>

					<button class="bookNowButton"><?=$l->translate("booknow")?></button>

					<button class="addFavourite buttonWithIcon" data-boxtitle="<?=$l->translate("message")?>" data-tourid="<?=$data["productGetter"]["idx"]?>">
						<i class="fa <?=($data["fav"]) ? "fa-heart" : "fa-heart-o"?>" aria-hidden="true"></i> <span><?=($data["fav"]) ? $l->translate("removefavourite") : $l->translate("favourite")?></span>
					</button>
					
					
					<button class="share buttonWithIcon" data-url="http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?=htmlentities($data["productGetter"]["title"])?>&amp;p[url]=http://<?=$_SERVER['SERVER_NAME'].urlencode($_SERVER['REQUEST_URI'])?>&amp;p[summary]=<?=htmlentities($data["productGetter"]["title"])?>">
						<i class="fa fa-facebook-official" aria-hidden="true"></i> <?=$l->translate("facebook")?>
					</button>
					
					<button class="share buttonWithIcon" data-url="https://twitter.com/share?url=http://<?=$_SERVER['SERVER_NAME'].urlencode($_SERVER['REQUEST_URI'])?>&amp;via=<?=Config::NAME?>&amp;text=<?=htmlentities($data["productGetter"]["title"])?>">
						<i class="fa fa-twitter-square" aria-hidden="true"></i> <?=$l->translate("twitter")?>
					</button>

					<button class="share buttonWithIcon" data-url="https://plus.google.com/share?url=http://<?=$_SERVER['SERVER_NAME'].urlencode($_SERVER['REQUEST_URI'])?>">
						<i class="fa fa-google-plus" aria-hidden="true"></i> <?=$l->translate("googleplus")?>
					</button>

					<button class="share buttonWithIcon" data-url="https://www.linkedin.com/shareArticle?url=http://<?=$_SERVER['SERVER_NAME'].urlencode($_SERVER['REQUEST_URI'])?>&amp;title=<?=htmlentities($data["productGetter"]["title"])?>">
						<i class="fa fa-linkedin-square" aria-hidden="true"></i> <?=$l->translate("linkedin")?>
					</button>

					

				</section>
			</section>
		</section>
	</section>
</main>



<section class="clearer"></section>


<script type="text/javascript">
var map;
function initMap() {
	var mapOptions = {
        zoom: 10,
		styles: [{"featureType":"administrative","elementType":"all","stylers":[{"color":"#20314a"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"visibility":"off"},{"color":"#cc3c3c"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"visibility":"off"},{"color":"#c22f2f"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#96d42c"}]},{"featureType":"poi","elementType":"all","stylers":[{"color":"#20314a"},{"visibility":"off"}]},{"featureType":"poi.attraction","elementType":"all","stylers":[{"color":"#20314a"},{"visibility":"on"}]},{"featureType":"poi.attraction","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"color":"#d40808"},{"visibility":"off"}]},{"featureType":"poi.government","elementType":"all","stylers":[{"visibility":"off"},{"color":"#cf2727"}]},{"featureType":"poi.medical","elementType":"all","stylers":[{"visibility":"off"},{"color":"#f7ce37"}]},{"featureType":"poi.medical","elementType":"labels.text","stylers":[{"visibility":"on"},{"color":"#ff0000"}]},{"featureType":"poi.medical","elementType":"labels.text.fill","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"all","stylers":[{"visibility":"on"},{"color":"#f7ce37"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"poi.park","elementType":"labels.text","stylers":[{"visibility":"on"},{"color":"#20314a"}]},{"featureType":"poi.park","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"labels.icon","stylers":[{"visibility":"off"},{"color":"#be1515"}]},{"featureType":"poi.school","elementType":"all","stylers":[{"visibility":"on"},{"color":"#f7ce37"}]},{"featureType":"poi.school","elementType":"labels.text","stylers":[{"visibility":"on"},{"color":"#20314a"}]},{"featureType":"poi.school","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"poi.sports_complex","elementType":"all","stylers":[{"visibility":"on"},{"color":"#f7ce37"}]},{"featureType":"poi.sports_complex","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"poi.sports_complex","elementType":"labels.text","stylers":[{"visibility":"on"},{"color":"#20314a"}]},{"featureType":"poi.sports_complex","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"color":"#f77e4d"}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"visibility":"off"},{"color":"#873412"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"color":"#f77e4d"}]},{"featureType":"transit","elementType":"geometry.fill","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#5bc7e0"}]}]
	};
	map = new google.maps.Map(document.getElementById('map'), mapOptions);

	var locations = [
		<?php 
		$expl = explode(",",$data["productGetter"]["location"]);
		$i = 1;
		foreach($expl as $e):
		$e = explode(":", $e);
		if(!empty($e) && isset($e[0]) && isset($e[1])){
			$lat = $e[0];
			$long = $e[1];
		}else{
			$lat = "0";
			$long = "0";
		}
		?>
      	['marker', <?=$lat?>,<?=$long?>, '<?=Config::PUBLIC_FOLDER?>img/marker-yellow.<?=$i?>.png'],
      	<?php $i++; endforeach; ?>
    ];
    var bounds = new google.maps.LatLngBounds();
	var marker, i;

    for (i = 0; i < locations.length; i++) {  
		if(locations[i][1]==0 || locations[i][2]==0){
			$(".map-title").hide();
			$("#map").hide();
			break;
		}
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[i][1], locations[i][2]),
			map: map,
			animation: google.maps.Animation.DROP,
			title: locations[i][0],
			icon: locations[i][3]
		});

		bounds.extend(marker.position);
    }
    map.fitBounds(bounds);
}
</script>
<!-- https://console.developers.google.com -->
 <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuNVK1o6mUkHGOO44eULUbWzLnkXDkUW4&amp;callback=initMap"

  type="text/javascript"></script>

<?=$data['footer']?>