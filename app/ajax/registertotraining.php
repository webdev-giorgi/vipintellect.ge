<?php 
class registertotraining
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
		$phone = str_replace(" ", "", functions\request::index("POST","phone"));
		$email = functions\request::index("POST","email");
		$age = functions\request::index("POST","age");
		$starttime = functions\request::index("POST","starttime");
		$howfind = functions\request::index("POST","howfind");
		$trainingid = functions\request::index("POST","trainingid");

		if(
			(!isset($firstname) || $firstname=="") || 
			(!isset($phone) || $phone=="") || 
			(!isset($email) || $email=="") || 
			(!isset($age) || $age=="") || 
			(!isset($starttime) || $starttime=="") || 
			(!isset($howfind) || $howfind=="") || 
			(!isset($trainingid) || $trainingid=="") 
		){
			$error = 1;
			$Success = 0;
  			$errorText = $l->translate("errorallfieldsrequire", $lang);
		}else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = 1;
			$Success = 0;
  			$errorText = $l->translate("erroremail", $lang);
		}else if (!preg_match('/^\d{9}$/', $phone)){
			$error = 1;
			$Success = 0;
  			$errorText = $l->translate("errorphone", $lang);
		}else{
			$email = str_replace("%40", "@", $email);
			$error = 0;
			$Success = 1;
  			$errorText = $l->translate("errornull", $lang);

  			$Database = new Database('user', array(
				'method'=>'insert', 
				'firstname'=>$firstname, 
				'phone'=>$phone, 
				'email'=>$email,
				'age'=>$age,
				'starttime'=>$starttime,
				'howfind'=>$howfind,
				'trainingid'=>$trainingid,
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