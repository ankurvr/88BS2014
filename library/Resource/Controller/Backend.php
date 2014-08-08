<?php

	/**
	 * 	Class >  Resource_Controller_Backend
	 * 	Extends  Resource_Controller_Action, This class extend by all admin
	 * 	section module controller for common settings
	 * 
	 * 	Date: 1 April 2013
	 * 	@author Yogesh Patel
	 */

	class Resource_Controller_Backend extends Resource_Controller_Action {

		
		/**
		 * Public static variable
		 * @var Zend_Translate
		 */
		public static $Translate;
		
		/**
		 * 	Public variable
		 * 	@var Zend_Session
		 */
		public $session; 
		
		/**
		 * 	Public variable
		 * 	@var Zend_Db_Adapter_Mysqli
		 */
		public $Database;
		
		/**
		 * 
		 * 	(non-PHPdoc)
		 * 	@see Zend_Controller_Action::preDispatch()
		 */
		
		public function preDispatch() {
						
			// Initialize database object
			global $Database;			
			$this->Database = $Database;

			// Start session 
			Zend_Session::start();
			
			// Retrive requested module name and controller name
			$Module = $this->getRequest()->getModuleName();
			$Controller = $this->getRequest()->getControllerName();
			
			///////////////////////////////////////////////////////////////////////////////
			/* 	Placed to resolve server problems				     */
			
			$Parameters = $this->getRequest()->getParams();
				
			if(isset($Parameters[0])) {
			
				parse_str($Parameters[0], $UserParams);
			
				if($_GET) {
					$_GET = array_merge($Parameters, $UserParams);
				} else {
					$_POST = array_merge($Parameters, $UserParams);
				}
			}
			///////////////////////////////////////////////////////////////////////////////
			
			$Request = new Zend_Controller_Request_Http();
			$Reuest_Admin = $Request->getHeader('Admin-Key');
			$Reuest_Type = $Request->getHeader('Admin-Type');
				
			if($Reuest_Admin && !($Module == "admin" && $Controller ==  "login")) {
			
				if(!Zend_Session::namespaceIsset("AdminNamespace")) {
						
					if($Reuest_Type) {
						echo json_encode(array('Error' => 'ACCESS DENIED')); die;
					} else {
						echo 'ACCESS DENIED'; die;
					}
				}
			}
				
			// Disable layout for all modules of admin section instead of admin/index
			if(!($Module == "admin" && $Controller ==  "index")) {
				$this->_helper->layout->disableLayout();
			}
				
			// Define constant for actionn url and controller url
			define("ACTION_URL", SITE_URL.$Module."/".$Controller);
			define("CONTROLLER", $Module."_".$Controller);
			
			self::initTranslation($Module, $Controller);
			
			$this->checkAuthentication($Module, $Controller);
		}

		

		/**
		 * 	Function init 
		 * 	Construct of Class, Used to initiate some settings
		 * 
		 * 	Date: 1 April 2013
		 * 	@see Zend_Controller_Action::init()
		 * 	@author Yogesh Patel
		 */
		
		public function init() {

	
			global $Database;
			
			$this->view->headTitle()->setSeparator(' - ')->append('TAG YOUR LIFE: Administrator');
				
			// Initalize Multiple Language 
			$VO_Language = new VO_TagLanguages();
			$DAO_Language = new DAO_TagLanguages();
			
			$this->view->LanguageList = $DAO_Language->getListByCriteria($VO_Language, $Database);
			unset($VO_Language); unset($DAO_Language);	
			
		}
		
		
		
		/**
		 * 	Function initTranslation
		 * 	Fetch Language keys and it's values from language file and from 
		 * 	database and add into language translation
		 *
		 * 	Date: 1 April 2013
		 * 	@author Yogesh Patel
		 */
		
		public static function initTranslation($Module, $Controller) {
			
			$Language = new Language_Model();
			
			
			$_LangCache = Resource_Cache::getDefaultCache();
			
			Zend_Translate::setCache($_LangCache);
			
			
			
			try {
								
				// Set default language.			
				$LangFile = LANGUAGE_PATH. "/".$Language->getLanguageCode().".php";		
						
				$_Translate = new Zend_Translate(
						array(
								'adapter' => 'array',
								'content' => $LangFile,
								'locale'  => $Language->getLanguageCode()
						)
				);
				
			
				$LanguageList = $Language->listAllLanguages();
								
				if(count($LanguageList) > 0) {
					
					foreach ($LanguageList as $Index => $Value) {
						
						// Load Language base file
						$LangFile = LANGUAGE_PATH. "/".$Value["langcode"].".php";
						$_Translate->addTranslation(
								array(
										'content' => $LangFile,
										'locale'  => $Value["langcode"]
								)
						);
						
						// Load Language defination key
						$LangDefination = $Language->getLanguageDefination($Value["langid"], ucfirst($Module));	
						if(count($LangDefination) > 0) {							
							$_Translate->addTranslation(
									array(
											'content' => $LangDefination,
											'locale'  => $Value["langcode"]
									)
							);
						} unset($LangDefination);					
							
						// Load Module language file
						$LangFile = LANGUAGE_PATH. "/".$Module."/".$Value["langcode"]."/".$Controller.".php";						
						if(file_exists($LangFile)) {
						
							$_Translate->addTranslation(
									array(
											'content' => $LangFile,
											'locale'  => $Value["langcode"]
									)
							);
						}
						
						unset($LangFile);
					}
				}
				
				// YOG: Language log writer. 
				// YOG: Create a log instance
				$_LogWriter = new Zend_Log_Writer_Stream( LANGUAGE_DIRECTORY .  '/lang_admin.log');
				$_Log = new Zend_Log($_LogWriter);
				
				// YOG: Attach it to the translation instance
				$_Translate->setOptions(
						array(
								'log'             => $_Log,
								'logMessage'      => "Missing '%message%' within locale '%locale%'",
								'logUntranslated' => true
						)
				);
				
				
				
				// Set Locale Language
				$_Translate->setLocale($Language->getLanguageCode());
				self::$Translate = $_Translate;
				Zend_Registry::set('Zend_Translate', $_Translate);
								
			} catch (Exception $exception) {				
				throw $exception;
			}			
		}	
		
		
		
		/**
		 * 	Function checkAuthentication
		 * 	Authenticate admin that it's already login or not
		 *
		 * 	Date: 1 April 2013
		 * 	@author Yogesh Patel
		 */
		
		public function checkAuthentication($Module, $Controller) {
				
			if(!($Module == "admin" && $Controller ==  "login")) {
				
				if(!Zend_Session::namespaceIsset("AdminNamespace")) {
					$this->_redirect(SITE_URL."admin/login");
				} else {
					$this->session = new Zend_Session_Namespace('AdminNamespace');
				}
				 
			} else if($Module == "admin" && $Controller ==  "login") {
				
				if(Zend_Session::namespaceIsset("AdminNamespace")) {
					
					if($this->getRequest()->getActionName() != "logout")
						$this->_redirect(SITE_URL.$Module);
				}
			}
		}	
		
	} // End Class

