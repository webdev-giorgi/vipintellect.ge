<?php 
class _header
{
	public $public;
	public $lang;
	public $pagedata;
	public $imageSrc;
	public $product;

	public function index(){ 

		$getter = $this->pagedata->getter(); 

		if(isset($getter['title'])){
			$title = strip_tags($getter['title']);
			$description = strip_tags($getter['description']);
		}else if(isset($getter[0]['title'])){
			$title = strip_tags($getter[0]['title']); 
			$description = strip_tags($getter[0]['description']);
		}else{
			$title = "";
			$description = "";
		}

		if(isset($this->product)){
			$title = strip_tags($this->product['title']);
			$description = strip_tags($this->product['short_description']);
		}

		$out = "<!DOCTYPE html>\n";
		$htmlLang = (isset($_SESSION['LANG']) && $_SESSION['LANG']=="ge") ? 'ka' : 'en';
		$out .= sprintf("<html lang=\"%s\">\n", $htmlLang);
		$out .= "<head>\n";
		
		$out .= "<script>
		window.addEventListener('load', function() {

			var s = document.createElement('script');
			s.src = 'https://www.googletagmanager.com/gtag/js?id=UA-117764606-1';
			s.async = true;
			document.head.appendChild(s);

			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}

			s.onload = function() {
				gtag('js', new Date());
				gtag('config', 'UA-117764606-1', { 'anonymize_ip': true });
			};

		});
		</script>\n";

		$out .= "<meta charset=\"utf-8\">\n";
		$out .= "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n";
				
		$out .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
		$out .= "<meta name=\"format-detection\" content=\"telephone=no\"/>\n";
		$out .= sprintf("<title>%s - Vip Intellect Group</title>\n", strip_tags($title));
		$out .= "<meta name=\"description\" content=\"".htmlentities($description)."\" />\n";
		
		$actual_link = "https://".$_SERVER["HTTP_HOST"].htmlentities($_SERVER["REQUEST_URI"]);
		$out .= "<meta property=\"fb:app_id\" content=\"1833020360093608\" />\n";
		$out .= "<meta property=\"og:title\" content=\"".strip_tags($title)."\" />\n";
		$out .= "<meta property=\"og:type\" content=\"website\" />\n";
		$out .= "<meta property=\"og:url\" content=\"".$actual_link."\"/>\n";
		$keywords = str_replace(" ", ",", strip_tags($description));
		$out .= sprintf(
			"<meta name=\"keywords\" content=\"%s\" />\n", 
			htmlentities($keywords)
		);
		
		if(isset($this->imageSrc)){
			$image = $this->imageSrc;
		}else{
			$image = $this->public."img/share2.jpg";
		}
		$out .= sprintf(
			"<meta property=\"og:image\" content=\"%s\" />\n", 
			$image
		);
		$out .= sprintf(
			"<link rel=\"image_src\" type=\"image/jpeg\" href=\"%s\" />\n", 
			$image
		);

		$out .= "<meta property=\"og:image:width\" content=\"600\" />\n";
		$out .= "<meta property=\"og:image:height\" content=\"315\" />\n";
		$out .= "<meta property=\"og:site_name\" content=\"Vip Intellect Group\" />\n";
		$out .= "<meta property=\"og:description\" content=\"".htmlentities($description)."\"/>\n";

		$out .= sprintf(
			"<link rel=\"icon\" type=\"image/ico\" href=\"%simg/favicon.png\" />\n", 
			$this->public
		);
		
		$out .= sprintf(
			"<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' as=\"style\" rel=\"preload\" onload=\"this.onload=null;this.rel='stylesheet'\" />\n"
		);

		$out .= sprintf(
			"<link href=\"%sfont-awesome/css/font-awesome.min.css\" as=\"style\" rel=\"preload\" onload=\"this.onload=null;this.rel='stylesheet'\">\n", 
			$this->public
		);

		$out .= sprintf(
			"<link href=\"%sbootstrap/css/bootstrap3.css\" as=\"style\" rel=\"preload\" onload=\"this.onload=null;this.rel='stylesheet'\">\n", 
			$this->public
		);

		$out .= sprintf(
			"<link href=\"%scss/web/selectize.css\" as=\"style\" rel=\"preload\" onload=\"this.onload=null;this.rel='stylesheet'\">\n", 
			$this->public
		);

		$out .= sprintf(
			"<link href=\"%scss/web/owl.carousel.css\" as=\"style\" rel=\"preload\" onload=\"this.onload=null;this.rel='stylesheet'\">\n", 
			$this->public
		);

		$out .= sprintf(
			"<link href=\"%scss/web/vanillabox/vanillabox.css\" as=\"style\" rel=\"preload\" onload=\"this.onload=null;this.rel='stylesheet'\">\n", 
			$this->public
		);		

		$out .= sprintf(
			"<link href=\"%scss/web/fonts.css\" as=\"style\" rel=\"preload\" onload=\"this.onload=null;this.rel='stylesheet'\" />\n", 
			$this->public
		);
		
		$out .= sprintf(
			"<link href=\"%scss/web/style3.css\" rel=\"stylesheet\" />\n", 
			$this->public
		);

		if(isset($_SESSION['LANG']) && $_SESSION['LANG']=="en"){
			$out .= "<link href=\"https://fonts.googleapis.com/css?family=Roboto\" rel=\"stylesheet\" />";   
			$out .= sprintf(
				"<link href=\"%scss/web/en.css\" as=\"style\" rel=\"preload\" onload=\"this.onload=null;this.rel='stylesheet'\" />\n", 
				$this->public
			);   
		}

		// fb plugin (should be in body, not head)
		$out .= "</head>\n";
		$out .= "<body class=\"page-homepage-carousel\">\n";
		// $out .= "<div id=\"fb-root\"></div>\n";
		// $out .= "<script>(function(d, s, id) {
		// var js, fjs = d.getElementsByTagName(s)[0];
		// if (d.getElementById(id)) return;
		// js = d.createElement(s); js.id = id;
		// js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=1388834851138933&autoLogAppEvents=1';
		// fjs.parentNode.insertBefore(js, fjs);
		// }(document, 'script', 'facebook-jssdk'));</script>\n";
		
		
		return $out;
	}
}