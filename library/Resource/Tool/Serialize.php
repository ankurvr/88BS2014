<?php

class Resource_Tool_Serialize {


    /**
     * @static
     * @param mixed $da	 
     * @return string
     */
    public static function serialize ($data) {
        return serialize($data);
    }

    /**
     * @static
     * @param $data
     * @return mixed
     */
    public static function unserialize ($data) {
		if(!empty($data) && is_string($data)) {
            return unserialize($data);
        }
        return null;
    }
    
}



?>