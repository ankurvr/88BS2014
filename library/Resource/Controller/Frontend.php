<?php

class Resource_Controller_Frontend extends Resource_Controller_Action
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
        $Action = $this->getRequest()->getActionName();

		$this->view->module = $Module;
		$this->view->controller = $Controller;
		$this->view->action = $Action;
		
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

        if($Module == 'website')
        {
            if($Controller == 'login')
            {
                $this->_helper->layout->disableLayout();
            }
            else
            {
                $options = array(
                    'layout'     => 'home',
                    'layoutPath' => APPLICATION_PATH.'/layouts/',
                    'contentKey' => 'content',  // ignored when MVC not used
                );
                $layout = Zend_Layout::startMvc($options);

                $this->checkAuthentication($Module, $Controller, $Action);
            }
        }
	}

    public function checkAuthentication($Module, $Controller, $Action)
    {
        if($Module == "website" && $Controller == "login" && $this->getRequest()->getActionName() == 'reactive')
        {
            // Nothing to do
        }
        else if(!($Module == "website" && $Controller ==  "login"))
        {
            if(!Zend_Session::namespaceIsset("WebsiteNamespace"))
            {
                $this->_redirect(SITE_URL."login/");
            }
            else
            {
                $UModel = new Users_Model();
                $this->session = new Zend_Session_Namespace('WebsiteNamespace');

                if ($UModel->getUserStatus($this->session->UserObject->getUserId()))
                {
                    $this->Users = $this->session->UserObject;
                    $this->view->Username = $this->Users->getUserfirstname(). " ".$this->Users->getUserlastname();
                    $this->view->Userid = $this->Users->getUserId();
                    $this->UsersProfile = $UModel->getUserProfile($this->Users->getUserId());
                    $this->view->UsersProfile = $this->UsersProfile;
                    unset($UModel);
                }
                else
                {
                    if(Zend_Session::namespaceIsset("WebsiteNamespace"))
                    {
                        $Session = new Zend_Session_Namespace('WebsiteNamespace');
                        $Users = $Session->UserObject;
                        Zend_Session::namespaceUnset("WebsiteNamespace");
                        unset($this->session);
                    }
                    $this->_redirect(SITE_URL."login/");
                }
            }
        }
        else if($Module == "website" && $Controller ==  "login")
        {
            if(Zend_Session::namespaceIsset("WebsiteNamespace")) {

                if($this->getRequest()->getActionName() != "logout" && $this->getRequest()->getParam("username","") != '')
                {
                    echo json_encode(array('result' => "Success"));die;
                }

                if($this->getRequest()->getActionName() != "logout")
                {
                    $this->_redirect(SITE_URL."index/");
                }
            }
        }
        else
        {
            $this->_redirect(SITE_URL."login/");
        }
    }

	function __call($method, $args) {
		
		throw new Exception("Action does not exist"); // This is done by default
		// Just do whatever you want to do in this function (like redirecting)
	}
	
}
