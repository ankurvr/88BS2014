<?php

    class ErrorController extends Zend_Controller_Action
    {

        public function errorAction()
        {

            $errors = $this->_getParam('error_handler');
            $this->_helper->viewRenderer->setNoRender(true);

            switch ($errors->type) {

                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
                    $this->_helper->layout()->disableLayout();
                    $this->view->message = '404: Not Found';
                    $this->getResponse()->setHttpResponseCode(404);
                    $this->render('error');
                    break;
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                    // 404 error -- controller or action not found
                    $this->getResponse()->setHttpResponseCode(404);
                    $this->view->message = '404: Not Found';
                    //$this->view->message = $errors->exception;//'Application error';
                    $this->render('error');
                    break;
                default:
                    // application error
                    $this->getResponse()->setHttpResponseCode(500);
                    $this->view->message = '500: An unexpected error occurred';
                    $this->render('error');
                    //$this->view->message = $errors->exception;//'Application error';
                    break;
            }

            // Log exception, if logger available
            if ($log = $this->getLog()) {
                $log->crit($this->view->message, $errors->exception);
            }

            // conditionally display exceptions
            if ($this->getInvokeArg('displayExceptions') == true) {
                $this->view->exception = $errors->exception;
            }

            $this->view->request   = $errors->request;

        }

        public function getLog()
        {
            $bootstrap = $this->getInvokeArg('bootstrap');
            if (!$bootstrap->hasPluginResource('Log')) {
                return false;
            }
            $log = $bootstrap->getResource('Log');
            return $log;
        }


    }
?>
