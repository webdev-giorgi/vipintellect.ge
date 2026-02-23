<?php 
class _myaccountnav
{
	public $data;

	public function index()
	{
		require_once("app/functions/strip_output.php");
		require_once("app/functions/request.php");
		require_once("app/functions/l.php");
		$l = new functions\l();
		
		$out = "<section class=\"list-group\"\n>";

		if(count($this->data)){
			$view = functions\request::index("GET", "view");
			foreach($this->data as $value) {
				$active = ($view==$value["slug"]) ? " active" : "";

				$out .= sprintf(
					"<a href=\"%s\" class=\"list-group-item%s\">\n",
					"?view=".$value["slug"], 
					$active
				);
				$out .= sprintf(
					"<i class=\"%s\" aria-hidden=\"true\"></i>\n",
					$value["icon"]
				);
				$out .= sprintf(
					"<span>%s</span>\n",
					$value["title"]
				);
				$out .= "</a>\n";
			}

		}
		$out .= "<a href=\"javascript:void(0)\" class=\"list-group-item myaccount-signout\">";
		$out .= "<i class=\"fa fa-sign-out\" aria-hidden=\"true\"></i>";
		$out .= sprintf(
			"<span>%s</span>",
			$l->translate("signout")
		);
		$out .= "</a>";

		$out .= "</section>\n";
		return $out;
	}
}