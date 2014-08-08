<?php 

class Resource_Controller_Plugin_ActionbarTitle extends Zend_Controller_Plugin_Abstract {

	
	protected $FrontEnd;
	protected $Modules = array();
    protected $Controllers = array();
    protected $Actions = array();
    
    protected $ModulesName;
    protected $ControllerName;
    protected $ActionName;
    
	Public function routeStartup(Zend_Controller_Request_Abstract $request) { 
		
		$this->FrontEnd = Zend_Controller_Front::getInstance();		
			
	}	
	
	public function routeShutdown(Zend_Controller_Request_Abstract $request) {

	}
	
	
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
				
		$AclArray = array();
		
		foreach ($this->FrontEnd->getControllerDirectory() as $module => $path) {

			if($module == FRONTEND_MODULE) {
			
				foreach (scandir($path) as $file) {
					
					if (strstr($file, "Controller.php") !== false) {
						 
						include_once $path . DIRECTORY_SEPARATOR . $file;
						 
						foreach (get_declared_classes() as $class) {
							 
							if (is_subclass_of($class, 'Zend_Controller_Action')) {
								 
								$controller = strtolower(substr($class, 0, strpos($class, "Controller")));
								
								$actions = array();
								 
								foreach (get_class_methods($class) as $action) {
						    
									if( preg_match( '/Action/', $action ) ) {
										$actions[] = substr($this->_camelCaseToHyphens($action),0,-6 );
									}
								}
							}
						}
						 
						$AclArray[$module][$controller] = $actions;
						$this->Controllers[$module][] = $controller;
					}
				}
				
				$this->Modules[] = $module;
			}
		}
		
		$this->Actions = $AclArray;
		
		$this->ModulesName = $request->getModuleName();
		$this->ControllerName = $request->getControllerName();
		$this->ActionName = $request->getActionName();
		
	}
	
	
	Public function dispatchLoopShutdown() {
		
		global $Database;
		
		$VO_Module = new VO_TagModules();
		$DAO_Module = new DAO_TagModules();
		
		$VO_Module->setModulename($this->ModulesName);
		$VO_Module->setModulecontroller($this->ControllerName);
		
		if($this->ActionName != '') {
			
			$VO_Module->setModuleaction($this->ActionName);
		}
				
		$VO_Module->setIsenable(1);		
		$ResultData = $DAO_Module->getListByCriteria($VO_Module, $Database);
		
		unset($VO_Module); unset($DAO_Module);
		
		if($ResultData->size() > 0) {
			
			$Module = $ResultData->getFirst();
			
			if($Module->getModuletitle() != "" && $Module->getModuletitle() != NULL) {
				
				$body = $this->getResponse()->getBody();
				$PageTitle = $Module->getModuletitle();
				
				$TitleEndPosition = stripos($body, "</title>");

				if($TitleEndPosition !== false) {
					$body = substr_replace($body, $PageTitle."</title>", $TitleEndPosition, 8);
				}
				
				$this->getResponse()->setBody($body);
			}

			unset($Module);
		}		
		
		unset($ResultData);
	}
	
	
	public function writeToDB() {
		
		foreach( $this->Modules as $strModuleName ) {
					
			if( array_key_exists( $strModuleName, $this->Controllers ) ) {
					
				foreach( $this->Controllers[$strModuleName] as $strControllerName ) {
		
					if( array_key_exists( $strControllerName, $this->Actions[$strModuleName] ) ) {
							
						foreach( $this->Actions[$strModuleName][$strControllerName] as $strActionName ) {
		
							Entities_ResourceTable::addResourceIfNonExistant($strModuleName, $strControllerName, $strActionName);
						}
					}
				}
			}
		}
	}
	
	
	private function _camelCaseToHyphens($string) {
		
		if($string == 'currentPermissionsAction') {
			$found = true;
		}
		$length = strlen($string);
		$convertedString = '';
		for($i = 0; $i <$length; $i++) {
			if(ord($string[$i]) > ord('A') && ord($string[$i]) < ord('Z')) {
				$convertedString .= '-' .strtolower($string[$i]);
			} else {
				$convertedString .= $string[$i];
			}
		}
		return strtolower($convertedString);
	}
	
}
