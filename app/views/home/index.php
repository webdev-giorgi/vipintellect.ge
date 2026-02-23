<?php 
require_once("app/functions/l.php"); 
require_once("app/functions/strings.php"); 
require_once("app/functions/strip_output.php"); 
require_once("app/functions/trainings.php"); 
require_once("app/functions/getWebpUrl.php"); 

$getWebpUrl = new functions\getWebpUrl(); 
$l = new functions\l(); 
$string = new functions\strings(); 
$trainings = new functions\trainings(); 


echo $data['headerModule']; 
echo $data['headertop']; 
?>
<div id="page-content" role="main">
    <!-- start Slider -->
    <div id="homepage-carousel">
        <div class="container">
            <div class="homepage-carousel-wrapper">
                <div class="row">
                    <div class="col-md-6 col-sm-7">
                        <div class="image-carousel">
                            <?php echo $data['slider']['list']; ?>
                        </div><!-- /.slider-image -->
                    </div><!-- /.col-md-6 -->
                    <div class="col-md-6 col-sm-5">
                        <div class="slider-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1 class="ninoMtavruli"><?=$l->translate("registertraining")?></h1>
                                    <form id="slider-form" role="form" action="" method="post">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <input class="form-control has-dark-background glakho" name="firstname" id="firstname" placeholder="<?=$l->translate("namelname")?>" type="text" />
                                                </div>
                                            </div><!-- /.col-md-6 -->
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <input class="form-control has-dark-background glakho" name="phone" id="phone" placeholder="<?=$l->translate("contactnumber")?>" type="text" />
                                                </div>
                                            </div><!-- /.col-md-6 -->
                                        </div><!-- /.row -->

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <input class="form-control has-dark-background glakho" name="email" id="email" placeholder="<?=$l->translate("email")?>" type="text" />
                                                </div>
                                            </div><!-- /.col-md-6 -->
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <input class="form-control has-dark-background glakho" name="age" id="age" placeholder="<?=$l->translate("age")?>" type="text" />
                                                </div>
                                            </div><!-- /.col-md-6 -->
                                        </div><!-- /.row -->

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <input class="form-control has-dark-background glakho" name="starttime" id="starttime" placeholder="<?=$l->translate("trainingstarttime")?>" type="text" />
                                                </div>
                                            </div><!-- /.col-md-6 -->
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <select name="howfind" id="howfind" class="has-dark-background glakho">
                                                        <option value=""><?=$l->translate("howfindus")?></option>
                                                        <?php foreach($data["howfindus"] as $item):?>
                                                        <option value="<?=$item['idx']?>"><?=$item['title']?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div><!-- /.form-group -->
                                            </div><!-- /.col-md-6 -->
                                        </div><!-- /.row -->

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <?=$trainings->index()?>
                                                </div><!-- /.form-group -->
                                            </div><!-- /.col-md-6 -->
                                            <div class="col-md-6">
                                                
                                            </div><!-- /.col-md-6 -->
                                        </div><!-- /.row -->
                                        <button type="button" id="slider-submit" class="btn btn-framed pull-right glakho register-to-training"><?=$l->translate("register")?></button>
                                        <div id="form-status"></div>
                                    </form>
                                </div><!-- /.col-md-12 -->
                            </div><!-- /.row -->
                        </div><!-- /.slider-content -->
                    </div><!-- /.col-md-6 -->
                </div><!-- /.row -->
                <div class="background"></div>
            </div><!-- /.slider-wrapper -->
            <div class="slider-inner"></div>
        </div><!-- /.container -->
    </div>
    <!-- end Slider -->

    <?php if(isset($data['slogan']['description']) && !empty($data['slogan']['description'])) : ?>
    <!-- Testimonial -->
    <section id="testimonials">
        <div class="block" style="padding-top: 20px; padding-bottom: 10px;">
            <div class="container">
                <div class="author-carousel" style="transform: skew(-10deg); -webkit-transform: skew(-10deg); -moz-transform: skew(-10deg);">
                    <div class="author">
                        <blockquote>
                        <article class="paragraph-wrapper">
                                <div class="inner">
                                    <header><?=strip_tags($data['slogan']['description'])?></header>
                                    <!-- <footer>Claire Doe</footer> -->
                                </div>
                            </article>
                        </blockquote>
                    </div><!-- /.author -->
                    
                </div><!-- /.author-carousel -->
            </div><!-- /.container -->
        </div><!-- /.block -->
    </section>
    <!-- end Testimonial -->
    <?php endif; ?>


  <!-- News, Events, About -->
  <div class="block g-news-fb-block">
      <div class="container">
          <div class="row">
              <div class="col-md-4 col-sm-6">
                  <section class="news-small" id="news-small">
                      <header>
                          <h2 class="ninoMtavruli"><?=$l->translate("news")?></h2>
                      </header>
                      <div class="section-content glakho">
                          <?=$data["news"]?>                          
                      </div><!-- /.section-content -->
                      <div class="clear"></div>
                      <a href="/<?=$_SESSION["LANG"]?>/news" class="read-more stick-to-bottom glakho" style="font-size: 12px;"><?=$l->translate("allnews")?></a>
                  </section><!-- /.news-small -->
              </div><!-- /.col-md-4 -->
              <div class="col-md-4 col-sm-6">
                  <section class="events small" id="events-small">
                      <header>
                          <h2 class="ninoMtavruli"><?=$l->translate("vacancies")?></h2>
                      </header>
                      <div class="section-content glakho">
                          <?=$data['vacancies']?>
                          
                      </div><!-- /.section-content -->
                  </section><!-- /.events-small -->
                  <div class="clear"></div>
                  <a href="/<?=$_SESSION["LANG"]?>/vacancies" class="read-more glakho" style="font-size: 12px;"><?=$l->translate("allvacancies")?></a>
              </div><!-- /.col-md-4 -->
              <div class="col-md-4 col-sm-12">
                  <section id="about">
                  <div id="fb-container"></div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const container = document.getElementById("fb-container");

  const observer = new IntersectionObserver(entries => {
    if (entries[0].isIntersecting) {
      loadFacebook();
      observer.disconnect();
    }
  });

  observer.observe(container);

  function loadFacebook() {
    const script = document.createElement("script");
    script.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v19.0";
    script.async = true;
    script.defer = true;
    document.body.appendChild(script);

    container.innerHTML = `
      <div class="fb-page"
        data-href="https://www.facebook.com/vipintellectgroup"
        data-tabs="timeline"
        data-width="340"
        data-height="400">
      </div>`;
  }
});
</script>
                  </section><!-- /.about -->
              </div><!-- /.col-md-4 -->
          </div><!-- /.row -->
      </div><!-- /.container -->
  </div>
  <!-- end News, Events, About -->

  <!-- Our Professors, Gallery -->
  <div class="block">
      <div class="container">
          <div class="row">
              <div class="col-md-4 col-sm-4">
                  <section id="our-professors">
                      <header>
                          <h2 class="ninoMtavruli"><?=$l->translate("ourteam")?></h2>
                      </header>
                      <div class="section-content">
                          <div class="professors">
                              <?=$data["staff"]?>
                              <a href="/<?=$_SESSION["LANG"]?>/staff" class="read-more glakho" style="font-size: 12px;"><?=$l->translate("more")?></a>                            
                          </div><!-- /.professors -->
                              
                      </div><!-- /.section-content -->
                  </section><!-- /.our-professors -->
              </div><!-- /.col-md-4 -->

              <div class="col-md-8 col-sm-8">
                  <section id="gallery">
                      <header>
                          <h2 class="ninoMtavruli"><?=$l->translate("gallery")?></h2>
                      </header>
                      <div class="section-content">
                          <?=$data["gallery"]?>
                          <a href="/<?=$_SESSION["LANG"]?>/gallery" class="read-more glakho" style="font-size: 12px;"><?=$l->translate("more")?></a>
                      </div><!-- /.section-content -->
                  </section><!-- /.gallery -->
              </div><!-- /.col-md-4 -->

          </div><!-- /.row -->
      </div><!-- /.container -->
  </div>
  <!-- end Our Professors, Gallery -->

  <!-- Partners, Make a Donation -->
  <div class="block">
      <div class="container">
          <div class="row">
              <div class="col-md-12 col-sm-12">
                  <section id="partners">
                      <header>
                          <h2 class="ninoMtavruli"><?=$l->translate("finishprojects")?></h2>
                      </header>
                      <div class="section-content">
                          <div class="logos">  
                            <?php 
                              foreach($data["partners"] as $item): 
                                $photos = new Database("photos",array(
                                  "method"=>"selectByParent", 
                                  "idx"=>(int)$item['idx'],  
                                  "lang"=>strip_output::index($item['lang']),  
                                  "type"=>strip_output::index($item['type'])
                                ));
                                if($photos->getter()){
                                  $pic = $photos->getter();
                                  $imageUrl = sprintf(
                                    "%s%s",
                                    Config::WEBSITE_,
                                    strip_output::index($pic[0]['path'])
                                  );

                                //   $image = $getWebpUrl->index($imageUrl, array(200, 140));
                                  $image = $imageUrl;
                                }else{
                                  $image = "/public/filemanager/noimage.png";
                                }
                            ?>                      
                              <div class="logo">
                                <a href="<?=$item['url']?>" title="<?=htmlentities($item['title'])?>" />
                                  <img src="<?=$image?>" alt="<?=htmlentities($item['title'])?>" width="100" height="70" style="object-fit: contain" />
                                </a>
                              </div>
                            <?php endforeach; ?>
                          </div>
                      </div>
                  </section>
              </div><!-- /.col-md-9 -->
              
          </div><!-- /.row -->
      </div><!-- /.container -->
  </div>
  <!-- end Partners, Make a Donation -->

</div>
<!-- end Page Content -->

<?=$data['footer']?>


</body>
</html>