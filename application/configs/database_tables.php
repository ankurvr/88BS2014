<?php

/**
 * 	File  > database_table
 * 	Define constant for all database table 
 *
 * 	Date: 4 Aug, 2014
 * 	@author Ankur Raiyani
 */
	
	/**
	 * 	Function cryptSwTable
	 * 	Decode database tables name 
	 *
	 * 	Date: 4 Aug, 2014
	 * 	@author Ankur Raiyani
	 */
	
	function cryptSwTable($table_name)
	{
		return DB_TABLE_PREFIX . (DEVELOP_VERSION ? $table_name : md5($table_name));
	}
	
	/**
	 *  Constant variables for database tables name 
	 * 	@var String
	 */
	
	define('TABLE_ADMINISTRATOR_LOG', cryptSwTable('administrator_log'));