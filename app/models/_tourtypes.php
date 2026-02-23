<?php 
class _tourtypes
{
	public $data;

	public function options()
	{
		require_once("app/functions/strip_output.php");
		
		$options = "";

		if(count($this->data)){
			
			foreach($this->data as $value) {
				$options .= sprintf(
					"<option value=\"%d\">%s</option>",
					$value['idx'],
					$value['title']
				);
			}

		}
		return $options;
	}
}