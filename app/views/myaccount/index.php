<?php 
require_once("app/functions/l.php"); 
require_once("app/functions/strip_output.php"); 
require_once("app/functions/request.php"); 
require_once 'app/functions/countrynames.php';
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
		<section class="title"><?=(isset($data["pageData"]["title"])) ? $data["pageData"]["title"] : ""?> - #<?=(int)$data["userdata"]["id"]?></section>
		<section class="row">
			<section class="col-lg-3 left">
				<?=$data["myaccountnav"]?>
			</section>
			<section class="col-lg-9 right">

				<?php 
				switch(functions\request::index("GET", "view")){
				case "purchases":
				echo $data["purchaseslist"];
				?>
				<!-- <section class="purchase-item">
					<section class="image" style="background-image: url('<?=Config::PUBLIC_FOLDER?>img/filemanager/batumi.jpg')">
					</section>
					<section class="header">
						<section class="pu-title">
							Batumi beach resort
						</section>
						<section class="pu-id">
							#51651651152
						</section>
					</section>
					<section class="pu-description">
						A four hour drive and 300 km (190 mi) away from Tbilisi lies the region of Racha – a highland area located in northwestern Georgia. This place has always attracted...
					</section>
					<section class="pu-links">
						<ul>
							<li>
								<a href="">
									<i class="fa fa-clock-o" aria-hidden="true"></i> 
									<span title="Book Time">12/05/2017 13:35</span>
								</a>
							</li>
							<li>
								<a href="">
									<i class="fa fa-usd" aria-hidden="true"></i> 
									<span title="Price">700$</span>
								</a>
							</li>
							<li>
								<a href="">
									<i class="fa fa-print" aria-hidden="true"></i> 
									<span title="Print">Print</span>
								</a>
							</li>
						</ul>
					</section>
					<section class="clearer"></section>
				</section> -->
				<?php 
				break; 
				case "favourites":
				echo $data["favouriteslist"];
				echo $pagination->myaccount($data["countFavourites"], $data["itemPerPage"]);
				break;
				case "profile":
				?>
				<form action="javascript:void(0)" method="get" id="profileForm">
					<?php if(!$data["userdata"]["email_confirm"]): ?>
					<section class="alert alert-warning"><?=$l->translate("emailnotconfirmed")?></section>
					<?php endif; ?>
					<section class="alert alert-warning profile-error-message" style="display: none"></section>

					<section class="input-group">
						<label><?=$l->translate("email")?></label>
						<input type="text" class="form-control" placeholder="Email" value="<?=(isset($_SESSION[Config::SESSION_PREFIX."web_username"])) ? $_SESSION[Config::SESSION_PREFIX."web_username"] : ""?>" readonly="readonly" />
					</section>

					<section class="input-group">
						<label><?=$l->translate("firstname")?></label>
						<input type="text" class="form-control" name="firstname" value="<?=$data["userdata"]["firstname"]?>" />
					</section>

					<section class="input-group">
						<label><?=$l->translate("lastname")?></label>
						<input type="text" class="form-control" name="lastname" value="<?=$data["userdata"]["lastname"]?>" />
					</section>

					<section class="input-group">
						<label><?=$l->translate("dob")?></label>
						<section class="dateBox">
							<input type="text" class="form-control date" name="dob" value="" readonly="readonly">
						</section>
					</section>

					<section class="input-group">
						<label><?=$l->translate("gender")?></label>
					</section>
					<input type="hidden" name="gender" id="gender" value="<?=$data["userdata"]["gender"]?>" />
					<select class="selectpicker" id="genderSelect" data-live-search="true">
						<option value="1"><?=$l->translate("male")?></option>
						<option value="2"><?=$l->translate("female")?></option>
					</select>


					<section class="input-group">
						<label><?=$l->translate("country")?></label>
					</section>
					<input type="hidden" name="country" id="country" value="<?=$data["userdata"]["country"]?>" />
					<select class="selectpicker" id="countrySelect" data-live-search="true">
					<?php 
					$countryNames = new functions\countrynames();
					echo $countryNames->options($_SESSION["LANG"]);
					?>						
					</select>

					<div class="input-group">
						<label><?=$l->translate("city")?></label>
						<input type="text" class="form-control" name="city" value="<?=htmlentities($data["userdata"]["city"])?>" />
					</div>

					<div class="input-group">
						<label><?=$l->translate("phone")?></label>
						<input type="text" class="form-control" name="phone" value="<?=htmlentities($data["userdata"]["phone"])?>" />
					</div>

					<div class="input-group">
						<label><?=$l->translate("postcode")?></label>
						<input type="text" class="form-control" name="postcode" value="<?=htmlentities($data["userdata"]["postcode"])?>" />
					</div>
					
					<button class="update updateProfile" data-plzwait="<?=$l->translate("plzwait")?>"><?=$l->translate("update")?></button>
					<script type="text/javascript">
					$('.selectpicker').selectpicker();
					$(".date").datepicker({
						format: 'mm/dd/yyyy'
					});

					$('#genderSelect').val(<?=(int)$data["userdata"]["gender"]?>);
					$('#genderSelect').selectpicker('refresh');

					$('#countrySelect').val(<?=(int)$data["userdata"]["country"]?>);
					$('#countrySelect').selectpicker('refresh');

					$('#genderSelect').on('changed.bs.select', function (e) {
		            	$("#gender").val(e.target.value);
		            });

		            $('#countrySelect').on('changed.bs.select', function (e) {
		            	$("#country").val(e.target.value);
		            });

		            <?php 
		            $dob = explode("-", $data["userdata"]["dob"]);
		            $dob = sprintf("%s/%s/%s", @$dob[1], @$dob[2], @$dob[0]);
		            ?>
					$(".date").datepicker("update", "<?=$dob?>");
					</script>
				</form>
				<?php
				break;
				case "changepassword":
				?>
				<form action="javascript:void(0)" method="get" id="updatepassword">
					<section class="alert alert-warning passwordupdate-error-message" style="display: none"></section>
					<div class="input-group">
						<label><?=$l->translate("currentpassword")?></label>
						<input type="password" class="form-control" name="cpoassword" value="" />
					</div>

					<div class="input-group">
						<label><?=$l->translate("newpassword")?></label>
						<input type="password" class="form-control" name="npassword" value="" />
					</div>

					<div class="input-group">
						<label><?=$l->translate("comfirmpassword")?></label>
						<input type="password" class="form-control" name="cnpassword" value="" />
					</div>					
					
					<button class="update updatePasswordButton" data-plzwait="<?=$l->translate("plzwait")?>"><?=$l->translate("update")?></button>
					
				</form>
				<?php break; } //end switch ?>


			</section>
		</section>
	</section>
</main>

<section class="clearer"></section>


<?=$data['footer']?>