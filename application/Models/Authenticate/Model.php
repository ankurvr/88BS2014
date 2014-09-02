<?php

class Authenticate_Model implements Authenticate {
	
	public function createHashPassword($Password, $UserId = 0) {
		
		global $Database;
		
		$Salt = base64_encode(mcrypt_create_iv(self::PBKDF2_SALT_BYTES, MCRYPT_DEV_RANDOM));
		
		$HashPassword = self::PBKDF2_HASH_ALGORITHM . ":" . self::PBKDF2_ITERATIONS . ":" .  $Salt . ":" .
				base64_encode($this->pbkdf2(
						self::PBKDF2_HASH_ALGORITHM,
						$Password,
						$Salt,
						self::PBKDF2_ITERATIONS,
						self::PBKDF2_HASH_BYTES,
						true
				));
		
		if($UserId > 0 && $UserId != "") {
				
			$PassData = array(
					"userpasswordsalt" => $Salt,
					"userpassord"	=> $HashPassword
			);
			
			try {
			
				$Where = $Database->quoteInto('userid = ?', $UserId);
				$Database->update(TABLE_USERS, $PassData, $Where);
							
			} catch (Exception $exception) {
				
				throw $exception;
			}
		}
		
		return $HashPassword;
	}
	
	public function validatePassword($Password, $HashPassword) {
		
		$Params = explode(":", $HashPassword);
		
		if(count($Params) < self::HASH_SECTIONS)
			return false;
		
		$pbkdf2 = base64_decode($Params[self::HASH_PBKDF2_INDEX]);
		
		return $this->slowEquals(
				$pbkdf2,
				$this->pbkdf2(
						$Params[self::HASH_ALGORITHM_INDEX],
						$Password,
						$Params[self::HASH_SALT_INDEX],
						(int)$Params[self::HASH_ITERATION_INDEX],
						strlen($pbkdf2),
						true
				)
		);		
	}
	
	public function slowEquals($AString, $BString) {
		
		$Different = strlen($AString) ^ strlen($BString);
		
		for($i = 0; $i < strlen($AString) && $i < strlen($BString); $i++) {
			
			$Different |= ord($AString[$i]) ^ ord($BString[$i]);
		}
		
		return $Different === 0;		
	}
	
	public function pbkdf2($Algorithm, $Password, $Salt, $Count, $KeyLength, $RawOutput = false) {
		
		$Algorithm = strtolower($Algorithm);
		
		if(!in_array($Algorithm, hash_algos(), true))
			die('PBKDF2 ERROR: Invalid hash algorithm.');
		
		if($Count <= 0 || $KeyLength <= 0)
			die('PBKDF2 ERROR: Invalid parameters.');
		
		$hash_length = strlen(hash($Algorithm, "", true));
		$block_count = ceil($KeyLength / $hash_length);
		
		$output = "";
		for($i = 1; $i <= $block_count; $i++) {
			
			$last = $Salt . pack("N", $i);
			
			$last = $xorsum = hash_hmac($Algorithm, $last, $Password, true);
			
			for ($j = 1; $j < $Count; $j++) {
				$xorsum ^= ($last = hash_hmac($Algorithm, $last, $Password, true));
			}
			$output .= $xorsum;
		}
		
		if($RawOutput)
			return substr($output, 0, $KeyLength);
		else
			return bin2hex(substr($output, 0, $KeyLength));
		
	}
	
	public function randAlphanumeric() {
		
		$subsets[0] = array('min' => 48, 'max' => 57); // ascii digits
		$subsets[1] = array('min' => 65, 'max' => 90); // ascii lowercase English letters
		$subsets[2] = array('min' => 97, 'max' => 122); // ascii uppercase English letters
		 
		// random choice between lowercase, uppercase, and digits
		$s = rand(0, 2);
		$ascii_code = rand($subsets[$s]['min'], $subsets[$s]['max']);
		 
		return chr( $ascii_code );		
	}
	
