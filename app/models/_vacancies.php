<?php
class _vacancies
{
	public $data;
	public function index()
	{
		require_once("app/functions/strings.php");
		require_once("app/functions/l.php");
		require_once("app/functions/strip_output.php");
		$month = array(
			"ge"=>array(
				"Jan"=>"იან",
				"Feb"=>"თებ",
				"Mar"=>"მარ",
				"Apr"=>"აპრ",
				"May"=>"მაი",
				"Jun"=>"ივն",
				"Jul"=>"ივლ",
				"Aug"=>"აგვ",
				"Sep"=>"სექ",
				"Oct"=>"ოქტ",
				"Nov"=>"ნოე",
				"Dec"=>"დეკ"
			),
			"en"=>array(
				"Jan"=>"Jan",
				"Feb"=>"Feb",
				"Mar"=>"Mar",
				"Apr"=>"Apr",
				"May"=>"May",
				"Jun"=>"Jun",
				"Jul"=>"Jul",
				"Aug"=>"Aug",
				"Sep"=>"Sep",
				"Oct"=>"Oct",
				"Nov"=>"Nov",
				"Dec"=>"Dec"
			)
		);
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
						"%s%s/image/loadimage?f=%s%s&w=360&h=220",
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
				$out .= "<article class=\"event glakho\">";
				$out .= "<figure class=\"date\" style=\"background-color: #ff0000;\">";
				$out .= sprintf(
					"<div class=\"month\">%s</div>",
					$month[$_SESSION['LANG']][date("M", (int)$value['date'])]
				);
				$out .= sprintf(
					"<div class=\"day\">%s</div>",
					date("d", (int)$value['date'])
				);
				$out .= "</figure>";
				$out .= "<aside>";
				$out .= "<header>";
				$out .= sprintf(
					"<a href=\"%s%s/vacancies/%s/%s\">%s</a>",
					Config::WEBSITE,
					strip_output::index($_SESSION['LANG']),
					(int)$value['idx'],
					strip_output::index($titleUrl),
					$title
				);
				$out .= "</header>";
				$out .= "<div class=\"additional-info\">";
				$out .= sprintf(
					"<span class=\"fa fa-briefcase\"></span> %s",
					$value['classname']
				);
				$out .= "</div>";
				$out .= "<div class=\"description\">";
				$out .= sprintf(
					"<p>%s</p>",
					$sting->cut($value['description'], 160)
				);
				$out .= "</div>";
				$out .= sprintf(
					"<a href=\"%s%s/vacancies/%s/%s\" class=\"btn btn-framed btn-color-grey btn-small glakho\">%s</a>",
					Config::WEBSITE,
					strip_output::index($_SESSION['LANG']),
					(int)$value['idx'],
					strip_output::index($titleUrl),
					$l->translate("more")
				);
				$out .= "</aside>";
				$out .= "</article>";
			}
		}
		$return["html"] = $out;
		return $return;
	}
}