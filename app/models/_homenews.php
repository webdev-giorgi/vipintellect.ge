<?php 
class _homenews
{
	public $data;

	public function index()
	{
		require_once("app/functions/strings.php");
		require_once("app/functions/l.php");
		$l = new functions\l();
		
		$out = "";

		if(count($this->data)){
			$month = array("ge"=>array("Jan"=>"იან", "Feb"=>"თებ", "Mar"=>"მარ", "Apr"=>"აპრ", "May"=>"მაი", "Jun"=>"ივნ", "Jul"=>"ივლ", "Aug"=>"აგვ", "Sep"=>"სექ", "Oct"=>"ოქტ", "Nov"=>"ნოე", "Dec"=>"დეკ"), "en"=>array("Jan"=>"Jan", "Feb"=>"Feb", "Mar"=>"Mar", "Apr"=>"Apr", "May"=>"May", "Jun"=>"Jun", "Jul"=>"Jul", "Aug"=>"Aug", "Sep"=>"Sep", "Oct"=>"Oct", "Nov"=>"Nov", "Dec"=>"Dec"));
			
			foreach($this->data as $item) {
				$str = str_replace(date("M", (int)$item['date']), $month[strip_output::index($_SESSION['LANG'])][date("M", (int)$item['date'])], date("M d, Y", (int)$item['date']));
				$title = strip_tags($item['title']);
				$titleUrl = str_replace(array(" ", "'", '"'), "-", $title);

				$out .= "<article>";
				$out .= sprintf(
					"<figure class=\"date\"><i class=\"fa fa-file-o\"></i>%s</figure>",
					$str
				);
				$out .= "<header>";
				$out .= sprintf(
					"<a href=\"%s%s/news/%s/%s\">%s</a>",
					Config::WEBSITE,
					strip_output::index($_SESSION['LANG']),
					(int)$item['idx'],
					strip_output::index($titleUrl),
					$title
				);
				$out .= "</header>";
				$out .= "</article>";
				
			}

		}
		return $out;
	}
}