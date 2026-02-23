<?php 
class deleteParentModuleForm
{
	public $out; 

	public function __construct()
	{
		require_once 'app/core/Config.php';
		if(!isset($_SESSION[Config::SESSION_PREFIX."username"]))
		{
			exit();
		}
	}
	
	public function index(){
		require_once 'app/core/Config.php';
		require_once 'app/functions/makeForm.php';
		require_once 'app/functions/request.php';
		require_once 'app/functions/strings.php';

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>"მოხდა შეცდომა !",
				"Details"=>"!"
			)
		);

		$lang = functions\request::index("POST","lang");
		$_SESSION["LANG"] = $lang;

		$form = functions\makeForm::open(array(
			"action"=>"?",
			"method"=>"post",
			"id"=>"",
			"id"=>"",
		));

		$Database = new Database("modules", array(
			"method"=>"selectParentUsefull"
		));
		$output = $Database->getter();
		$options = array();
		if($output){
			foreach ($output as $v) {
				$options[$v['idx']] = $v['title'];
			}
		}

		$form .= functions\makeForm::select(array(
			"id"=>"chooseParentModule",
			"choose"=>"აირჩიეთ მოდული",
			"options"=>$options,
			"selected"=>"false",
			"disabled"=>"false"
		));

		$form .= functions\makeForm::close();

		
		$this->out = array(
			"Error" => array(
				"Code"=>0, 
				"Text"=>"ოპერაცია შესრულდა წარმატებით !",
				"Details"=>""
			),
			"form" => $form,
			"attr" => "formParentModuleDelete('".$lang."')"
		);



		return $this->out;
	}
}