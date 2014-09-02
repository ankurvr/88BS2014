<?php

	/**
	 * 	Class >  Resource_Controller_Backend
	 * 	Extends  Resource_Controller_Action, This class extend by all admin
	 * 	section module controller for common settings
	 * 
	 * 	Date: 12 Aug 2014
	 * 	@author Ankur Raiyani
	 */

	class Resource_Controller_Backend extends Resource_Controller_Action
    {
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
			
			$this->checkAuthentication($Module, $Controller);
		}

		

		/**
		 * 	Function init 
		 * 	Construct of Class, Used to initiate some settings
		 * 
		 * 	Date: 12 Aug 2014
		 * 	@see Zend_Controller_Action::init()
		 * 	@author Ankur Raiyani
		 */
		
		public function init()
        {
			global $Database;
			$this->view->headTitle()->setSeparator(' - ')->append('Bulk SMS');
		}
		
		/**
		 * 	Function checkAuthentication
		 * 	Authenticate admin that it's already login or not
		 *
		 * 	Date: 12 Aug 2014
		 * 	@author Ankur Raiyani
		 */
		
		public function checkAuthentication($Module, $Controller)
        {
				
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

