<?php 
namespace functions;

class breadcrups
{
	public $list;
	public $main;
	public function __construct()
	{

	}

	public function index()
	{
		require_once("app/functions/l.php");
		$l = new l();
		$out = "";
		$page = new \Database("page", array(
			"method"=>"selecteBySlug",
			"slug"=>$_SESSION["URL"][1], 
			"lang"=>$_SESSION["LANG"],
			"all"=>true
		));
		$getter = $page->getter();
		$idx = $getter["idx"];
		$title = $getter["title"];
		$slug = $getter["slug"];


		$array[] = array(
			"idx"=>$getter["idx"],
			"cid"=>$getter["cid"],
			"title"=>$getter["title"],
			"slug"=>$getter["slug"],
			"sub"=>$this->select($getter["cid"])
		);

		foreach ($array as $item) {
			$this->list[] = sprintf(
				"<li><a href=\"/%s/%s\">%s</a></li>",
				$_SESSION["LANG"],
				$item["slug"],
				$item["title"]
			);
			if(count($item["sub"])){
				$this->sub($item["sub"]);
			}else{

			}
		}

		$reversed = array_reverse($this->list);

		$out = "<ol class=\"breadcrumb glakho\">";
		if(!$this->main){
			$out .= sprintf(
				"<li><a href=\"/\">%s</a></li>",
				$l->translate("main")
			);
		}
		$out .= implode("", $reversed);
		$out .= "</ol>";

		return $out;
	}

	private function sub($array)
	{
		if(isset($array["idx"]) && is_array($array["idx"]) && count($array["idx"])){
			$this->list[] = sprintf(
				"<li><a href=\"/%s/%s\">%s</a></li>",
				$_SESSION["LANG"],
				$array["slug"],
				$array["title"]
			);
			if($array["slug"]=="home"){
				$this->main = true;
			}
			if(isset($array["sub"]) && is_array($array["sub"]) && count($array["sub"])){
				$this->sub($array["sub"]);
			}
		}
	}

	private function select($cid)
	{
		$page = new \Database("page", array(
			"method"=>"selectById",
			"idx"=>$cid, 
			"lang"=>$_SESSION["LANG"]
		));
		$getter = $page->getter();
		//echo $getter[0]["idx"];
		$array = array();
		if(isset($getter["idx"])){		

			$array = array(
				"idx"=>$getter["idx"],
				"cid"=>$getter["cid"],
				"title"=>$getter["title"],
				"slug"=>$getter["slug"],
				"sub"=>$this->select($getter["cid"])
			);
		}
		return $array;
	}
}