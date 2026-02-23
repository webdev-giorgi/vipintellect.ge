<?php 
class _legislation
{
	public $data;

	public function index()
	{
		require_once("app/functions/timeleft.php"); 
		require_once("app/functions/l.php"); 
		require_once("app/functions/strip_output.php");
		$l = new functions\l(); 
		$out = "";
		if(count($this->data)){
			$out .= "<ul class=\"collapsible\" data-collapsible=\"accordion\">\n";
			foreach($this->data as $value) {
				$fileTree = "";
				$file = new Database("file", array(
					"method"=>"selectFilesByPageId",  
					"page_id"=>(int)$value['idx'],  
					"lang"=>strip_output::index($_SESSION['LANG']),  
					"type"=>"module"
				));

				if($file->getter()){
					$fileTree .= "<section class=\"fileTree\">\n";
					$fileTree .= "<ul>\n";
					foreach ($file->getter() as $f) {
						$explode = explode("/", $f['file_path']); 
						$fileName = end($explode);

						$fileTree .= "<li>\n";	
						$fileTree .= "<div class=\"icon\"></div>\n";	
						$fileTree .= sprintf(
							"<div class=\"text\"><a href=\"%s%s\" target=\"_blank\">%s</a></div>\n",
							Config::PUBLIC_FOLDER,
							$f['file_path'], 
							$fileName
						);	
						$fileTree .= sprintf(
							"<div class=\"rightSide\"><i class=\"penIcon height30 cursorPointer\" onclick=\"openComment('c%s','commentForm%s')\"></i></div>\n",
							(int)$f['idx'], 
							(int)$value['idx'] 
						);	
						$fileTree .= "<div class=\"line\"></div>\n";

						$fileTree .= "</li>\n";	
					}
					$fileTree .= "</ul>\n";	
					$fileTree .= "<div class=\"clearer\"></div>\n";	
					$fileTree .= "</section>\n";	
				}



				$out .= "<li>\n";
				$out .= sprintf(
					"<div class=\"collapsible-header\" id=\"open%s\"><i class=\"blueArraw-icon\"></i><div>%s</div><p style=\"clear:both\"></p></div>\n",
					(int)$value['idx'],
					strip_tags($value['title'])
				);
				$out .= "<div class=\"collapsible-body\">\n";
				$out .= "<div class=\"hideShadow\"></div>\n";
				$out .= $fileTree;
				
				

				$out .= "<section class=\"padding20\">\n";
				$out .= sprintf(
					"%s\n",
					strip_output::index($value['description'])
				);
				
				$endtime = $value['date'] + 604800;
				$timeleft = functions\timeleft::index($endtime); 

				$out .= sprintf(
					"<section class=\"col s12 m8 l8 commentForm%s\" style=\"display: none; margin-top:20px\">", 
					(int)$value['idx']
				);
				if($endtime>time()){					
					$out .= sprintf(
						"<section class=\"justTitle\" style=\"color:#3c3c3c;\">%s</section><br>", 
						$l->translate('youcanleavecommenthere')
					);
					
					$out .= sprintf(
						"<section class=\"timeLeft\">* %s  <span>%s</span></section>",
						$l->translate('youcanleavecommenthere'), 
						$timeleft
					);
					$out .= "<section class=\"contactForm\">";
					$out .= "<form action=\"\" method=\"post\">";
					
					require_once("app/functions/strings.php"); 
					$_SESSION['protect_x'] = functions\string::random(6);
					$out .= sprintf(
						"<input type=\"hidden\" name=\"csrf\" id=\"csrf\" class=\"csrf\" value=\"%s\">", 
						$_SESSION['protect_x']
					);

					$out .= sprintf(
						"<div class=\"commMsG commentForm%s_msg\" style=\"padding:0 0 20px 0\"></div>",
						(int)$value['idx']
					);
					$out .= "<input type=\"hidden\" name=\"commentId\" class=\"commentId\" value=\"c1\">";
					$out .= "<section class=\"marginminus10\">";
					$out .= "<div class=\"input-field col s12 m6 l4\">";
					$out .= "<input type=\"text\" class=\"validate first_name\">";
					$out .= sprintf(
						"<label>%s</label>", 
						$l->translate('name')
					);
					$out .= "</div>";
					$out .= "<div class=\"input-field col s12 m6 l4\">";
					$out .= "<input type=\"text\" class=\"validate organization\">";
					$out .= sprintf(
						"<label>%s</label>",
						$l->translate('organization')
					);
					$out .= "</div>";
					$out .= "<div class=\"input-field col s12 m6 l4\">";
					$out .= "<input type=\"text\" class=\"validate email\">";
					$out .= sprintf(
						"<label>%s</label>", 
						$l->translate('email')
					);
					$out .= "</div>";

					$out .= "<div class=\"input-field col s12 m12 l12\">";
					$out .= "<input type=\"text\" class=\"validate comment\">";
					$out .= sprintf(
						"<label>%s</label>",
						$l->translate('comment')
					);
					$out .= "</div>";

					$out .= "<div class=\"col s12 m12 l12\">";
					$out .= sprintf(
						"<a class=\"waves-effect waves-light btn submit\" style=\"text-decoration: none;\" onclick=\"comment('commentForm%s','%s')\">%s</a>",
						(int)$value['idx'],
						strip_output::index($_SESSION['LANG']), 
						$l->translate('submit')
					);
					$out .= "</div>";
					$out .= "</section>";
					$out .= "</form>";
					$out .= "</section>";
					
				}else{
					$out .= sprintf(
						"<section class=\"justTitle\" style=\"color:#3c3c3c;\">%s</section><br>", 
						$l->translate('commentTimeOver')
					);
				}

				$out .= "</section>";

				$out .= "<div class=\"clearer\"></div>";
				$out .= "</section>";



				$out .= "</div>\n";
				$out .= "</li>\n";
			}
			$out .= "</ul>\n";
		}

		return $out;
	}
}