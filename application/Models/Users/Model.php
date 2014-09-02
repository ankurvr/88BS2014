<?php
/**
 * Created by PhpStorm.
 * User: AnkurR
 * Date: 8/11/14
 * Time: 11:50 PM
 */

class Users_Model implements Users
{

    /**
     * @param $UserId
     * @return User status true or false as per database
     * @author Ankur Raiyani
     * @date-created : 11-8-2014
     */
    public function getUserStatus($UserId)
    {
        global $Database;

        try {

        $sqlQuery = $Database->select()
                    ->from(array('bsa_u' => TABLE_USERS), array('bsa_u.userstatus'))
                    ->where('bsa_u.userid = ?', $UserId);

        $Result = $Database->fetchOne($sqlQuery);

        return $Result == 1 ? true : false;

        } catch (Exception $e) {
            p_r($e);die;
        }
    }

    /**
     * @param $UserId
     * @return User status true or false as per database
     * @author Ankur Raiyani
     * @date-created : 11-8-2014
     */
    public function getUserProfile($UserId)
    {
        
    }

    /**
     * 	Function validateUser
     * 	This function validate website username
     *
     * 	@param String $Username
     * 	@author Ankur Raiyani
     */

    public function validateUser($Username)
    {
        global $Database;
        $resultSet = array();
        try
        {
            $sqlQuery  = $Database->select()
                ->from(TABLE_USERS)
                ->where('username = ?', $Username)
                ->where('userrole = "User"');

            $resultSet = $Database->fetchOne($sqlQuery);
        }
        catch (Exception $e)
        {
            $this->debug("[FATAL ERROR]: ".$e->getMessage()."");
        }
        return $resultSet;
    }
}