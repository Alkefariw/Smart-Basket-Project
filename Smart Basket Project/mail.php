<?php
session_start();

if (isset($_SESSION['username']) && isset($_FILES['file'])) {
	
	$username = $_SESSION['username'];

	$attachment = $_FILES['file']['tmp_name'];
	$html = $_POST['html'];

	$mail = new mymailer();

	$mail->sendmail($attachment, $html);
	die("PASS");
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class mymailer
{

	///////////////////////////////////////////////////
	private $smtp_host = "smtp.gmail.com";
	private $smtp_port = "465";
	private $smtp_username = "Enter Your Email Here";   
	private $smtp_password = "Enter Your App Password Here";  
	private $smtp_secure = "ssl";
	private $from_name = 'Mullen Library ';

	private $to_name = 'Mullen Library';
	private $to_email = 'Enter The Email That You Want To Send Here';   

	private $message_subject = "CUA Basket 1";
////////////////////////////////////////////////////////////////////////////////
	public $mail = null;

	function __construct()
	{
		require 'Mailer/src/Exception.php';
		require 'Mailer/src/PHPMailer.php';
		require 'Mailer/src/SMTP.php';

		$this->mail = new PHPMailer;
	}


	function sendmail($attachment, $message)
	{

		//Create a new PHPMailer instance
		$mail = $this->mail;

		//Remove this block. It is unsecure to some extent. It was added because of encryption problem at my local host
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);


		$mail->isSMTP();
		$mail->Host = $this->smtp_host;
		$mail->Port = $this->smtp_port;

		//$mail->SMTPDebug = 5;

		$mail->CharSet = 'utf-8';
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication
		$mail->Username = $this->smtp_username;
		//Password to use for SMTP authentication
		$mail->Password = $this->smtp_password;
		// Enable TLS encryption, `ssl` also accepted
		$mail->SMTPSecure = $this->smtp_secure;

		//Set who the message is to be sent from
		$mail->setFrom($this->smtp_username, $this->from_name);
		//Set an alternative reply-to address
		$mail->addReplyTo('noreply@gmail.com', 'NoReply');
		//Set who the message is to be sent to

		// Remove previous recipients
		$mail->ClearAllRecipients();

		$mail->addAddress($this->to_email, $this->to_name);
		//Set the subject line
		$mail->Subject = $this->message_subject;
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($message);

		//Attach an image file

		$mail->AddEmbeddedImage($attachment, 'attachment', 'MullenLibrary.png');
		
		//$mail->AddAttachment($attachment, 'MullenLibrary.png');
		//$mail->AddAttachment($attachment, 'MullenLibrary.png', 'base64', 'image/png');


		if (!@$mail->send()) {
			return "error";
		} else {
			return "good";
		}
	}
}
