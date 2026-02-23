<?php
require_once("app/functions/l.php"); 
require_once("app/functions/strip_output.php");
require_once("app/functions/pagination.php");
require_once("app/functions/breadcrups.php");
$l = new functions\l();
$pagination = new functions\pagination();
echo $data['headerModule']; 
echo $data['headertop']; 
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
                <div class="col-md-8">
                	<?php 
                	if(empty($data['vacanciesId'])){ ?>
                    <div id="page-main">
                        <section class="events" id="events">
                            <header><h1 class="ninoMtavruli"><?=$data['pageData']['title']?></h1></header>
                            <div class="row">
                                <?=$data['vacancies']['html']?>
                            </div><!-- /.row -->                  
                        </section><!-- /.blog-listing -->
                        <?php if(isset($data['vacancies']['count']) && $data['vacancies']['count']>Config::NEWS_PER_PAGE): ?>
                        <div class="center">
                        	<?php
                        	echo $pagination->intellect_pagination($data['vacancies']['count'], Config::NEWS_PER_PAGE);
                        	?>
                        </div>
                    	<?php endif; ?>
                    </div><!-- /#page-main -->
                    <?php }else{ ?>
                    


                    <div id="page-main">
                        <section id="about">
                           <header><h1 class="ninoMtavruli"><?=$data['vacancies_inside']['title']?></h1></header>
                           <?php 
                           $photos = new Database("photos",array(
								"method"=>"selectByParent", 
								"idx"=>(int)$data['vacancies_inside']['idx'],  
								"lang"=>strip_output::index($data['vacancies_inside']['lang']),  
								"type"=>strip_output::index($data['vacancies_inside']['type'])
							));
							if($photos->getter()){
								$pic = $photos->getter();
								$image = sprintf(
									"%s%s/image/loadimage?f=%s%s&w=750&h=280",
									Config::WEBSITE,
									strip_output::index($_SESSION['LANG']),
									Config::WEBSITE_,
									strip_output::index($pic[0]['path'])
								);
								echo sprintf(
									"<img src=\"%s\" alt=\"\" width=\"%s\" style=\"margin-bottom: 40px;\" />", 
									$image,
									"100%"
								);
							}

                           echo strip_tags($data['vacancies_inside']['description'], "<p><a><ul><li><br>");
                           ?>
                        </section><!-- /.blog-listing -->
                    </div><!-- /#page-main -->



                    <?php } ?>
                </div><!-- /.col-md-8 -->

                <!--SIDEBAR Content-->
                <div class="col-md-4">
                    <div id="page-sidebar" class="sidebar">
                        <aside id="news-small" class="news-small">
                            <header class="glakho"> 
                                <h2 class="ninoMtavruli"><?=$l->translate("lastnews")?></h2>
                                <?=$data["news"]?>
                            </header>
                        </aside><!-- /archive -->
                    </div><!-- /#sidebar -->
                </div><!-- /.col-md-4 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>
    <!-- end Page Content -->
</div>

<?=$data['footer']?>
<script type="text/javascript">
    $(document).ready(function(){
        $("body").removeClass("page-homepage-carousel");
        <?php if(empty($data["vacanciesId"])){ ?>
        $("body").addClass("page-sub-page page-blog-listing");
        <?php }else{ ?>
        $("body").addClass("page-sub-page page-about-us");
        <?php } ?>
    });
</script>
</body>
</html>

