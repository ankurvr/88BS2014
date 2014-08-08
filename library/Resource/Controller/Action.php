<?php

class Resource_Controller_Action extends Zend_Controller_Action {

	public $Mode;

	public function getMode() {

		return $this->Mode;
	}

	public function setMode($Mode) {

		$this->Mode = $Mode;
	}

	public function isAdmin() {

		if($this->getMode()) {

			return true;
		}
		return false;
	}
}