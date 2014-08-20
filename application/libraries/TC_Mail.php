<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

# Chargement de SwiftMailer
require_once('application/third_party/swiftmailer/swift_required.php');

class TC_Mail {



	# SMTP server settings
	const SMTP_HOST = '';
	const SMTP_USER = '';
	const SMTP_PASS = '';
	const SMTP_PORT = 25;

	const FROM_ADDRESS = 'noreply@textchange.ca';
	const FROM_NAME = 'Textchange';

	const TEMPLATE_DIR = 'application/include/emails';



	# Éléments Swift
	private $transport;
	private $mailer;



	# Éléments du message
	private $from;
	private $replyTo;
	private $subjects = array();
	private $destinations = array();
	private $messages = array();



	# CodeIgniter
	//private $CI;



	# Constructeur
	public function __construct()
	{
		$this->transport = Swift_SmtpTransport::newInstance()
			->setHost(self::SMTP_HOST)
			->setPort(self::SMTP_PORT)
			->setUsername(self::SMTP_USER)
			->setPassword(self::SMTP_PASS)
			->setEncryption('ssl')
		;
		
		$this->mailer = Swift_Mailer::newInstance($this->transport);
		
		/*$this->CI =& get_instance();
		$this->CI->load->library('email');
		$this->CI->email->initialize(
			array(
				'mailtype' => 'html',
				'protocol' => 'smtp',
				'smtp_host' => self::SMTP_HOST,
				'smtp_user' => self::SMTP_USER,
				'smtp_pass' => self::SMTP_PASS,
				'smtp_port' => self::SMTP_PORT
			)
		);*/
	}



	# Règle l'adresse de l'expéditeur
	public function set_from($from)
	{
		$this->from = $from;
	}



	# Règle l'adresse de réponse
	public function set_reply_to($replyTo)
	{
		$this->replyTo = $replyTo;
	}



	# Règle le sujet
	public function set_subject($lang, $subject)
	{
		$this->subjects[$lang] = $subject;
	}



	# Ajoute une destination
	public function add_destination($lang, $email, $name = NULL)
	{
		$this->destinations[$lang][] = $email;
	}



	# Règle le corps, non l'entièreté du message, qui lui est toujours basé sur le même template
	public function set_body($lang, $html)
	{
		$this->messages[$lang] = $html;
	}



	# Récupère un template et le remplit avec les données fournies
	public function use_template($template, $items)
	{
		$tmpfile = self::TEMPLATE_DIR.'/'.$template.'.html';
		if(!file_exists($tmpfile)) return NULL;

		$search = array();
		$replace = array();

		foreach($items as $src => $rep) {
			$search[] = "{{ ".$src." }}";
			$replace[] = $rep;
		}

		return str_replace($search, $replace, file_get_contents($tmpfile));
	}



	# Envoie les messages
	public function send()
	{
		$count = intval(file_get_contents('application/logs/email_count.txt'));

		foreach($this->messages as $lang => $body) {
			if(!empty($body) && !empty($this->subjects[$lang]) && !empty($this->destinations[$lang])) {
				
				$message = Swift_Message::newInstance($this->subjects[$lang])
					->setFrom(array(self::FROM_ADDRESS => self::FROM_NAME))
					->setContentType('text/html')
					->setBody( str_replace('{{ BODY }}', $body, file_get_contents(self::TEMPLATE_DIR.'/base.html')) )
				;
				
				foreach($this->destinations[$lang] as $D) {
					if($count > 9800) {
						$m = serialize($message);
						file_put_contents('application/archive/'.md5($m), $m);
					}
					else {
						$message->setTo(array($D));
						$count+= $this->mailer->send($message);
					}
				}
				
				unset($message);
				
				/*$message =& $this->CI->email;

				$message->subject($this->subjects[$lang]);
				$message->message(str_replace('{{ BODY }}', $body, file_get_contents(self::TEMPLATE_DIR.'/base.html')));

				if(count($this->destinations[$lang]) == 1) $message->to($this->destinations[$lang]);
				else $message->bcc($this->destinations[$lang]);

				if(!empty($this->from)) $message->from($this->from);
				else $message->from(self::FROM_ADDRESS, self::FROM_NAME);

				if(!empty($this->replyTo)) $message->reply_to($this->replyTo);

				$count+= intval($message->send());

				$message->clear();*/
			}
		}

		file_put_contents('application/logs/email_count.txt', $count);
		return $count;
	}



