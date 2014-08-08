<?php

    /**
     * Class > Bootstrap
     *
     *  Bootstrap file execute after index file for all module.
     *  Setup and configure initial module and settings
     *
     *  Date: 4 Aug, 2014
     * 	@category Configuration
     *	@author Ankur Raiyani
     */


    class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
    {

        /**
         * Function _initDoctype
         * Defines content type and doctype of layout.
         *
         * Date: 4 Aug, 2014
         * @return Zend_View
         * @author Ankur Raiyani
         */

        protected function _initDoctype()
        {
            $view = new Zend_View();

            $view->doctype('XHTML1_STRICT');

            $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8');
            $view->headMeta()->appendName('keywords', "");
            $view->headMeta()->appendName('description', "");

            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
            $viewRenderer->setView($view);

            return $view;
        }



        /**
         * Function _initSiteModules
         * Setup module directory for different admin modules.
         *
         * Date: 4 Aug, 2014
         * @author Ankur Raiyani
         */

        protected function _initSiteModules()
        {
            //Don't forget to bootstrap the front controller as the resource may not been created yet...
            $this->bootstrap("frontController");
            $front = $this->getResource("frontController");

            //Add modules dirs to the controllers for default routes...
            $front->addModuleDirectory(APPLICATION_PATH . '/modules');
        }

    }

?>
