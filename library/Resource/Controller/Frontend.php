<?php

class Resource_Controller_Frontend extends Resource_Controller_Action {

	/**
	 * Public static variable
	 * @var Zend_Translate
	 */
	public static $Translate;
	public static $TimeZone;
	
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
	 * 	Static variable
	 */
	static $Lang;
	
	/**
	 * 	> YOG: Define users and userprofile objects
	 */
	public $Users;
	public $UsersProfile;
	
	public $DisabledControllers = array();
	
	public function init() {

		global $Database;
		
		try {

			$this->initCustomView();
		}
		catch (Exception $e) {

			throw $e;
		}
		
	}

	protected function initCustomView() {
		
		// Initialize database object
		global $Database;			
		$this->Database = $Database;

        // Start session
		Zend_Session::start();
		
		// Retrive requested module name and controller name
		$Module = $this->getRequest()->getModuleName();
		$Controller = $this->getRequest()->getControllerName();

		$this->view->module = $Module;
		$this->view->controller = $Controller;
		$this->view->action = $this->getRequest()->getActionName();
		
		///////////////////////////////////////////////////////////
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
		///////////////////////////////////////////////////////////
		
        $options = array(
            'layout'     => 'home',
            'layoutPath' => APPLICATION_PATH.'/layouts/',
            'contentKey' => 'content',  // ignored when MVC not used
        );

        $layout = Zend_Layout::startMvc($options);
	}

	function __call($method, $args) {
		
		throw new Exception("Action does not exist"); // This is done by default
		// Just do whatever you want to do in this function (like redirecting)
	}
	
}
