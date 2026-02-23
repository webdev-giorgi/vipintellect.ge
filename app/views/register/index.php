<?php
require_once("app/functions/l.php"); 
require_once("app/functions/strip_output.php");
require_once("app/functions/breadcrups.php");
require_once("app/functions/trainings.php"); 
$l = new functions\l();
$trainings = new functions\trainings();
echo $data['headerModule']; 
echo $data['headertop']; 
?>

<div class="container">
    <?php 
    $breadcrups = new functions\breadcrups();
    echo $breadcrups->index();
    ?>
</div>

<div id="page-content" role="main">
    <div class="container">
        <div class="row">
            <!--MAIN Content-->
            <div id="page-main">
                <div class="col-md-10 col-sm-10 col-sm-offset-1 col-md-offset-1">
                    <div class="row">
                        <div class="col-md-12">
                            <section id="account-register" class="account-block">
                                <header><h2 class="ninoMtavruli"><?=$data['pageData']['title']?></h2></header>
                                <form role="form" class="clearfix glakho" action="">
                                    <div class="form-group">
                                        <label><?=$l->translate("namelname")?></label>
                                        <input type="text" name="firstname" id="firstname" class="form-control" placeholder="<?=$l->translate("namelname")?>">
                                    </div>
                                    <div class="form-group">
                                        <label><?=$l->translate("contactnumber")?></label>
                                        <input type="text" name="phone" id="phone" class="form-control" placeholder="<?=$l->translate("contactnumber")?>">
                                    </div>
                                    <div class="form-group">
                                        <label><?=$l->translate("email")?></label>
                                        <input type="text" name="email" id="email" class="form-control" placeholder="<?=$l->translate("email")?>">
                                    </div>

                                    <div class="form-group">
                                        <label><?=$l->translate("age")?></label>
                                        <input type="text" name="age" id="age" class="form-control" placeholder="<?=$l->translate("age")?>">
                                    </div>
                                    <div class="form-group">
                                        <label><?=$l->translate("trainingstarttime")?></label>
                                        <input type="text" name="starttime" id="starttime" class="form-control" placeholder="<?=$l->translate("trainingstarttime")?>">
                                    </div>
                                    <div class="form-group">
                                        <label><?=$l->translate("howfindus")?></label>
                                        <select name="howfind" id="howfind" class="has-dark-background glakho">
                                            <option value=""><?=$l->translate("howfindus")?></option>
                                            <?php foreach($data["howfindus"] as $item):?>
                                            <option value="<?=$item['idx']?>"><?=$item['title']?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label><?=$l->translate("choosetraining")?></label>
                                        <?=$trainings->index()?>
                                    </div>
                                    <button type="button" class="btn pull-right register-to-training"><?=$l->translate("register")?></button>
                                </form>
                            </section><!-- /#account-block -->
                        </div><!-- /.col-md-6 -->
                        
                    </div><!-- /.row -->
                </div><!-- /.col-md-10 -->
            </div><!-- /#page-main -->

            <!-- end SIDEBAR Content-->
        </div><!-- /.row -->
    </div><!-- /.container -->
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