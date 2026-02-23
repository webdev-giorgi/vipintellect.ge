<?php 
class _publications2
{
	public $data;

	public function index()
	{
		require_once("app/functions/files.php"); 
		require_once("app/functions/string.php"); 
		require_once("app/functions/strip_output.php");

		$sting = new functions\string();
		
		$out = "\n";
		
		if(count($this->data)){
			foreach($this->data as $value) {
				$file = new Database("file", array(
					"method"=>"selectFilesByPageId",  
					"page_id"=>(int)$value['idx'],  
					"lang"=>strip_output::index($value['lang']),
					"type"=>"module"
				));

				if($file->getter()){
					$f = $file->getter();
					$theFile = Config::PUBLIC_FOLDER.$f[0]['file_path'];
					$size = $f[0]['file_size'];
				}else{
					$theFile = "";
					$size = 0;
				}

				$out .= sprintf(
					"<section class=\"file\" title=\"%s\">\n", 
					strip_output::index($value['title'])
				);
				$out .= sprintf(
					"<a href=\"%s\" target=\"_blank\">\n", 
					strip_output::index($theFile)
				);
				$out .= "<p class=\"pdfIcon\"></p>\n";
				$out .= "<p class=\"downloadIcon\"></p>\n";
				$out .= sprintf(
					"<p class=\"title\"><span>%s</span><br /><b>%s</b></p>\n", 
					$sting->cut(strip_tags($value['title']),40),
					functions\files::formatSizeUnits($size) 
				);
				$out .= "</a>";
				$out .= "</section>";
			}
		}
		return $out;
	}
}