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
				  <?=$data["faqs"]?>
				</section>
				<!-- Panel End -->
			</section>
		</section>
	</section>
</main>

<section class="clearer"></section>

<?=$data['footer']?>