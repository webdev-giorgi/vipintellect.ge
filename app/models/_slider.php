<?php 
class _slider
{
	public $data;

	public function index()
	{
		require_once("app/functions/strip_output.php"); 
		
		require_once("app/functions/webp.php"); 
		$webp = new functions\webp(); 

		require_once("app/functions/getWebpUrl.php"); 
		$getWebpUrl = new functions\getWebpUrl(); 
		

		$out = array();
		$out["list"] = "";
		$out["count"] = 0;
		
		$out["count"] = count($this->data);

		if($out["count"])
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
					$image = strip_output::index($pic[0]['path']);
				}else{
					$image = "/public/filemanager/noimage.png";
				}	

				// $imageUrl = $getWebpUrl->index(Config::WEBSITE_ . $image, array(555, 320));
				$imageUrl = $webp->index(Config::WEBSITE_ . $image, 3);

				// $out["list"] .= $imageUrl."zzz";
				
                $out["list"] .= "<div class=\"image-carousel-slide\">";
                $out["list"] .= sprintf(
                	"<img src=\"%s\" alt=\"\" rel=\"preload\" as=\"image\" fetchpriority=\"high\" width=\"555\" height=\"320\" style=\"width:555px; height:320px; object-fit:cover;\" />",
					$imageUrl
                );
                $out["list"] .= "</div>";

				// $out["list"] .= sprintf(
				// 	"<a href=\"%s\" class=\"item%s\" style=\"background-image: url('%s%s/image/loadimage?f=%s%s&w=1350&h=500');\"></a>",
				// 	$value['url'],
				// 	$active,
				// 	Config::WEBSITE,
				// 	$_SESSION["LANG"],
				// 	Config::WEBSITE_,
				// 	$image
				// );
			}
		}
		
		return $out;
	}
}