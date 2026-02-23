<?php 
class _top
{
	public  $data;

	public function index()
	{
		require_once("app/functions/l.php"); 
		require_once("app/functions/strip_output.php"); 
		require_once("app/functions/language_output_name.php"); 
		require_once("app/functions/request.php"); 

		$word = "";
		if(functions\request::index("GET","w")){
			$word = strip_tags(functions\request::index("GET","w"));
			$word = str_replace(
				array("-", "%20", "'", '"'),
				array(" ", " ", "", ""),
				$word
			); 
		}

		$language_output_name = new functions\language_output_name();
		$outputName = $language_output_name->index();

		$l = new functions\l();

		/* Language manipulation */
		$currentUrl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$toGeorgian = str_replace("/en/", "/ge/", $currentUrl, $count1);
		if($count1 <= 0){
			$toGeorgian = Config::WEBSITE."ge";
		}

		$toEnglish = str_replace("/ge/", "/en/", $currentUrl, $count2);
		if($count2 <= 0){
			$toEnglish = Config::WEBSITE."en";
		}


		$out = "\n";

		$out .= "<div class=\"wrapper\">\n";
		$out .= "<div class=\"navigation-wrapper\">\n";
		$out .= "<div class=\"secondary-navigation-wrapper\">\n";
		
		$out .= "<div class=\"container\">\n";
		$out .= "<div class=\"navigation-contact pull-left glakho\">\n";
		$out .= sprintf(
			"%s: <span class=\"opacity-70\">%s</span>\n",
			$l->translate("contactus"),
			(isset($this->data['contactdetails'][0]['description'])) ? strip_tags($this->data['contactdetails'][0]['description']) : ''
		);
		$out .= "</div>\n";

		$out .= "<div class=\"search\">\n";
		$out .= "<div class=\"input-group\">\n";
		$out .= sprintf(
			"<input type=\"search\" class=\"form-control glakho\" name=\"search\" id=\"topsearch\" value=\"%s\" placeholder=\"%s\">\n",
			htmlentities($word), 
			$l->translate("search")
		);
		$out .= "<span class=\"input-group-btn\">\n";
		$out .= "<button type=\"submit\" id=\"search-submit\" class=\"btn\" aria-label=\"Search\">\n";
		$out .= "<i class=\"fa fa-search\"></i>\n";
		$out .= "</button>\n";
		$out .= "</span>\n";
		$out .= "</div><!-- /.input-group -->\n";
		$out .= "</div>\n";

		$out .= sprintf(
			"<input type=\"hidden\" name=\"input_lang\" id=\"input_lang\" value=\"%s\" />\n",
			$_SESSION["LANG"]
		);
		$out .= "<ul class=\"secondary-navigation list-unstyled glakho\">\n";
		$out .= sprintf(
			"<li><a href=\"%s\"%s>ქართული</a></li>\n",
			$toGeorgian,
			(isset($_SESSION["LANG"]) && $_SESSION["LANG"]=="ge") ? ' class="active"' : ''
		);
		$out .= sprintf(
			"<li><a href=\"%s\"%s>English</a></li>\n",
			$toEnglish,
			(isset($_SESSION["LANG"]) && $_SESSION["LANG"]=="en") ? ' class="active"' : ''
		);
		$out .= "</ul>\n";
		$out .= "</div>\n";
		$out .= "</div><!-- /.secondary-navigation -->\n";
		
		$out .= "<div class=\"primary-navigation-wrapper\" style=\"border-bottom: solid 1px rgba(0, 0, 0, 0.1);\">\n";
		$out .= "<header class=\"navbar\" id=\"top\" role=\"banner\">\n";
		$out .= "<div class=\"container\">\n";
		$out .= "<div class=\"navbar-header\">\n";
		$out .= "<button class=\"navbar-toggle\" type=\"button\" data-toggle=\"collapse\" data-target=\".bs-navbar-collapse\">\n";
		$out .= "<span class=\"sr-only\">Toggle navigation</span>\n";
		$out .= "<span class=\"icon-bar\"></span>\n";
		$out .= "<span class=\"icon-bar\"></span>\n";
		$out .= "<span class=\"icon-bar\"></span>\n";
		$out .= "</button>\n";

		$out .= "<div class=\"navbar-brand nav\" id=\"brand\">\n";
		$out .= sprintf(
			"<a href=\"%s\"><img src=\"%simg/logo2.png\" alt=\"brand\" width=\"210\" height=\"50\" style=\"object-fit:contain; object-position: left\"></a>\n",
			Config::WEBSITE.$_SESSION["LANG"],
			Config::PUBLIC_FOLDER
		);
		$out .= "</div>\n";

		$out .= "</div>\n";

		$out .= $this->data['navigationModule'];


		$out .= "</div><!-- /.container -->\n";
		$out .= "</header><!-- /.navbar -->\n";
		$out .= "</div><!-- /.primary-navigation -->\n";
		$out .= "</div><!-- end Header -->\n";
		
		return $out;
	}
}