<?php 

class addParentModuleForm

{

	public $out; 



	public function __construct()

	{

		require_once 'app/core/Config.php';

		if(!isset($_SESSION[Config::SESSION_PREFIX."username"]))

		{app/functions/strings.php

			exit();

		}

	}

	

	public function index(){

		require_once 'app/core/Config.php';

		require_once 'app/functions/makeForm.php';

		require_once 'app/functions/request.php';

		require_once 'app/functions/string.php';



		$this->out = array(

			"Error" => array(

				"Code"=>1, 

				"Text"=>"მოხდა შეცდომა !",

				"Details"=>"!"

			)

		);



		$lang = functions\request::index("POST","lang");



		$form = functions\makeForm::open(array(

			"action"=>"?",

			"method"=>"post",

			"id"=>"",

			"id"=>"",

		));



		$form .= functions\makeForm::label(array(

			"id"=>"typeLabel", 

			"for"=>"type", 

			"name"=>"მოდულის უნიკალური ბმული",

			"require"=>""

		));

		$form .= functions\makeForm::inputText(array(

			"placeholder"=>"მოდულის უნიკალური ბმული", 

			"id"=>"type", 

			"name"=>"type",

			"value"=>""

		));

		

		$form .= functions\makeForm::label(array(

			"id"=>"titleLabel", 

			"for"=>"title", 

			"name"=>"დასახელება",

			"require"=>""

		));

		$form .= functions\makeForm::inputText(array(

			"placeholder"=>"დასახელება", 

			"id"=>"title", 

			"name"=>"title",

			"value"=>""

		));





		$form .= functions\makeForm::close();



		

		$this->out = array(

			"Error" => array(

				"Code"=>0, 

				"Text"=>"ოპერაცია შესრულდა წარმატებით !",

				"Details"=>""

			),

			"form" => $form,

			"attr" => "formParentModuleAdd('".$lang."')"

		);







		return $this->out;

	}

}