<?php 
class _mainevents
{
	public $data;

	public function index()
	{
		require_once("app/functions/strip_output.php");
		$out = ""; 
		if(count($this->data)){
			$ourData = $this->data;
			$photos = new Database("photos",array(
				"method"=>"selectByParent", 
				"idx"=>(int)$ourData['idx'],  
				"lang"=>strip_output::index($_SESSION['LANG']),  
				"type"=>strip_output::index($ourData['type'])
			));
			if($photos->getter()){
				$pic = $photos->getter();
				$image = strip_output::index($pic[0]['path']);
				$out .= sprintf(
					"<img src=\"%s%s/image/loadimage?f=%s%s&w=350&h=218\" width=\"350\" height=\"218\" alt=\"\" align=\"left\" style=\"margin: 0 10px 0px 0px\" />", 
					Config::WEBSITE,
					$_SESSION['LANG'],
					Config::WEBSITE_,
					$image
				);
			}
			$out .= sprintf(
				"<section class=\"justTitle\">%s</section>", 
				strip_output::index($ourData['title'])
			);
			$out .= sprintf(
				"<section class=\"date\"><span>%s</span></section>", 
				date("M d, Y", (int)$ourData['date'])
			);
			$out .= sprintf(
				"<section class=\"mainText\">%s</section>", 
				strip_output::index($ourData['description'])
			);
		}
		return $out;
	}
}