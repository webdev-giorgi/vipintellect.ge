<?php 
class _staff
{
	public $data; 

	public function index()
	{
		require_once("app/functions/strings.php"); 
		require_once("app/functions/l.php"); 
		require_once("app/functions/strip_output.php");

		$l = new functions\l(); 
		$sting = new functions\strings();
		$out = "";

		$return["count"] = (isset($this->data[0]['count'])) ? $this->data[0]['count'] : 0;
		if($return["count"]){
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
						"%s%s/image/loadimage?f=%s%s&w=160&h=160",
						Config::WEBSITE,
						strip_output::index($_SESSION['LANG']),
						Config::WEBSITE_,
						strip_output::index($pic[0]['path'])
					);
				}else{
					$image = "/public/filemanager/noimage.png";
				}
				$title = strip_tags($value['title']);
				$titleUrl = str_replace(array(" ", "'", '"'), "-", $title); 

				$out .= "<div class=\"author-block course-speaker\">";
				$out .= sprintf(
					"<a href=\"%s%s/staff/%s/%s\">",
					Config::WEBSITE,
					strip_output::index($_SESSION['LANG']),
					(int)$value['idx'],
					strip_output::index($titleUrl)
				);
				$out .= "<figure class=\"author-picture\">";
				$out .= sprintf(
					"<img src=\"%s\" alt=\"\" />",
					$image
				);
				$out .= "</figure>";
				$out .= "</a>";
				$out .= "<article class=\"paragraph-wrapper glakho\">";
				$out .= "<div class=\"inner\">";
				$out .= sprintf(
					"<header><a href=\"%s%s/staff/%s/%s\">%s</a></header>",
					Config::WEBSITE,
					strip_output::index($_SESSION['LANG']),
					(int)$value['idx'],
					strip_output::index($titleUrl), 
					$title
				);
				$out .= sprintf("<figure>%s</figure>", $value['classname']);
				$out .= sprintf("<p>%s</p>", $sting->cut($value['description'], 100));
				$out .= sprintf(
					"<a href=\"%s%s/staff/%s/%s\" class=\"btn btn-framed btn-small btn-color-grey\">%s</a>",
					Config::WEBSITE,
					strip_output::index($_SESSION['LANG']),
					(int)$value['idx'],
					strip_output::index($titleUrl),
					$l->translate("viewprofile")
				);
				$out .= "</div>";
				
				$out .= "</article>";
				$out .= "</div>";
			}
		}		
		$return["html"] = $out;
		return $return; 
	}
}