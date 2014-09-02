<?php

interface Authenticate  {
	
 	/**
 	 * The hash algorithm to use
 	 * @var Recommended: SHA256
 	 */
	const PBKDF2_HASH_ALGORITHM = "sha256";
		
 	/**
 	 * The value of iteration
 	 * @var Int: 1000
 	 */
 	const PBKDF2_ITERATIONS = 1000;
	
 	/**
 	 * Length value for password salt
 	 * @var Int: 24
 	 */
 	const PBKDF2_SALT_BYTES = 24;
	
 	/**
 	 * Length value for hash password
 	 * @var Int: 24
 	 */
 	const PBKDF2_HASH_BYTES = 24;

 	/**
 	 * Password sections
 	 * @var Int: 4
 	 */
 	const HASH_SECTIONS = 4;

 	/**
 	 * Value of first algorithm index
 	 * @var Int: 0
 	 */
 	const HASH_ALGORITHM_INDEX = 0;

 	/**
 	 * Value of first hash iteration index
 	 * @var Int: 1
 	 */
 	const HASH_ITERATION_INDEX = 1;

 	/**
 	 * Value of first salt index
 	 * @var Int: 2
 	 */
 	const HASH_SALT_INDEX = 2;

 	/**
 	 * Value of first hash pbkdf2 index
 	 * @var Int: 3
 	 */
 	const HASH_PBKDF2_INDEX = 3;
		
 	
 	
 	/**
 	 *  Function :  createHashPassword
 	 *  This function create hash password by
 	 *  merging password string with auto generated salt
 	 */
 	public function createHashPassword($Password, $UserId = 0);

 	/**
 	 *  Function :  validatePassword
 	 *  This function compare with hash password with clean
 	 *  password and check weather it match or not
 	 */
 	public function validatePassword($Password, $HashPassword);

 	/**
 	 *  Function :  slowEquals
 	 *  Compares two strings $a and $b in length-constant time.
 	 */
 	public function slowEquals($AString, $BString);

 	/**
 	 *  Function :  slowEquals
 	 *  PBKDF2 key derivation function
 	 */
 	public function pbkdf2($Algorithm, $Password, $Salt, $Count, $KeyLength, $RawOutput = false);
 	
 	/**
 	 *  Function :  randAlphanumeric
 	 *  Function that will return a random alphanumeric character. 
 	 *  The function must randomly return one of the following: a-z, A-Z, 0-9.
 	 */
 	public function randAlphanumeric();
 	
 	/**
 	 *  Function :  generateApiToken
 	 *  The function generate Api token.
 	 *  The function takes length as a parameter. 
 	 */
 	public function generateApiToken( $length = 16 );
 	
 	/**
 	 *  Function :  verifyApiToken
 	 *  The function verify the api key and return true on success
 	 *  The function takes Api key as a parameter.
 	 */
 	public function verifyApiToken( $String );
 	
 	/**
 	 *  Function :  checkApiToken
 	 *  The function check that Api key is already exist in database or not.
 	 *  Return true on found Api key. 
 	 */
 	public function checkApiToken( $String );
 	
}
