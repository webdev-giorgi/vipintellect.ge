<?php 
class sendmessage
{
	public $out; 

	public function __construct()
	{

	}
	
	public function index(){
		require_once 'app/core/Config.php';
		require_once 'app/functions/request.php';
		require_once 'app/functions/l.php';
		require_once 'app/functions/sendEmail.php';

		$l = new functions\l();

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>$l->translate("error"),
				"Details"=>"!"
			)
		);

		$lang = functions\request::index("POST","lang");
		$firstname = functions\request::index("POST","firstname");
		$email = functions\request::index("POST","email");
		$massage = functions\request::index("POST","massage");

		if(
			empty($firstname) && 
			empty($email) &&
			empty($massage) 
		){
			$error = 1;
			$Success = 0;
  			$errorText = $l->translate("errorallfieldsrequire", $lang);
		}else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = 1;
			$Success = 0;
  			$errorText = $l->translate("erroremail", $lang);
		}else{
			$email = str_replace("%40", "@", $email);
			$error = 0;
			$Success = 1;
  			$errorText = $l->translate("errornull", $lang);

  			$Database = new Database('comments', array(
				'method'=>'insert', 
				'commentId'=>1, 
				'firstname'=>$firstname, 
				'organization'=>"nope",
				'email'=>$email,
				'comment'=>$massage,
				'lang'=>$lang
			));
		}

		$this->out = array(
			"Error" => array(
				"Code"=>$error, 
				"Text"=>$errorText,
				"Details"=>""
			),
			"Success"=>array(
				"Code"=>$Success, 
				"Text"=>$errorText,
				"Details"=>""
			)
		);

		return $this->out;
	}
}