	public function generateApiToken( $length = 16 ) {
		
		if ($length < 8 || $length > 44) return false;
		 
		// collecting info about the length
		$length_odd = (($length % 2) != 0);
		$length_has_root = ( strpos( sqrt($length), '.' ) === false);
		
		
		/**
		 * Let's make an offset based on oddity
		 * to mess things up a bit.  Feel free to go crazy here,
		 * but for the purpose of this tutorial I'll keep it simple.
		 */ 
		$offset = $length_odd ? 1 : 0;
		
		/**
		 * Mapping keys to positions that they will occupy.
		 * Since arrays are zero-based, we're subtracting 1 from each.
		 * Also we're adding offset to each.
		 * For convenience, let's gather our keys into string too.
		 * We will need it for hashing.
		 */
		
		$key_str = '';
		 
		$key_str .= $keys[ (0 + $offset)                 ] = $this->randAlphanumeric();
		$key_str .= $keys[ (($length / 4) - 1 + $offset) ] = $this->randAlphanumeric();
		$key_str .= $keys[ (($length / 2) - 1 + $offset) ] = $this->randAlphanumeric();
		$key_str .= $keys[ (($length - 2) + $offset)     ] = $this->randAlphanumeric();
		
		/**
		 * Building the "answer" to the key string.
		 * We'll do it by hashing the string in weird ways.
		 * We'll choose a hashing sequence based on whether the length has root.
		 */
		
		$hashed_keys = $length_has_root ? sha1(md5($key_str)) : sha1(sha1($key_str));
		
		/**
		 * Once again, it's easy to go crazy here, but
		 * for the purpose of this tutorial, we're going to simply
		 * fill up all remaining positions with the hashed_keys string
		 * as far as we have space
		 */		
		$hash_enum = 0;

		for ($i = 0; $i < $length; $i++) {
			if (!isset($keys[$i])) {
				$keys[$i] = $hashed_keys[$hash_enum];
				$hash_enum++;
			}
		}
		
		ksort($keys);
		return implode($keys, '');		
	}
	
	public function verifyApiToken( $String ) {
		
		$length = strlen($String);
		$keys = str_split($String);
		
		/**
		 * we're simply using the same algorithm
		 * to find key positions based on length
		 * as well as find which hashes must be used
		 */				
		$length_odd = (($length % 2) != 0);
		$length_has_root = ( strpos( sqrt($length), '.' ) === false);
		
		$offset = $length_odd ? 1 : 0;
		
		/**
		 * Only this time we're extracting the keys instead of
		 * generating them.  And while we're at it, let's remember the positions.
		 */		
		$key_str = '';
		
		$key_str .= $keys[ $pos1 = (int)(0 + $offset)                 ];
		$key_str .= $keys[ $pos2 = (int)(($length / 4) - 1 + $offset) ];
		$key_str .= $keys[ $pos3 = (int)(($length / 2) - 1 + $offset) ];
		$key_str .= $keys[ $pos4 = (int)(($length - 2) + $offset)     ];
		
		
		$hashed_keys = $length_has_root ? sha1(md5($key_str)) : sha1(sha1($key_str));
		
		$hash_string = '';
		
		/**
		 * we've already extracted the keys above, so here we should skip them,
		 * and instead extract everything else
		 */		
		for ($i = 0; $i < $length; $i++) {
			if ( $i != $pos1 &&
					$i != $pos2 &&
					$i != $pos3 &&
					$i != $pos4 ) {
				$hash_string .= $keys[$i];
			}
		}
		
		$hash_length = $length - 4;
		
		/**
		 * Returning the comparison of question to the answer;
		 * if they're equal - the key is valid
		 */		
		return ( $hash_string == substr($hashed_keys, 0, $hash_length) );
		
	}
	
	
	public function checkApiToken( $String ) {
		
		global $Database;
		
		try { 
		
			$select = $Database->select()
						->from(TABLE_USERS, array("userapi"))
						->where("userapi = ?", $String);
			
			if($Database->fetchOne($select) != "") {
				
				return true;
			}
			
		} catch (Exception $exception) {
			throw $exception;
		}

		return false;
	}
	
}
