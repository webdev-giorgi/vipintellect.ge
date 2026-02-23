<?php 
class _contactinfo
{
	public $data;

	public function index()
	{
		require_once("app/functions/strip_output.php");
		
		$out = "";

		if(count($this->data)){
			$out = "<ul>";
			foreach($this->data as $value) {
				$out .= sprintf(
					"<li><i class=\"%s\" aria-hidden=\"true\"></i> %s</li>\n",
					htmlentities($value['classname']),
					strip_tags($value['description'])
				);
			}
			$out .= "</ul>";
		}
		return $out;
	}
}