<?php 

if (!defined('CONNECTION_CONFIG_PHP'))
{
    define('CONNECTION_CONFIG_PHP',1);
    define('DB_DATABASE_CLASS', 'Mysqli');
    define('DB_TABLE_PREFIX', 'kdplpm_');

    require 'configs/database_tables.php';

    /**
     * Class > ConnectionConfig
     * Established connection to database and return connection object
     * by some __get and __set method
     *
     * Date: 4 Aug, 2014
     * @author Ankur Raiyani
     */

    class ConnectionConfig
    {
        /**
         * Variables of classes
         * @access private
         */

        private $_hostname;
        private $_username;
        private $_password;
        private $_database;

        /**
         * Function __construct
         * Get Database criteria from ini file and set it's value
         * by set method
         *
         * Date: 4 Aug, 2014
         * @author Ankur Raiyani
         */

        public function __construct()
        {
            try
            {
                $config = new Zend_Config_Ini('configs/application.ini', 'production');

                // Set values
                $this->setHostname($config->database->params->host);
                $this->setUsername($config->database->params->username);
                $this->setPassword($config->database->params->password);
                $this->setDbname($config->database->params->dbname);

                // Make connection to database
                $Database = $this->connect();

                // Store connection object into Zend Registry class
                Zend_Registry::set('Database', $Database);

            }
            catch(Exception $exp)
            {
                throw $exp;
            }
        }

        /**
         * Get Methods
         *
         * Date: 4 Aug, 2014
         * @return Value of private variables
         * @author Ankur Raiyani
         */

        public function getHostname()
        {
            return $this->_hostname;
        }
        public function getUsername()
        {
            return $this->_username;
        }
        public function getPassword()
        {
            return $this->_password;
        }
        public function getDbname()
        {
            return $this->_database;
        }
        public function getConnection()
        {
            return Zend_Registry::get('Database');
        }

        /**
         * Set Methods
         * Store Value into private variables
         *
         * Date: 4 Aug, 2014
         * @author Ankur Raiyani
         */

        public function setHostname($hostname)
        {
            $this->_hostname = $hostname;
        }
        public function setUsername($username)
        {
            $this->_username = $username;
        }
        public function setPassword($password)
        {
            $this->_password = $password;
        }
        public function setDbname($database)
        {
            $this->_database = $database;
        }

        /**
         * Function connect
         * Make connection to databse
         *
         * Date: 4 Aug, 2014
         * @return Zend_Db_Adapter_Mysqli
         * @author Ankur Raiyani
         */

        public function connect()
        {
            $config = new Zend_Config(
                    array(
                            'database' => array(
                                    'adapter' => DB_DATABASE_CLASS,
                                    'params'  => array(
                                            'host'     => $this->_hostname,
                                            'dbname'   => $this->_database,
                                            'username' => $this->_username,
                                            'password' => $this->_password,
                                    )
                            )
                    )
            );
            return Zend_Db::factory($config->database);
        }
    }
}
