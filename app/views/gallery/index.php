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
                        <section id="gallery">
                          <header>
                              <h2 class="ninoMtavruli">გალერეა</h2>
                          </header>
                          <div class="section-content">
                            <?=$data["gallery"]?>
                          </div><!-- /.section-content -->
                      </section><!-- /.gallery -->
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
                                <h2 class="ninoMtavruli">ჩვენი გუნდი</h2>
                            </header>
                            <div class="section-content">
                                <div class="professors">
                                    <?=$data["staff"]?>
                                    <a href="/<?=$_SESSION["LANG"]?>/staff" class="read-more glakho">ნახე მეტი</a>
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