<?php 
namespace functions;

class getpayment
{
	public function index($id)
	{
		$payments = new \Database('payments', array(
			'method'=>'selectById', 
			'id'=>$id
		));
		$getter = $payments->getter();

		$table = '<table class="striped"><tbody>';
		if(count($getter)) {
			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'საინდენთიფიკაციო კოდი: ',
				$getter['id']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'ტრანზაქციის საინდენთიფიკაციო კოდი: ',
				urldecode($getter['tbc_trans_id'])
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'გადახდის თარიღი: (დღე/თვე/წელი)',
				date("d/m/Y H:i:s", $getter['date'])
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'IP მისამართი: ',
				$getter['ip_address']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'ოპერაციული სისტემა: ',
				$getter['os']
			);
			
			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'ბრაუზერი: ',
				$getter['browser']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'მომხმარებლის სახელი: ',
				$getter['username']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s %s</td>
				</tr>",
				'სახელი გვარი: ',
				$getter['firstname'], 
				$getter['lastname']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'სქესი: ',
				($getter['gender']==1) ? "მამრობითი" : "მდედრობითი" 
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'საკონტაქტო ნომერი: ',
				$getter['phone']
			); 

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td><a href=\"%s/fr/view/admin-view/?id=%s\" target=\"_blank\">%s</a></td>
				</tr>",
				'ტურის დასახელება: ',
				\Config::WEBSITE_, 
				$getter['tour_id'],
				$getter['product_title']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'ჩამოსვლა / გამგზავნრება: ',
				$getter['checkin_checkout']
			); 

			if(isset($getter['tour_services']) && !empty($getter['tour_services'])){
				$explode = explode(",", $getter['tour_services']);
				$serviceTitles = array();
				foreach ($explode as $serv) {
					$ex = explode(":", $serv);
			  		$db_service = new \Database("service", array(
						"method"=>"subservicvesbyid", 
						"id"=>(int)$ex[2],
						"lang"=>$_SESSION["LANG"]
					));
					if($fetch = $db_service->getter()){ 
						$serviceTitles[] = $fetch[0]["title"];
					} 
				}

				$table .= sprintf("
					<tr>
					<td><strong>%s</strong></td>
					<td>%s</td>
					</tr>",
					'სერვისები: ',
					implode(", ", $serviceTitles)
				);
			}

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'მოზრდილები: ',
				$getter['adults']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s ( %s )</td>
				</tr>",
				'არასრულწლოვნები ( ასაკი ): ',
				$getter['children'],
				$getter['children_ages']
			);

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'სრული თანხა: ',
				$getter['total_price']
			);

			if($getter['payment_status']==1){
				$status = "ელოდება გადახდას";
			}else if($getter['payment_status']==2){
				$status = "წარუმატებელი";
			}else if($getter['payment_status']==3){
				$status = "გადახდილი";
			}

			$table .= sprintf("
				<tr>
				<td><strong>%s</strong></td>
				<td>%s</td>
				</tr>",
				'გადახდის სტატუსი: ',
				$status
			);
		}
		$table .= '</table></tbody>';

		return $table;
	}
}