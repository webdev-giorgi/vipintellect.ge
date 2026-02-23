<?php 
class _usefulllink2
{
	public $data;
	public $column;

	public function index()
	{
		$out = "";
		require_once("app/functions/string.php"); 
		require_once("app/functions/strip_output.php"); 
		$string = new functions\string(); 
		$out .= "<ul class=\"usefullLinks\">";
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
					$image = strip_output::index($pic[0]['path']);
				}else{
					$image = "/public/filemanager/noimage.png";
				}
				$out .= "<li>\n";
				$out .= sprintf(
					"<a href=\"%s\" class=\"waves-effect waves-light\" target=\"_blank\">\n", 
					strip_output::index($value['url'])
				);
				$out .= sprintf(
					"<img src=\"%s\" alt=\"\" />\n", 
					$image 
				);
				// $out .= sprintf("<div title=\"%s\">%s</div>\n", $value['title'], $string->cut($value['title'],14));
				$out .= sprintf("<div title=\"%s\">%s</div>\n", strip_output::index($value['title']), $string->cut(strip_tags($value['title']),30));
				$out .= "</a>\n";
				$out .= "</li>\n";					
			}
		}
		$out .= "</ul>";
		
		return $out;
	}
}