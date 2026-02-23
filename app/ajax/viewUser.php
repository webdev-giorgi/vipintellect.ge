<?php 
class viewUser
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
		require_once 'app/functions/request.php';

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>"მოხდა შეცდომა !",
				"Details"=>"!"
			)
		);

		$id = functions\request::index("POST","id");

		$user = new Database('user', array(
			'method'=>'select', 
			'id'=>$id,
			'lang'=>$_SESSION["LANG"]
		));
		$getter = $user->getter();

		// echo "<pre>";
		// print_r($getter);
		// echo "</pre>";

		$table = '<table class="striped"><tbody>';
		if(count($getter)) {
			$val = $getter;
			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'ს.კ.: ',
				$val['id']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'რეგისტრაციის თარიღი: (დ/თ/წ)',
				date("d/m/Y H:i:s", $val['register_date'])
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'IP მისამართი: ',
				$val['register_ip']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'ტრენინგი: ',
				$val['training_title']
			);
			
			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'ელ-ფოსტა: ',
				$val['email']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'სახელი გვარი: ',
				$val['firstname']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'საკონტაქტო ნომერი: ',
				$val['phone']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'ასაკი: ',
				$val['age']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'სწავლების სასურველი დრო: ',
				$val['starttime']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'როგორ შეიტყვეთ ჩვენ შესახებ: ',
				$val['howfind_title']
			);

		}else{
			$table .= sprintf("
					<tr>
					<td colspan=\"2\">%s</td>
					</tr>",
					'მონაცემი ვერ მოიძებნა !'
			);
		}
		$table .= '</table></tbody>';

		$this->out = array(
			"Error" => array(
				"Code"=>0, 
				"Text"=>"ოპერაცია შესრულდა წარმატებით !",
				"Details"=>""
			),
			"table" => $table
		);	

		return $this->out;
	}
}