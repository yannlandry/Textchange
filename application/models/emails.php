<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emails extends CI_Model {
/* Gère l'envoi d'e-mails */



	const FROM_ADDRESS = 'noreply@textchange.ca';
	const FROM_NAME = 'Textchange';
	
	const SMTP_HOST = 'mail.textchange.ca';
	const SMTP_USER = 'yann@textchange.ca';
	const SMTP_PASS = '$XUBRAXPK^';
	const SMTP_PORT = 26;



	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('email');
		
		$this->email->initialize(array(
			'smtp_host' => self::SMTP_HOST,
			'smtp_user' => self::SMTP_USER,
			'smtp_pass' => self::SMTP_PASS,
			'smtp_port' => self::SMTP_PORT,
			'mailtype' => 'html',
			'protocol' => 'smtp'
		));
	}
	
	
	
	# Activation temporaire
	public function send_temp_activation($token, $destination)
	{
		$this->email->clear();

		$this->email->from(self::FROM_ADDRESS, self::FROM_NAME);
		$this->email->to($destination);
		
		$this->email->subject('Bienvenue sur Textchange !');
		
		$message = '<h1>Bienvenue sur Textchange !</h1>';
		$message.= '<p>Merci de vous être inscrits à Textchange. Pour activer votre compte et commencer à afficher vos livres usagés, veuillez cliquer sur ce lien :</p>';
		$message.= '<p><a href="http://textchange.ca/activate?token='.$token.'">http://textchange.ca/activate?token='.$token.'</a></p>';
		$message.= '<p>Merci et à bientôt sur Textchange !</p>';
		$message.= '<p><em>Ceci est un message envoyé automatiquement. Prière de ne pas y répondre.</em></p>';
		$this->email->message($message);
		
		return $this->email->send();
	}
	
	
	
	# Contact
	public function send_temp_contact()
	{
		$this->email->clear();
		
		$this->email->from($this->input->post('email'), $this->input->post('name'));
		$this->email->to('eyl0515@umoncton.ca');
		
		$this->email->subject('[Contact Textchange] '.$this->input->post('subject'));
		
		$message = htmlspecialchars($this->input->post('message'));
		$this->email->message($message);
		
		return $this->email->send();
	}


	
}

/* End of file accounts.php */
/* Location: ./application/models/accounts.php */