<?php 
class _homevacancies
{
	public $data;

	public function index()
	{
		require_once("app/functions/strings.php");
		require_once("app/functions/l.php");
		$l = new functions\l();
		
		$out = "";

		if(count($this->data)){
			
			foreach($this->data as $item) {
				$title = strip_tags($item['title']);
				$titleUrl = str_replace(array(" ", "'", '"'), "-", $title);

                $out .= "<article class=\"event nearest\">";
                $out .= "<aside style=\"padding: 0\">";
                $out .= "<header>";
                $out .= sprintf(
                	"<a href=\"%s%s/vacancies/%s/%s\">%s</a>",
                	Config::WEBSITE,
					strip_output::index($_SESSION['LANG']),
					(int)$item['idx'],
					strip_output::index($titleUrl), 
					$title
                );
                $out .= "</header>";
                $out .= sprintf(
                	"<div class=\"additional-info\">%s</div>",
                	$item["classname"]
                );
                $out .= "</aside>";
                $out .= "</article>";				
			}

		}
		return $out;
	}
}