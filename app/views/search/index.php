<?php
require_once("app/functions/l.php"); 
require_once("app/functions/strip_output.php");
require_once("app/functions/breadcrups.php");
$l = new functions\l();
echo $data['headerModule']; 
echo $data['headertop']; 
// $searchText = htmlspecialchars(strip_tags($data['searchText']));
?>

<div class="container">
    <?php 
    $breadcrups = new functions\breadcrups();
    echo $breadcrups->index();
    ?>    
</div>

<div id="page-content" role="main">
	<div class="container">
        <!--MAIN Content-->
        <div id="page-main">
            <section id="right-sidebar">
                <header><h2 class="ninoMtavruli"><?=$data['pageData']['title']?> :: <?=$data["word"]?></h2></header>                

                <ul class="list-links glakho">
                	<?php 
                		foreach($data["search"] as $s): 
                			if($s["page_type"]=="text"):
                	?>
								<li><a href="/<?=$_SESSION["LANG"]?>/<?=$s['page_slug']?>"><?=$s['page_title']?></a></li>
					<?php
							endif;

							if($s["page_type"]=="news"):
							?>
							<li><a href="/<?=$_SESSION["LANG"]?>/news/<?=$s['page_slug']?>/<?=htmlentities($s['page_title'])?>"><?=$s['page_title']?></a></li>
							<?php
							endif;

							if($s["page_type"]=="ongoing"):
							?>
							<li><a href="/<?=$_SESSION["LANG"]?>/ongoing/<?=$s['page_slug']?>/<?=htmlentities($s['page_title'])?>"><?=$s['page_title']?></a></li>
							<?php
							endif;

							if($s["page_type"]=="finished"):
							?>
							<li><a href="/<?=$_SESSION["LANG"]?>/finished/<?=$s['page_slug']?>/<?=htmlentities($s['page_title'])?>"><?=$s['page_title']?></a></li>
							<?php
							endif;

							if($s["page_type"]=="future"):
							?>
							<li><a href="/<?=$_SESSION["LANG"]?>/future/<?=$s['page_slug']?>/<?=htmlentities($s['page_title'])?>"><?=$s['page_title']?></a></li>
							<?php
							endif;

							if($s["page_type"]=="vacancies"):
							?>
							<li><a href="/<?=$_SESSION["LANG"]?>/vacancies/<?=$s['page_slug']?>/<?=htmlentities($s['page_title'])?>"><?=$s['page_title']?></a></li>
							<?php
							endif;

						endforeach; 

					?>
				</ul>
               
            </section>
        </div><!-- /#page-main -->
    <!-- end MAIN Content -->
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