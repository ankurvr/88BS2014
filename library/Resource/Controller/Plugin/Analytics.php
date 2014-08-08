<?php 

class Resource_Controller_Plugin_Analytics extends Zend_Controller_Plugin_Abstract {

    protected $enabled = true;

    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
        
    }

    public function disable() {
        $this->enabled = false;
        return true;
    }

    public function dispatchLoopShutdown() {
        
        // analytics
		
    }
}
