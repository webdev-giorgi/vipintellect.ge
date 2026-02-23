<?php 
class _footer
{
	public $data;

	public function index()
	{
		require_once("app/functions/l.php");
		require_once("app/functions/strip_output.php");  
		require_once("app/functions/strings.php");
		require_once("app/functions/request.php"); 

		$l = new functions\l(); 	
		$word = "";
		if(functions\request::index("GET","w")){
			$word = strip_tags(functions\request::index("GET","w"));
			$word = str_replace(
				array("-", "%20", "'", '"'),
				array(" ", " ", "", ""),
				$word
			); 
		};	

		$out = "";
		
		$out .= "<footer id=\"page-footer\">\n";

		$out .= "<section id=\"footer-top\">\n";
		$out .= "<div class=\"container\">\n";
		
		$out .= "<div class=\"footer-inner\">\n";

		$out .= "<div class=\"footer-social\">\n";
		$out .= "<div class=\"icons\">\n";
		foreach ($this->data["socialnetworks"] as $item) {
			$out .= sprintf(
				"<a href=\"%s\" target=\"_blank\" aria-label=\"%s\"><i class=\"%s\"></i></a>\n", 
				$item['url'], 
				$item['title'],
				$item['classname']
			);
		}
		$out .= "</div>\n";
		$out .= "</div>\n";

		$out .= "<div class=\"search pull-right\">\n";
		$out .= "<div class=\"input-group\">\n";
		$out .= sprintf(
			"<input type=\"text\" class=\"form-control glakho\" id=\"bottom_search\" placeholder=\"%s\" value=\"%s\" />\n",
			$l->translate("search"),
			htmlentities($word)
		);
		$out .= "<span class=\"input-group-btn\">\n";
		$out .= "<button type=\"submit\" class=\"btn bottom_search_button\" aria-label=\"Seach\"><i class=\"fa fa-search\"></i></button>\n";
		$out .= "</span>\n";
		$out .= "</div>\n";
		$out .= "</div>\n";

		$out .= "</div> <!-- /.footer-inner -->\n";
		$out .= "</div> <!-- /.container -->\n";
		$out .= "</section><!-- /#footer-top -->\n";


		$out .= "<section id=\"footer-content\" style=\"background-color: #090983;\">\n";
		$out .= "<div class=\"container\">\n";
		$out .= "<div class=\"row\">\n";
		
		$out .= "<div class=\"col-md-3 col-sm-12\">\n";
		$out .= "<aside class=\"logo\">\n";
		$out .= sprintf(
			"<img src=\"%simg/logo-white.png\" class=\"vertical-center\" width=\"180\" height=\"43\" style=\"object-fit:contain; object-position: left\" alt=\"Logo\">\n",
			Config::PUBLIC_FOLDER
		);
		$out .= "</aside>\n";
		$out .= "</div><!-- /.col-md-3 -->\n";

		$out .= "<div class=\"col-md-3 col-sm-4\">\n";
		$out .= "<aside>\n";
		$out .= sprintf(
			"<header><h3 class=\"ninoMtavruli\">%s</h3></header>\n",
			$l->translate("contactus")
		);
		$out .= "<address class=\"glakho\">\n";
		$out .= "<strong>VIP Intellect Group</strong><br>\n";
		$out .= sprintf(
			"<span>%s</span><br><br><br>\n", 
			(isset($this->data['contactdetails'][1]['description'])) ? strip_tags($this->data['contactdetails'][1]['description']) : ''
		);
		$out .= sprintf(
			"<abbr title=\"%s\">%s:</abbr> %s<br>\n",
			$l->translate("contactnumber"),
			$l->translate("contactnumber"),
			(isset($this->data['contactdetails'][0]['description'])) ? strip_tags($this->data['contactdetails'][0]['description']) : ''
		);
		$out .= sprintf(
			"<abbr title=\"%s\">%s:</abbr> <a href=\"#\">%s</a>\n", 
			$l->translate("email"),
			$l->translate("email"),
			(isset($this->data['contactdetails'][2]['description'])) ? strip_tags($this->data['contactdetails'][2]['description']) : ''
		);
		$out .= "</address>\n";
		$out .= "</aside>\n";
		$out .= "</div><!-- /.col-md-3 -->\n";

		$out .= "<div class=\"col-md-3 col-sm-4\">\n";
		$out .= "<aside>\n";
		$out .= sprintf(
			"<header><h3 class=\"ninoMtavruli\">%s</h3></header>\n",
			$l->translate("trainings")
		);
		$out .= "<ul class=\"list-links glakho\">\n";
		foreach ($this->data['footerHelpNav'] as $item) {
			if(isset($item['redirect']) && $item['redirect']!=""){
				$url = $item['redirect'];
			}else{
				$url = sprintf(
					"%s%s/%s",
					Config::WEBSITE,
					strip_output::index($_SESSION['LANG']),
					strip_output::index($item['slug'])
				);
			}

			$out .= sprintf(
				"<li><a href=\"%s\">%s</a></li>\n",
				$url, 
				$item['title']
		);
		}		
		$out .= "</ul>\n";
		$out .= "</aside>\n";
		$out .= "</div><!-- /.col-md-3 -->\n";

		$out .= "<div class=\"col-md-3 col-sm-4\">\n";
		$out .= "<aside>\n";
		$out .= sprintf(
			"<header><h3 class=\"ninoMtavruli\">%s</h3></header>\n",
			$l->translate("usefulllinks")
		);
		$out .= "<ul class=\"list-links glakho\">\n";
		foreach ($this->data['usefulllinks'] as $item) {
			$target = ($item['classname']=="blank") ? ' target="_blank"' : '';
			$out .= sprintf(
				"<li><a href=\"%s\"%s>%s</a></li>\n",
				$item['url'],
				$target, 
				$item['title']
			);
		}
		$out .= "</ul>\n";
		$out .= "</aside>\n";
		$out .= "</div><!-- /.col-md-3 -->\n";

		$out .= "</div><!-- /.row -->\n";
		$out .= "</div><!-- /.container -->\n";
		$out .= "<div class=\"background\">\n";
		$out .= "</div>\n";
		$out .= "</section><!-- /#footer-content -->\n";
		
		// footer footer
		$out .= "<section id=\"footer-bottom\" style=\"border-top: solid 1px rgba(255, 255, 255, 0.05);\">";
		$out .= "<div class=\"container\">";
		$out .= "<div class=\"footer-inner\">";
		$out .= "<div class=\"copyright\">© 2018 VIP Intellect Group</div>";

		$out .= "<div style=\"float:right; width:88px; height:31px;\">
		<div id=\"top-ge-counter-container\" 
		data-site-id=\"110943\" 
		style=\"width:88px; height:31px;\">
		</div>
		</div>

		<script>
		function loadCounter() {
		var s = document.createElement('script');
		s.src = '//counter.top.ge/counter.js';
		s.async = true;
		document.body.appendChild(s);

		window.removeEventListener('scroll', loadCounter);
		window.removeEventListener('mousemove', loadCounter);
		}

		window.addEventListener('scroll', loadCounter, { once: true });
		window.addEventListener('mousemove', loadCounter, { once: true });
		</script>";

		$out .= "</div>";
		$out .= "</div>";
		$out .= "</section>";


		$out .= "</footer>";
		$out .= "</div>";

		$out .= sprintf(
			"<script src=\"%sjs/web/jquery2.1.0.min.js\" charset=\"utf-8\" defer></script>\n", 
			Config::PUBLIC_FOLDER
		);
	

		$out .= sprintf(
			"<script src=\"%sjs/web/jquerymigrate1.2.1.min.js\" charset=\"utf-8\" defer></script>\n", 
			Config::PUBLIC_FOLDER
		);

		$out .= sprintf(
			"<script src=\"%sbootstrap/js/bootstrap.min.js\" charset=\"utf-8\" defer></script>\n", 
			Config::PUBLIC_FOLDER
		);

		$out .= sprintf(
			"<script src=\"%sjs/web/selectize.min.js\" charset=\"utf-8\" defer></script>\n", 
			Config::PUBLIC_FOLDER
		);

		$out .= sprintf(
			"<script src=\"%sjs/web/owl.carousel.min.js\" charset=\"utf-8\" defer></script>\n", 
			Config::PUBLIC_FOLDER
		);

		$out .= sprintf(
			"<script src=\"%sjs/web/jquery.validate.min.js\" charset=\"utf-8\" defer></script>\n", 
			Config::PUBLIC_FOLDER
		);

		$out .= sprintf(
			"<script src=\"%sjs/web/jquery.placeholder.js\" charset=\"utf-8\" defer></script>\n", 
			Config::PUBLIC_FOLDER
		);

		$out .= sprintf(
			"<script src=\"%sjs/web/jQuery.equalHeights.js\" charset=\"utf-8\" defer></script>\n", 
			Config::PUBLIC_FOLDER
		);

		$out .= sprintf(
			"<script src=\"%sjs/web/icheck.min.js\" charset=\"utf-8\" defer></script>\n", 
			Config::PUBLIC_FOLDER
		);

		$out .= sprintf(
			"<script src=\"%sjs/web/jquery.vanillabox-0.1.5.min.js\" charset=\"utf-8\" defer></script>\n", 
			Config::PUBLIC_FOLDER
		);

		$out .= sprintf(
			"<script src=\"%sjs/web/retina-1.1.0.min.js\" charset=\"utf-8\" defer></script>\n", 
			Config::PUBLIC_FOLDER
		);

		$out .= sprintf(
			"<script src=\"%sjs/web/custom.js\" charset=\"utf-8\" defer></script>\n", 
			Config::PUBLIC_FOLDER
		);	

		return $out;
	}
}