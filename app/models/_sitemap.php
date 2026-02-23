<?php 
class _sitemap
{
	public $data;

	public function index()
	{
		require_once("app/functions/strings.php");
		require_once("app/functions/strip_output.php"); 
		$string = new functions\string(); 

		$out = "";

		/* NAVIGATION START */
		if(count($this->data)){
			foreach($this->data as $v1) {
				$out .= "<url>\n";
				$out .= sprintf(
					"<loc>%s%s/%s</loc>\n",
					Config::WEBSITE,
					$_SESSION["LANG"],
					$v1["slug"]
				);
				$out .= sprintf(
					"<lastmod>%sT%s+04:00</lastmod>\n",
					date("Y-m-d", $v1["date"]),
					date("H:i:s", $v1["date"])
				);
				$out .= "<priority>1.00</priority>\n";
				$out .= "</url>\n\n";

				$navigation2 = new Database("page", array(
					"method"=>"select", 
					"cid"=>$v1["idx"], 
					"nav_type"=>0,
					"lang"=>$_SESSION['LANG'],
					"status"=>0 
				));
				$fetch2 = $navigation2->getter();

				if(count($fetch2)){
					foreach ($fetch2 as $v2) {
						$out .= "<url>\n";
						$out .= sprintf(
							"<loc>%s%s/%s</loc>\n",
							Config::WEBSITE,
							$_SESSION["LANG"],
							$v2["slug"]
						);
						$out .= sprintf(
							"<lastmod>%sT%s+04:00</lastmod>\n",
							date("Y-m-d", $v2["date"]),
							date("H:i:s", $v2["date"])
						);
						$out .= "<priority>0.90</priority>\n";
						$out .= "</url>\n\n";

						$navigation3 = new Database("page", array(
							"method"=>"select", 
							"cid"=>$v2["idx"], 
							"nav_type"=>0,
							"lang"=>$_SESSION['LANG'],
							"status"=>0 
						));
						$fetch3 = $navigation3->getter();

						if(count($fetch3)){
							foreach ($fetch3 as $v3) {
								$out .= "<url>\n";
								$out .= sprintf(
									"<loc>%s%s/%s</loc>\n",
									Config::WEBSITE,
									$_SESSION["LANG"],
									$v3["slug"]
								);
								$out .= sprintf(
									"<lastmod>%sT%s+04:00</lastmod>\n",
									date("Y-m-d", $v3["date"]),
									date("H:i:s", $v3["date"])
								);
								$out .= "<priority>0.80</priority>\n";
								$out .= "</url>\n\n";


								$navigation4 = new Database("page", array(
									"method"=>"select", 
									"cid"=>$v3["idx"], 
									"nav_type"=>0,
									"lang"=>$_SESSION['LANG'],
									"status"=>0 
								));
								$fetch4 = $navigation4->getter();

								if(count($fetch4)){
									foreach ($fetch4 as $v4) {
										$out .= "<url>\n";
										$out .= sprintf(
											"<loc>%s%s/%s</loc>\n",
											Config::WEBSITE,
											$_SESSION["LANG"],
											$v4["slug"]
										);
										$out .= sprintf(
											"<lastmod>%sT%s+04:00</lastmod>\n",
											date("Y-m-d", $v4["date"]),
											date("H:i:s", $v4["date"])
										);
										$out .= "<priority>0.70</priority>\n";
										$out .= "</url>\n\n";
									}
								}
							}
						}
					}
				}
			}
		}
		/* NAVIGATION END */

		return $out;
	}
}