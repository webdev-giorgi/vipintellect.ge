<?php
require_once("app/functions/l.php"); 
require_once("app/functions/strip_output.php"); 
$l = new functions\l();
echo $data['headerModule']; 
echo $data['headertop']; 
?>
<main>
	<section class="centerWidth">
		<section class="row">
			<section class="col s12 m6 l8">
				<section class="headerText">
					<div class="line"></div>
					<div class="title"><?=$l->translate('event')?></div>
				</section>
				<section class="event">
					<?=$data['mainevents']?>
					<section class="contactForm">
						<form action="" method="post">
							<?php
							require_once("app/functions/strings.php"); 
							$_SESSION['protect_x'] = functions\string::random(6);
							echo sprintf(
								"<input type=\"hidden\" name=\"csrf\" id=\"csrf\" class=\"csrf\" value=\"%s\">", 
								htmlspecialchars($_SESSION['protect_x'])
							);
							$evid = (isset($_SESSION["URL"][2])) ? $_SESSION["URL"][2] : "";
							echo sprintf(
								"<input type=\"hidden\" name=\"evid\" id=\"evid\" class=\"evid\" value=\"%s\">",
								htmlspecialchars($evid)
							);
							$evn = (isset($_SESSION["URL"][3])) ? $_SESSION["URL"][3] : "";
							echo sprintf(
								"<input type=\"hidden\" name=\"evn\" id=\"evn\" class=\"evn\" value=\"%s\">",
								str_replace("-"," ",htmlspecialchars($evn))
							);
							?>

							<section class="marginminus10">
								<div class="messageBox col s12 m12 l12"></div>
								<div class="input-field col s12 m6 l4">
									<input type="text" class="validate" id="input_name" value="" />
									<label class=""><?=$l->translate('name')?></label>
								</div>
								<div class="input-field col s12 m6 l4">
									<input type="text" class="validate" id="input_organization" value="" />
									<label><?=$l->translate('organization')?></label>
								</div>
								<div class="input-field col s12 m6 l4">
									<input type="text" class="validate" id="input_email" value="" />
									<label><?=$l->translate('email')?></label>
								</div>
								<div class="input-field col s12 m6 l4">
									<input type="text" class="validate" id="input_phone" value="" />
									<label><?=$l->translate('phone')?></label>
								</div>
								<div class="col s12 m12 l12">
									<a class="waves-effect waves-light btn submit" style="text-decoration: none;" onclick="registerEvent('<?=htmlspecialchars($_SESSION['LANG'])?>')"><?=$l->translate('submit')?></a>
								</div>
							</section>
						</form>
					</section>
				</section>
			</section>
			<section class="col s12 m6 l4">
				<section class="justTitle"><?=$l->translate('eventcalendar')?></section>
				<section class="CalendarBox">
					<?php
					require_once('app/functions/calendar.php'); 
					$calendar = new functions\calendar();
					echo $calendar->index(htmlspecialchars($_SESSION['LANG'])); 
					?>
				</section>

				<section class="justTitle marginTop40"><?=$l->translate('publications')?></section>
				<section class="files files-desktop" style="margin: 10px 0px; width: 100%">
					<section class="col s12 m12 l12 reports">
						<?=$data['publications']?>
					</section>
				</section>
			</section>

		</section>	
	</section>
</main>


<?=$data['footer']?>

</body>
</html>