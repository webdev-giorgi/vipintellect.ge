<?php
require_once("app/functions/l.php"); 
require_once("app/functions/strip_output.php");
require_once("app/functions/breadcrups.php");
$l = new functions\l();
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
                        <section id="about" class="glakho">
                            <header><h1 class="ninoMtavruli"><?=$data['pageData']['title']?></h1></header>
                            <?php
                            $photos = new Database("photos",array(
								"method"=>"selectByParent", 
								"idx"=>(int)$data['pageData']['idx'],  
								"lang"=>strip_output::index($data['pageData']['lang']),  
								"type"=>strip_output::index($data['pageData']['type'])
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
									"<img src=\"%s\" width=\"%s\" style=\"margin-bottom: 40px;\" alt=\"%s\" />", 
									$image,
									"100%",
                                    $data['pageData']['title']
								);
							}                            
                            
                            $text = preg_replace_callback(
                                "/\[https\:\/\/\w+\.youtube\.com\/watch\?v=(\w+|\w+-\w+)\]/",
                                function($metches){
                                    $iframe = "<iframe width=\"100%\" height=\"415\" src=\"https://www.youtube.com/embed/".$metches[1]."\" frameborder=\"0\" allow=\"autoplay; encrypted-media\" allowfullscreen></iframe>";
                                    return $iframe;
                                },
                                strip_tags($data['pageData']['text'], "<div><h3><img><p><a><ul><li><br><table><tr><td><strong>")
                            );

                            $text = preg_replace_callback(
                                "/\[registration\]/",
                                function($metches){
                                    $l = new functions\l();
                                    $div = sprintf(
                                        "<a href=\"/%s/register?r=%s\" class=\"registrationButton\">%s</a>",
                                        $_SESSION["LANG"],
                                        $_SESSION["URL"][1],
                                        $l->translate("register")
                                    );
                                    return $div;
                                },
                                $text
                            );

                            echo $text;
                            
                            if(count($data["sub_navigation"])){
                            ?>
                                <ul class="list-links glakho">
                                <?php foreach($data["sub_navigation"] as $item): ?>
                                    <li>
                                        <a href="<?=Config::WEBSITE.$_SESSION['LANG']?>/<?=$item['slug']?>"><?=$item['title']?></a>
                                    </li>
                                <?php endforeach; ?>
                                </ul>
                            <?php }else{ ?>
                            <div style="margin-top: 40px;">
                            <div class="fb-like" data-href="<?=Config::WEBSITE.$_SESSION["LANG"]?>/<?=$_SESSION["URL"][1]?>" data-layout="standard" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>
                            </div> 
                            <?php } ?> 

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
                        
                        <aside id="our-professors">
                            <header>
                                <h2 class="ninoMtavruli"><?=$l->translate("ourteam")?></h2>
                            </header>
                            <div class="section-content">
                                <div class="professors">
                                    <?=$data["staff"]?>
                                    <a href="/<?=$_SESSION["LANG"]?>/staff" class="read-more glakho"><?=$l->translate("more")?></a>
                                </div><!-- /.professors -->
                            </div><!-- /.section-content -->
                        </aside><!-- /.our-professors -->
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
        $("body").addClass("page-sub-page page-about-us");
    });
</script>
</body>
</html>