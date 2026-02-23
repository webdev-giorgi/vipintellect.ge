<?php 
require_once("app/functions/l.php"); 
require_once("app/functions/strip_output.php"); 
require_once("app/functions/breadcrups.php"); 
$l = new functions\l(); 
echo $data['headerModule']; 
echo $data['headertop']; 

$photo = (isset($data["pageData"]["photo"])) ? Config::WEBSITE_.$data["pageData"]["photo"] : "";
?>

<div class="container">
    <?php 
    $breadcrups = new functions\breadcrups();
    echo $breadcrups->index();
    ?>

    <!-- Page Content -->
    <div id="page-content" role="main" style="display: block;">
        <div class="">
            <div class="row">
                <!--MAIN Content-->
                <div class="col-md-8" style="min-height: 754px;">
                    <div id="page-main">
                       <section id="contact">
                            <header><h1 class="ninoMtavruli"><?=$data['pageData']['title']?></h1></header>
                            <div class="row">
                                <div class="col-md-6 glakho" style="min-height: 354px;">
                                	<?php 
                                	// echo "<pre>";
                                	// print_r($data["contactdetails"]);
                                	// echo "</pre>";
                                	?>
                                    <address>
                                        <h3>VIP Intellect Group</h3>
                                        <br>
                                        <span><?=strip_tags($data["contactdetails"][1]["description"])?></span>
                                        <br><br>
                                        <abbr title="<?=$l->translate("contactnumber")?>"><?=$l->translate("contactnumber")?>:</abbr> <?=strip_tags($data["contactdetails"][0]["description"])?>
                                        <br>
                                        <abbr title="<?=$l->translate("email")?>"><?=$l->translate("email")?>:</abbr> 
                                        <a href="mailto:"><?=strip_tags($data["contactdetails"][2]["description"])?></a>
                                    </address>
                                    <div class="icons">
										<?php
											foreach ($data["socialnetworks"] as $item) {
												echo sprintf(
												"<a href=\"%s\" target=\"_blank\"><i class=\"%s\"></i></a>\n", 
												$item['url'], 
												$item['classname']
												);
											}
										?>
                                    </div><!-- /.icons -->
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="map-wrapper" id="map-wrapper" style="min-height: 354px;">
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="contact-form" class="clearfix">
                            <header><h2 class="ninoMtavruli"><?=$l->translate("writeus")?></h2></header>
                            <p class="contact-error-message glakho"></p>
                            <form class="contact-form glakho" id="contactform" method="post" action="?">
                                <div class="row">
                                    <div class="col-md-6" style="min-height: 72px;">
                                        <div class="input-group">
                                            <div class="controls">
                                                <label for="firstname"><?=$l->translate("namelname")?></label>
                                                <input type="text" name="firstname" id="firstname" required="">
                                            </div><!-- /.controls -->
                                        </div><!-- /.control-group -->
                                    </div><!-- /.col-md-4 -->
                                    <div class="col-md-6" style="min-height: 72px;">
                                        <div class="input-group">
                                            <div class="controls">
                                                <label for="email"><?=$l->translate("email")?></label>
                                                <input type="email" name="email" id="email" required="">
                                            </div><!-- /.controls -->
                                        </div><!-- /.control-group -->
                                    </div><!-- /.col-md-4 -->
                                </div><!-- /.row -->
                                <div class="row">
                                    <div class="col-md-12" style="min-height: 158px;">
                                        <div class="input-group">
                                            <div class="controls">
                                                <label for="massage"><?=$l->translate("message")?></label>
                                                <textarea name="massage" id="massage" required=""></textarea>
                                            </div><!-- /.controls -->
                                        </div><!-- /.control-group -->
                                    </div><!-- /.col-md-4 -->
                                </div><!-- /.row -->
                                <div class="pull-right">
                                    <input type="button" class="btn btn-color-primary" id="submit" value="<?=$l->translate("send")?>" style="background-color: #000000" />
                                </div><!-- /.form-actions -->
                                <div id="form-status"></div>
                            </form><!-- /.footer-form -->
                        </section>
                    </div><!-- /#page-main -->
                </div><!-- /.col-md-8 -->

                <!--SIDEBAR Content-->
                <div class="col-md-4" style="min-height: 754px;">
                    <div id="page-sidebar" class="sidebar">
                        <aside class="news-small" id="news-small">
                            <header>
                                <h2 class="ninoMtavruli"><?=$l->translate("lastnews")?></h2>
                            </header>
                            <div class="section-content glakho">
                                <?=$data["news"]?>
                            </div><!-- /.section-content -->
                            <a href="/<?=$_SESSION["LANG"]?>/news" class="read-more glakho"><?=$l->translate("allnews")?></a>
                        </aside><!-- /.news-small -->
                        
                       
                    </div><!-- /#sidebar -->
                </div><!-- /.col-md-4 -->
            </div><!-- /.row -->
        </div>
    </div>
    <!-- end Page Content -->
</div>

<?=$data['footer']?>
<script type="text/javascript">
    $(document).ready(function(){
        $("body").removeClass("page-homepage-carousel");
        $("body").addClass("page-sub-page page-contact");
    });
</script>
<script type="text/javascript">
var map;
function initMap() {
	var mapOptions = {
	    zoom: 14,
	    center: new google.maps.LatLng(<?=strip_tags($data["contactdetails"][3]["description"])?>)
	};
	map = new google.maps.Map(document.getElementById('map-wrapper'), mapOptions);
	var marker = new google.maps.Marker({
	    position: new google.maps.LatLng(<?=strip_tags($data["contactdetails"][3]["description"])?>), 
	    map: map,
	    animation: google.maps.Animation.DROP,
	    title: '',
	    icon: '/public/img/marker.png'
	});
}
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLZ76vSDuPKXeml3hu_b_OvKQjX-KVQg8&amp;callback=initMap" type="text/javascript"></script>
</body>
</html>