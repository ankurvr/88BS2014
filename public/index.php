<?php

#==================================================================================
#  Define all Required path for the site.
#==================================================================================

    // Set Default Timezone
    date_default_timezone_set('GMT');
    error_reporting (E_ALL);

    // Define base path obtainable throughout the whole application
    defined('DEVELOP_VERSION')
    || define('DEVELOP_VERSION', "1");

    // Defined to enable or disable social import feature
    defined('SOCIAL_FLAG')
    || define('SOCIAL_FLAG', "0");

    // Define base path obtainable throughout the whole application
    defined('BASE_PATH')
    || define('BASE_PATH', realpath(dirname(__FILE__)).'/..');

    // Define path to application directory
    defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', BASE_PATH . '/application');

    // Define application environment
    defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

    // Define installation directory
    define('INSTALL_DIR', '/');

    // Define Site Url
    define('SITE_URL', 'http://'. $_SERVER['HTTP_HOST']. INSTALL_DIR);

    // Define Path for library folder
    define('LIBRARY_PATH',realpath(APPLICATION_PATH."/../library"));

    // website module specific
    if (!defined("FRONTEND_MODULE"))  define("FRONTEND_MODULE", "website"); // Frontend module

    if (!defined("WEBSITE_PATH"))  define("WEBSITE_PATH", BASE_PATH . "/" . FRONTEND_MODULE);

    if (!defined("LANGUAGE_PATH"))  define("LANGUAGE_PATH", APPLICATION_PATH . "/languages" );

    if (!defined("CONFIGURATION_DIRECTORY"))  define("CONFIGURATION_DIRECTORY", WEBSITE_PATH . "/var/configs");

    if (!defined("CACHE_DIRECTORY"))  define("CACHE_DIRECTORY", WEBSITE_PATH . "/var/cache");

    if (!defined("ASSET_DIRECTORY"))  define("ASSET_DIRECTORY", WEBSITE_PATH . "/var/assets");

    if (!defined("TEMPORARY_DIRECTORY"))  define("TEMPORARY_DIRECTORY", WEBSITE_PATH . "/var/temp");

    if (!defined("BACKUP_DIRECTORY"))  define("BACKUP_DIRECTORY", WEBSITE_PATH . "/var/backup");

    if (!defined("LANGUAGE_DIRECTORY"))  define("LANGUAGE_DIRECTORY", WEBSITE_PATH . "/var/languages");

    if (!defined("CSS_PATH"))  define("CSS_PATH", SITE_URL . "public/css");

    if (!defined("IMAGE_PATH"))  define("IMAGE_PATH", SITE_URL . "public/images");

    if (!defined("JAVASCRIPT_PATH"))  define("JAVASCRIPT_PATH", SITE_URL . "public/javascript");

    if (!defined("ASSET_PATH"))  define("ASSET_PATH", SITE_URL . FRONTEND_MODULE.  "/var/assets");

    if (!defined("EP"))  define("EP", "/var/log/error_pmanagement.html");


#==================================================================================
#  Load folders to set path.
#==================================================================================

    set_include_path(implode(PATH_SEPARATOR, array(
        realpath(LIBRARY_PATH),
        APPLICATION_PATH,
        APPLICATION_PATH . "/Models",
        get_include_path(),
    )));
#==================================================================================
#  Setup autoloader.
#==================================================================================

    /** Zend_Application */
    require_once 'Zend/Application.php';

    /** Helper functions for the application */
    include(APPLICATION_PATH . "/configs/helper.php");

    /** Zend Autoloader */
    include "Zend/Loader/Autoloader.php";
    $autoloader = Zend_Loader_Autoloader::getInstance();
    $autoloader->setFallbackAutoloader(true);
    $autoloader->suppressNotFoundWarnings(true);

#==================================================================================
#  Setup Frontend controller.
#==================================================================================

    // init front controller
    $front = Zend_Controller_Front::getInstance();

    // for frontend (default: website)
    $front->addControllerDirectory(WEBSITE_PATH . "/controllers", FRONTEND_MODULE);
    $front->setDefaultModule(FRONTEND_MODULE);

#==================================================================================
#  Add configuration.
#==================================================================================

    // Create application, bootstrap, and run
    $application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini'
    );

#==================================================================================
#  Run Basic setup.
#==================================================================================
    global $Database;
    require_once 'class/ArrayList.php';

    $Connection = new ConnectionConfig();
    $Database = $Connection->getConnection();

#==================================================================================
#  Run Application.
#==================================================================================

    $application->bootstrap()->run();

#==============================      End    =======================================
?>