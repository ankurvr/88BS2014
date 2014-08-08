<?php

interface Configuration  {
	
	/**
	 *  Function :  getConfigValueByKey
	 *  Functin returns configuration key value.
	 */	
	public function getConfigValueByKey($ConfigKey);
	
	
	/**
	 *  Function :  getConfigListByGroup
	 *  Functin returns list of configuration information.
	 */
	public function getConfigListByGroup($ConfigGroup);
	
	
	/**
	 *  Function :  checkCurrencyCode
	 *  Functin returns false if currency code exist in database otherwise true
	 */
	public function checkCurrencyCode($curr_id, $curr_code);
	
	
	/**
	 * 	Function: validateGroupKey
	 * 	Check group configuration key value
	 */
	public function validateGroupKey($Key, $GroupId = 0);
	
	
	/**
	 * 	Function: getNextShortingNo
	 * 	Return integer next shorting integer value
	 */
	public function getNextShortingNo();
	
}
