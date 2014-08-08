<?php

/**
 * Class Resource_HtmlMailer
 * Following is example of how to use mailer class
 * 
 *  >	$mail = new Resource_HtmlMailer();
 *  >	$mail->setSubject("Hello!!")
 *  >			->addTo("noreply@yourdomain.com")
 *  >			->setViewParams('name', "Yogesh Patel")
 *  >			->setHtmlTemplate("index.phtml");
 * 
 * @author Yogesh Patel
 */

class Resource_HtmlMailer extends Zend_Mail {
	
	static $fromName = "";
	
	static $fromEmail = "";
	
	/**
	 * @var Zend_View
	 */
	static $_defaultView;
	
	/**
	 * current instance of our Zend_View
	 * @var Zend_View
	 */
	protected $_view;
	
	public static function getDefaultView() {
		
		if(self::$_defaultView === null) {
			
			self::$_defaultView = new Zend_View();
			self::$_defaultView->setScriptPath(APPLICATION_PATH. "/modules/mail/views/scripts/mails");
		}
		
		return self::$_defaultView;
	}
	
	public function setHtmlTemplate($template, $encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE) {
		
		$html = $this->_view->render($template);
		$this->setBodyHtml($html, $this->getCharset(), $encoding);
		$this->send();
	}
	
	public function setViewParams($property, $value) {
		
		$this->_view->__set($property, $value);
		return $this;
	}
	
	public function __construct($charaset = 'iso-8859-1') {
		
		parent::__construct($charaset);
		
		$Configuration = new Configuration_Model();
		
		self::$fromName = $Configuration->getConfigValueByKey("SENDFROM_NAME");
		self::$fromEmail =  $Configuration->getConfigValueByKey("SENDFROM_EMAIL");
		
		$this->setFrom(self::$fromEmail, self::$fromName);
		$this->_view = self::getDefaultView();
		
	}	
	
}