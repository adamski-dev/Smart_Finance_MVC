<?php

namespace App;

use \App\Auth;
use \App\Flash;

use PHPMailer\PHPMailer\PHPMailer;


/**
 * Mail
 *
 * PHP version 7.0
 */

class Mail
{
	
	
	public static function send($email, $title, $text, $html){
	
		require_once'../vendor/autoload.php';
		
		$mail = new PHPMailer();
		
		$mail->IsSMTP();
		$mail->CharSet="UTF-8";
		$mail->Host = "smtp.gmail.com";
		//$mail->SMTPDebug = true; // debugging information
		$mail->Port = 587 ;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->SMTPAuth = true;
		$mail->IsHTML(true);
		$mail->Username = "adamskipop@gmail.com";
		$mail->Password = "tutaj haslo";
		$mail->setFrom('adamskipop@gmail.com', 'Adam Pop');
		$mail->AddAddress("$email");
		$mail->Subject = "$title";
		$mail->Body = "$text" . '<br/>' . "$html";

		if(!$mail->Send()) {
		echo "Błąd wysyłania e-maila: " . $mail->ErrorInfo;
		}	
	}
}
