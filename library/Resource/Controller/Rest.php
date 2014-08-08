<?php

class Resource_Controller_Rest extends REST_Controller {
	
	public function init() {

		echo "<pre>";
		print_r($this->getInvokeArgs()); die;
	}		
}