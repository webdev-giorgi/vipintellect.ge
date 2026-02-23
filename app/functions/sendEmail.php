<?php 
namespace functions; 

class sendEmail
{
	public function index($args)
	{
		require_once "app/_plugins/PHPMailer/PHPMailerAutoload.php"; 
		require_once "app/core/Config.php"; 

		if(!isset($_SESSION["SENDED_EMAILS"])){
			$_SESSION["SENDED_EMAILS"] = 1;
		}else{
			$_SESSION["SENDED_EMAILS"] = $_SESSION["SENDED_EMAILS"] + 1;
		}

		if(isset($_SESSION["SENDED_EMAILS"]) && $_SESSION["SENDED_EMAILS"]>=10){
			return false;
		}
		
		$out = false;	
		$mail = new \PHPMailer;
		//$mail->SMTPDebug = 3; 

		$mail->isSMTP(); 
		$mail->CharSet = 'UTF-8';
		$mail->Host = \Config::EMAIL_HOST;
		$mail->SMTPAuth = true;
		$mail->Username = \Config::EMAIL_USERNAME;
		$mail->Password = \Config::EMAIL_PASSWORD;
		$mail->SMTPSecure = 'tls';
		$mail->Port = 587;

		$mail->setFrom(\Config::EMAIL_USERNAME, \Config::EMAIL_NAME);
		$mail->addAddress($args["sendTo"]); 
		$mail->addReplyTo(\Config::EMAIL_USERNAME);
		// $mail->addCC('cc@example.com');
		// $mail->addBCC('bcc@example.com');

		// $mail->addAttachment('/var/tmp/file.tar.gz');         
		// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');   
		$mail->isHTML(true);                                  

		$mail->Subject = $args['subject'];
		$mail->Body = $args['body'];
		// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if(!$mail->send()) {
		    $out = false;
		} else {
		    $out = true;
		}

		return $out;
	}
}