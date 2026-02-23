<?php 
class paymentview
{
	public $data;
	public $string;

	public function index(){
		require_once("app/functions/strip_output.php"); 
		$out = '';
		if(count($this->data)) : 
			foreach ($this->data as $val) {
				if($val['payment_status']==1){
					$status = "ელოდება გადახდას";
				}else if($val['payment_status']==2){
					$status = "წარუმატებელი";
				}else if($val['payment_status']==3){
					$status = "გადახდილი";
				}
				$out .= sprintf("<tr>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td><a href=\"/fr/view/adminview/?id=%d\" target=\"_blank\">%s</a></td>
						<td><a href=\"javascript:void(0)\" onclick=\"viewUser('%s')\">%s</a></td>
						<td>%s</td>

						<td>
							<a href=\"javascript:void(0)\" onclick=\"viewPayment('%s')\"><i class=\"material-icons tooltipped\" data-position=\"bottom\" data-delay=\"50\" data-tooltip=\"დამატებითი ინფორმაცია\">pageview</i></a>
						</td>
					</tr>",
					date("d/m/Y g:i:s", (int)$val['date']), 
					strip_output::index($val['id']), //
					strip_output::index(urldecode($val['tbc_trans_id'])), 
					strip_output::index($val['tour_id']),
					strip_output::index($val['tour_id']),
					strip_output::index($val['username']),			
					strip_output::index($val['username']),			
					$status,			
					$val['id']
				);
			}
		endif;
		return $out;
	}
}