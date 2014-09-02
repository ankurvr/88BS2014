<?php
/**
 * Created by PhpStorm.
 * User: AnkurR
 * Date: 8/11/14
 * Time: 11:50 PM
 */

interface Users
{
    /**
     * @param $UserId
     * @return User status 1 or 0 as per database
     * @author Ankur Raiyani
     * @date-created : 11-8-2014
     */
    public function getUserStatus($UserId);

    /**
     * @param $UserId
     * @return User status 1 or 0 as per database
     * @author Ankur Raiyani
     * @date-created : 11-8-2014
     */
    public function getUserProfile($UserId);
}