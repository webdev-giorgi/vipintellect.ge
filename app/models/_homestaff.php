<?php 
class _homestaff
{
	public $data;

	public function index()
	{
		require_once("app/functions/strings.php");
		require_once("app/functions/l.php");
		require_once("app/functions/getWebpUrl.php"); 
		$getWebpUrl = new functions\getWebpUrl(); 

		$l = new functions\l();
		
		$out = "";

		if(count($this->data)){			
			foreach($this->data as $item) {
				$title = strip_tags($item['title']);
				$titleUrl = str_replace(array(" ", "'", '"'), "-", $title);

				$photos = new Database("photos",array(
					"method"=>"selectByParent", 
					"idx"=>(int)$item['idx'],  
					"lang"=>strip_output::index($_SESSION['LANG']),  
					"type"=>strip_output::index($item['type'])
				));
				

                $out .= "<article class=\"professor-thumbnail glakho\">";
                $out .= "<figure class=\"professor-image\">";
                $out .= sprintf(
                	"<a href=\"%s%s/staff/%s/%s\" aria-label=\"%s\">",
                	Config::WEBSITE,
					strip_output::index($_SESSION['LANG']),
					(int)$item['idx'],
					strip_output::index($titleUrl),
					htmlentities($title)
                );

                if($photos->getter()){
					$pic = $photos->getter();
					$imageUrl = sprintf(
						"%s%s",
						Config::WEBSITE_,
						strip_output::index($pic[0]['path'])
					);

					$image = $getWebpUrl->index($imageUrl, array(80, 80));
					$out .= sprintf(
						"<img src=\"%s\" alt=\"\" width=\"80\" height=\"80\" style=\"object-fit: contain\" />", 
						$image
					);
				}

                $out .= "</a>";
                $out .= "</figure>";
                $out .= "<aside>";
                $out .= "<header>";
                $out .= sprintf(
                	"<a href=\"%s%s/staff/%s/%s\">%s</a>",
                	Config::WEBSITE,
					strip_output::index($_SESSION['LANG']),
					(int)$item['idx'],
					strip_output::index($titleUrl),
					$title
                );
                $out .= "<div class=\"divider\"></div>";
                $out .= sprintf(
                	"<figure class=\"professor-description\">%s</figure>",
                	$item['classname']
                );
                $out .= "</header>";
                $out .= sprintf(
                	"<a href=\"%s%s/staff/%s/%s\" class=\"show-profile\">%s</a>",
                	Config::WEBSITE,
					strip_output::index($_SESSION['LANG']),
					(int)$item['idx'],
					strip_output::index($titleUrl),
                	$l->translate("viewprofile")
                );
                $out .= "</aside>";
                $out .= "<div style=\"clear:both\"></div>";
                $out .= "</article>";
				
			}

		}
		return $out;
	}
}