<?php 
class _miniinternationalsupport
{
	public $data;

	public function index()
	{
		require_once("app/functions/strip_output.php");
		$out = "";
		if(count($this->data)){
			foreach($this->data as $value) {
				$photos = new Database("photos",array(
					"method"=>"selectByParent", 
					"idx"=>(int)$value['idx'],  
					"lang"=>strip_output::index($value['lang']),  
					"type"=>strip_output::index($value['type']) 
				));
				if($photos->getter()){
					$pic = $photos->getter();
					$image = sprintf(
						"%s%s/image/loadimage?f=%s%s&w=230&h=50",
						Config::WEBSITE,
						strip_output::index($_SESSION['LANG']),
						Config::WEBSITE_,
						strip_output::index($pic[0]['path'])
					);
				}else{
					$image = "/public/filemanager/noimage.png";
				}

				$out .= "<section class=\"col s12 m6 l3 InternationalSupport\">";
				
				$title = strip_tags($value['title']);
				$links = str_replace(array(" "), array("-"), $title);

				$out .= sprintf(
					"<a href=\"%s%s/international-support/%s/%s\">",
					Config::WEBSITE,
					strip_output::index($_SESSION['LANG']),
					(int)$value['idx'],
					urlencode($links) 
				);
				$out .= "<section class=\"box\" style=\"min-height: auto\">";			
				$out .= sprintf(
					"<img src=\"%s\" alt=\"\" />", 
					$image
				);			
				$out .= "</section>";			
				$out .= "</a>";			
				$out .= "</section>";			
			}
		}		

		return $out;
	}
}