<?php

class Configuration_Model extends DAO_TagConfigurations implements Configuration {
	
	
	public function getConfigValueByKey($Key) {
		
		global $Database;
		
		$ConfigValue = "";
		try {
			 
			if (!$this->_empty($Key)) {

				$sqlQuery  = $Database->select()
								->from(TABLE_CONFIGURATIONS, array("configvalue"))
								->where("configkey = ?", $Key)->__toString();
				
				$record = $Database->fetchOne($sqlQuery);
				
				if ($record != NULL) {
					$ConfigValue = $record;
				}
				
			} else {
				throw new Exception("Configuration key is not defined");
			}
			
		} catch (Exception $e) {
		
			$this->debug("[FATAL ERROR]: ".$e->getMessage()."");
		}
		
		return $ConfigValue;
	}
	
	public function getConfigListByGroup($Group) {
		
	}
	
	
	/**
	 * 	Function checkCurrencyCode
	 * 	Check duplicate currency codes while editing
	 * 
	 * 	@param String $curr_id, $curr_code
	 * 	@see Currency::checkCurrencyCode()
	 * 	@author Ankur Raiyani
	 */
	
	public function checkCurrencyCode($curr_id, $curr_code) {
		
		global $Database;
		
		$selectQuery = $Database->select()
								->from(TABLE_CURRENCIES, array('COUNT(currencyid)') )
								->where('currencyid != ?', $curr_id)
								->where('currencycode = ?', $curr_code);
		
		if($Database->fetchOne($selectQuery) > 0)
			return false;
		
		return true;
	}
	
			
	/**
	 * 	Function validateGroupKey
	 * 	User to validate configuration key. If key already exists, 
	 * 	return true otherwise false 
	 * 
	 * 	@param $Key
	 * 	@see Configuration::validateGroupKey()
	 * 	@author Yogesh Patel
	 */
	
	public function validateGroupKey($Key, $GroupId = 0) {

		global $Database;
		
		$selectQuery = $Database->select()
						->from(TABLE_CONFIGURATION_GROUPS, array('COUNT(cgroupid)') )
						->where('groupskey = ?', $Key);
		
		if($GroupId > 0)
			$selectQuery->where('cgroupid != ?', $GroupId);
		
		if($Database->fetchOne($selectQuery) > 0)
			return true;
		
		return false;		
	}	
	
	
	/**
	 * 	Function getNextShortingNo
	 * 	Find last shoring number value and return 
	 * 	next possible value
	 * 
	 * 	@see Configuration::getNextShortingNo()
	 * 	@author Yogesh Patel
	 */
	
	public function getNextShortingNo() {
		
		
		global $Database;
		
		$selectQuery = $Database->select()
						->from(TABLE_CONFIGURATION_GROUPS, array('sortorder') )
						->order("sortorder DESC")
						->limitPage(0, 1);
		
		$ShortingNo = $Database->fetchOne($selectQuery);
		
		if($ShortingNo == NULL)
			return 0;
		
		return ($ShortingNo+1);
	}
	
	
	public function getConfigDataByKey($Key) {
		
		global $Database;
		
		$ConfigValue = "";
		try {
			 
			if (!$this->_empty($Key)) {

				$sqlQuery  = $Database->select()
								->from(TABLE_CONFIGURATIONS, array( 'title' => "configvalue", 'description' => 'configdescription' ))
								->where("configkey = ?", $Key)->__toString();
				
				$record = $Database->fetchRow($sqlQuery);
				
				if ($record != NULL) {
					$ConfigValue = $record;
				}
				
			} else {
				throw new Exception("Configuration key is not defined");
			}
			
		} catch (Exception $e) {
		
			$this->debug("[FATAL ERROR]: ".$e->getMessage()."");
		}
		
		return $ConfigValue;
	}
	
	
}

