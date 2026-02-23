<?php 
require_once("app/functions/l.php"); 
require_once("app/functions/strings.php"); 
require_once("app/functions/strip_output.php"); 
require_once("app/functions/pagination.php"); 
require_once("app/functions/breadcrups.php"); 
$l = new functions\l(); 
$string = new functions\strings(); 
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
                <div class="col-md-8" style="min-height: 754px;">
                    <div id="page-main">
                        <?php if(empty($data['staffId'])){ ?>
                        <section id="members">
                          <header><h1 class="ninoMtavruli"><?=$data['pageData']['title']?></h1></header>
                          <section id="our-speakers">
                            <?=$data["staff"]["html"]?>
                            <div style="clear: both;"></div>
                            <?php if($data['staff']['count']>Config::STAFF_PER_PAGE): ?>
                            <div class="center">
                            <?php
                            echo $pagination->intellect_pagination($data['staff']['count'], Config::STAFF_PER_PAGE);
                            ?>
                            </div>
                            <?php endif; ?>                       
                          </section><!-- /#our-speakers -->
                        </section>
                        <?php }else{ ?>
                        <section id="members">
                          <header><h1 class="ninoMtavruli">გუნდის წევრი</h1></header>
                          <div class="author-block member-detail">
                            <?php 
                           $photos = new Database("photos",array(
                              "method"=>"selectByParent", 
                              "idx"=>(int)$data['staff_inside']['idx'],  
                              "lang"=>strip_output::index($data['staff_inside']['lang']),  
                              "type"=>strip_output::index($data['staff_inside']['type'])
                            ));
                            if($photos->getter()){
                              $pic = $photos->getter();
                              $image = sprintf(
                                "%s%s/image/loadimage?f=%s%s&w=320&h=320",
                                Config::WEBSITE,
                                strip_output::index($_SESSION['LANG']),
                                Config::WEBSITE_,
                                strip_output::index($pic[0]['path'])
                              );
                            ?>
                              <figure class="author-picture">
                                <img src="<?=$image?>" alt="" />
                              </figure>
                            <?php } ?>
                            <article class="paragraph-wrapper">
                                <div class="inner glakho">
                                    <header><h2 class="ninoMtavruli" style="color:#000000"><?=$data['staff_inside']['title']?></h2></header>
                                    <figure><?=$data['staff_inside']['classname']?></figure>
                                    <hr>
                                    
                                    <?php
                                    echo strip_tags($data['staff_inside']['description'], "<p><a><ul><li><br><table><tr><td><th><h3>");

                                    $icons = "";
                                    if(preg_match_all("/\[(twitter)=(https:\/\/\w+\.\w+)\]/", $data['staff_inside']['url'], $twitter)){
                                      $icons .= sprintf(
                                        "<a href=\"%s\"><i class=\"fa fa-twitter\"></i></a>",
                                        $twitter[2][0]
                                      );
                                    }

                                    if(preg_match_all("/\[(facebook)=(https:\/\/.*)\]/", $data['staff_inside']['url'], $facebook)){
                                      $icons .= sprintf(
                                        "<a href=\"%s\"><i class=\"fa fa-facebook\"></i></a>",
                                        $facebook[2][0]
                                      );
                                    }

                                    if(preg_match_all("/\[(youtube)=(https:\/\/\w+\.\w+)\]/", $data['staff_inside']['url'], $youtube)){
                                      $icons .= sprintf(
                                        "<a href=\"%s\"><i class=\"fa fa-youtube-play\"></i></a>",
                                        $youtube[2][0]
                                      );
                                    }
                                    ?>
                                    <br />
                                    
                                    <div class="contact">
                                        <strong><?=$l->translate("contactinfo")?></strong>
                                        <div class="icons">
                                            <?=$icons?>
                                        </div><!-- /.icons -->
                                    </div>
                                  </div>
                                </article>
                              </div><!-- /.author -->
                            </section>
                          <?php } ?>
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
        <?php if(empty($data["staffId"])){ ?>
        $("body").addClass("page-sub-page page-about-us");
        <?php }else{ ?>
        $("body").addClass("page-sub-page page-members");
        <?php } ?>
    });
</script>

</body>
</html>