	/*********************
	 * FONCTIONS D'ENVOI *
	 *********************/



	# Activation du compte
	public function send_activation($token, $destination)
	{
		$this->set_subject(User::lang(), file_get_contents(self::TEMPLATE_DIR.'/'.User::lang().'/subjects/activation.txt'));
		$this->add_destination(User::lang(), $destination);
		$this->set_body(User::lang(), $this->use_template(User::lang().'/activation', array('TOKEN' => $token)));
		return $this->send();
	}



	# Récupération du mot de passe
	public function send_recover($password, $destination)
	{
		$this->set_subject(User::lang(), file_get_contents(self::TEMPLATE_DIR.'/'.User::lang().'/subjects/recover.txt'));
		$this->add_destination(User::lang(), $destination);
		$this->set_body(User::lang(), $this->use_template(User::lang().'/recover', array('PASSWORD' => $password)));
		return $this->send();
	}



	# Changement d'adresse courriel
	public function send_changemail($username, $token, $destination)
	{
		$this->set_subject(User::lang(), file_get_contents(self::TEMPLATE_DIR.'/'.User::lang().'/subjects/changemail.txt'));
		$this->add_destination(User::lang(), $destination);
		$this->set_body(User::lang(), $this->use_template(User::lang().'/changemail', array('USERNAME' => $username, 'TOKEN' => $token)));
		return $this->send();
	}



	# Formulaire de contact
	public function send_contact($name, $email, $subject, $message)
	{
		if(!empty($email)) $this->set_from($email);
		$this->set_subject('french', "[Contact Textchange] ".$subject);
		$this->add_destination('french', 'yann@textchange.ca');
		$this->set_body('french', $this->use_template('contact', array('NAME' => $name, 'EMAIL' => $email, 'SUBJECT' => $subject, 'MESSAGE' => nl2br($message))));
		return $this->send();
	}



	# Contacter un vendeur par courriel
	public function send_email_seller($replyTo, $replyName, $adID, $bookTitle, $message, $lang, $destination)
	{
		$this->set_from($replyTo);
		$this->set_subject($lang, "[Textchange] ".$bookTitle);
		$this->add_destination($lang, $destination);
		$this->set_body($lang, $this->use_template(User::lang().'/email_seller', array('REPLY-TO' => $replyTo, 'REPLY-NAME' => $replyName, 'AD-ID' => $adID, 'BOOK-TITLE' => $bookTitle, 'MESSAGE' => nl2br($message))));
		return $this->send();
	}



	# Notifier d'un nouveau message privé
	public function send_newpm($username, $name, $message, $lang, $destination)
	{
		$this->set_subject($lang, $name." ".file_get_contents(self::TEMPLATE_DIR.'/'.$lang.'/subjects/newpm.txt'));
		$this->add_destination($lang, $destination);
		$this->set_body($lang, $this->use_template($lang.'/newpm', array('USERNAME' => $username, 'NAME' => $name, 'MESSAGE' => nl2br(htmlspecialchars($message)))));
		return $this->send();
	}



	# Notifier de l'ajout d'un livre surveillé
	public function send_alert($username, $name, $ad, $title, $picture, $watchers)
	{
		$this->set_subject('french', htmlspecialchars($title));
		$this->set_subject('english', htmlspecialchars($title));

		$content = array(
			'USERNAME' => $username,
			'NAME' => $name,
			'AD-ID' => $ad,
			'TITLE' => htmlspecialchars($title),
			'PICTURE' => get_book_picture($picture, 'icon')
		);

		$this->set_body('french', $this->use_template('french/alert', $content));
		$this->set_body('english', $this->use_template('english/alert', $content));

		foreach($watchers as $W)
			$this->add_destination($W->Language, $W->Email);

		return $this->send();
	}



}
// END TC_Mail Class

/* End of file TC_Mail.php */
/* Location: ./application/libraries/TC_Mail.php */
