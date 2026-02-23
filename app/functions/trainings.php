<?php 
namespace functions;

class trainings
{
	public function index()
	{
		require_once("app/functions/l.php"); 
		$l = new l(); 

		$Database = new \Database("page", array(
			"method"=>"selecteByCid", 
			"cid"=>7, 
			"lang"=>$_SESSION["LANG"] 
		));
		$output = $Database->getter();
		
		$out = "<select name=\"trainingid\" id=\"trainingid\" class=\"has-dark-background glakho\">\n";

		$out .= sprintf(
			"<option value=\"\">%s</option>",
			$l->translate("choosetraining")
		);

		foreach ($output as $item1) {// level 1
			$Database2 = new \Database("page", array(
				"method"=>"selecteByCid", 
				"cid"=>$item1['idx'], 
				"lang"=>$_SESSION["LANG"] 
			));
			$output2 = $Database2->getter();

			if(count($output2)){//has sub
				$out .= sprintf(
					"<optgroup label=\"%s\">\n",
					$item1["title"]
				);

				foreach ($output2 as $item2) {// level 2
					$Database3 = new \Database("page", array(
						"method"=>"selecteByCid", 
						"cid"=>$item2['idx'], 
						"lang"=>$_SESSION["LANG"] 
					));
					$output3 = $Database3->getter();

					

					if(count($output3)){
						$selected = (isset($_GET["r"]) && $_GET["r"]==$item2["slug"]) ? ' selected="selected"' : '';
						$out .= sprintf(
							"<option value=\"%d\"%s>%s</option>\n",
							$item2["idx"],
							$selected,
							$item2["title"]
						);
						foreach ($output3 as $item3) {// level 3
							$Database4 = new \Database("page", array(
								"method"=>"selecteByCid", 
								"cid"=>$item3['idx'], 
								"lang"=>$_SESSION["LANG"] 
							));
							$output4 = $Database4->getter();

							if(count($output4)){
								foreach ($output4 as $item4) {// level 4
									$selected = (isset($_GET["r"]) && $_GET["r"]==$item4["slug"]) ? ' selected="selected"' : '';
									$out .= sprintf(
										"<option value=\"%d\"%s>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%s</option>\n",
										$item4["idx"],
										$selected,
										$item4["title"]
									);
								}
							}else{
								$selected = (isset($_GET["r"]) && $_GET["r"]==$item3["slug"]) ? ' selected="selected"' : '';
								$out .= sprintf(
									"<option value=\"%d\"%s>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%s</option>\n",
									$item3["idx"],
									$selected,
									$item3["title"]
								);
							}
						}
					}else{
						$selected = (isset($_GET["r"]) && $_GET["r"]==$item2["slug"]) ? ' selected="selected"' : '';
						$out .= sprintf(
							"<option value=\"%d\"%s>%s</option>\n",
							$item2["idx"],
							$selected,
							$item2["title"]
						);
					}
				}

				$out .= "</optgroup>\n";
			}else{//no sub
				$selected = (isset($_GET["r"]) && $_GET["r"]==$item2["slug"]) ? ' selected="selected"' : '';
				$out .= sprintf(
					"<option value=\"%d\"%s>%s</option>\n",
					$item1["idx"],
					$selected,
					$item1["title"]
				);
			}
		}

		$out .= "</select>\n";

		return $out;
	}
}