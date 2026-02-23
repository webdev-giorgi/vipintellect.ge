<?php 
class _navigation
{
	public $data;

	public function index(){
		require_once("app/functions/strip_output.php");
		$out = "<nav class=\"collapse navbar-collapse bs-navbar-collapse navbar-right ninoMtavruli\" role=\"navigation\">\n";
		$out .= "<ul class=\"nav navbar-nav\">\n";

		if(count($this->data)){
			foreach($this->data as $value) {
				$subNavigation = new Database('page', array(
					"method"=>"select", 
					"cid"=>(int)$value['idx'], 
					"nav_type"=>0, 
					"lang"=>strip_output::index($value['lang']), 
					"status"=>0
				));
				/* Active main nav start */
				$active = (isset($_SESSION["URL"][1]) && $_SESSION["URL"][1]==$value['slug']) ? "active" : "";
				if(!isset($_SESSION["URL"][1]) && $value['slug']=="home"){
					$active = "active";
				}
				foreach ($subNavigation->getter() as $val) {
					if(isset($_SESSION["URL"][1]) && $_SESSION["URL"][1]==$val['slug']){
						$active = "active";
						break;
					}
				}
				/* Active main nav end */
				
				if($subNavigation->getter()){				
					
					if(isset($value['redirect']) && $value['redirect']!=""){
						$out .= sprintf(
							"<li>\n<a href=\"%s\" class=\"has-child no-link\"><span>%s</span></a>\n",
							$value['redirect'], 
							strip_output::index($value['title'])
						);
					}else{
						// $out .= sprintf(
						// 	"<li class=\"%s\">\n<a href=\"%s%s/%s\" class=\"has-child no-link\"><span>%s</span></a>\n",
						// 	$active,
						// 	Config::WEBSITE,
						// 	strip_output::index($_SESSION['LANG']),
						// 	strip_output::index($value['slug']), 
						// 	strip_output::index($value['title'])
						// );

						$out .= sprintf(
							"<li class=\"%s\">\n<a href=\"#\" class=\"has-child no-link\"><span>%s</span></a>\n",
							$active,
							strip_output::index($value['title'])
						);
					}

					$out .= "<ul class=\"list-unstyled child-navigation\">\n";

					foreach ($subNavigation->getter() as $val) {
						if(isset($val['redirect']) && $val['redirect']!=""){
							$out .= sprintf(
								"<li><a href=\"%s\"><span>%s</span></a></li>\n",
								$val['redirect'], 
								strip_output::index($val['title'])  
							);	
						}else{
							$out .= sprintf(
								"<li><a href=\"%s%s/%s\"><span>%s</span></a></li>\n",
								Config::WEBSITE,
								strip_output::index($_SESSION['LANG']),
								$val['slug'], 
								strip_output::index($val['title'])  
							);	
						}
					}
					$out .= "</ul>\n";

					$out .= "</li>\n";
				}else{
					$active = (isset($_SESSION["URL"][1]) && $_SESSION["URL"][1]==$value['slug']) ? "active" : "";
					if(isset($value['redirect']) && $value['redirect']!=""){
						$out .= sprintf(
							"<li><a href=\"%s\"><span>%s</span></a></li>\n",
							strip_output::index($value['redirect']),
							strip_output::index($value['title'])
						);
					}else{
						$out .= sprintf(
							"<li class=\"%s\"><a href=\"%s%s/%s\"><span>%s</span></a></li>\n",
							$active, 
							Config::WEBSITE,
							strip_output::index($_SESSION['LANG']),
							strip_output::index($value['slug']),
							strip_output::index($value['title'])
						);	
					}
				}
				
			}				
		}			
		$out .= "</ul>\n";
		$out .= "</nav>\n";
		
		return $out;
	}
}