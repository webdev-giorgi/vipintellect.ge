<?php 
class addtasks
{
	public $out; 

	public function index()
	{
		require_once 'app/core/Config.php';
		require_once 'app/functions/request.php';

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>"მოხდა შეცდომა !",
				"Details"=>"!"
			)
		);

		$title = functions\request::index("POST","title");
		$description = functions\request::index("POST","description");
		$type = functions\request::index("POST","type");


		if($title=="" || $description=="" || $type=="")
		{
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"მოხდა შეცდომა !",
					"Details"=>"!"
				)
			);
		}else{
			$Insert = new Database('tasks', array(
				"method"=>"add", 
				"title"=>$title,
				"description"=>$description,
				"type"=>$type
			));

			$Database = new Database('tasks', array(
				'method'=>'select', 
				'status'=>1
			));

			$fetch = $Database->getter();
			
			$this->out = array(
				"Error" => array(
					"Code"=>0, 
					"Text"=>"",
					"Details"=>""
				),
				"Success"=>array(
					"Code"=>1, 
					"Text"=>"ოპერაცია შესრულდა წარმატებით !",
					"Table"=>$fetch
				)
			);
			
		}

		return $this->out;
	}
}