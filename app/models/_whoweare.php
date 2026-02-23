<?php 
class _whoweare
{
	public $data;

	public function index()
	{
		$out = "";
		require_once("app/functions/string.php"); 
		require_once("app/functions/strip_output.php"); 
		$string = new functions\string(); 

		if(count($this->data))
		{
			foreach($this->data as $value)
			{
				$photos = new Database("photos",array(
					"method"=>"selectByParent", 
					"idx"=>(int)$value['idx'],  
					"lang"=>strip_output::index($value['lang']),  
					"type"=>strip_output::index($value['type'])
				));
				if($photos->getter()){
					$pic = $photos->getter();
					$image = sprintf(
						"%s%s/image/loadimage?f=%s%s&w=230&h=230",
						Config::WEBSITE,
						strip_output::index($_SESSION['LANG']),
						Config::WEBSITE_,
						strip_output::index($pic[0]['path'])
					);
				}else{
					$image = Config::WEBSITE_."/public/filemanager/noimage.png";
				}

				$out .= "<article class=\"what-we-are_item\">";
   				$out .= " <div class=\"what-we-are_img-box\">";
   				$out .= sprintf(
   					"<img src=\"%s\" alt=\"\" class=\"what-we-are_img\" />",
   					$image
   				);
   				$out .= "</div>";



   				$out .= "<div class=\"what-we-are_content\">";
   				$out .= sprintf(
   					"<h2 class=\"what-we-are_title\">%s</h2>", 
   					strip_tags($value['title'])
   				);
   				$out .= sprintf(
   					"<p class=\"what-we-are_job\">%s</p>",
   					strip_tags($value['classname'])
   				);
   				$out .= "<p class=\"what-we-are_text\">";
   				$out .= strip_tags($value['description']);
   				$out .= "</p>";
   				
   				$out .= "</div>";
   				$out .= "</article>";
					
			}
		}
		
		return $out;
	}
}