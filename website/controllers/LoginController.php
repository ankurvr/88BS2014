<?php
/**
 * Created by PhpStorm.
 * User: AnkurR
 * Date: 8/11/14
 * Time: 11:06 PM
 */

class LoginController extends Resource_Controller_Frontend
{
    public function indexAction()
    {

    }

    public function reactiveAction()
    {

    }

    public function logoutAction()
    {

    }

    public function validateAction()
    {
        $this->_helper->layout->disableLayout();
        $this->session = new Zend_Session_Namespace('WebsiteNamespace');
        $Username =  htmlentities($this->getRequest()->getParam("username",""));
        $Password = $this->getRequest()->getParam("password","");

        if($Username == "")
        {
            echo json_encode(array('result' => 'Error', 'msg' => "Admin_Login_Error_Blank_Username"));die;
        }
        else if($Password == "")
        {
            echo json_encode(array('result' => 'Error', 'msg' => "Admin_Login_Error_Blank_Password"));die;
        }

        $UserModel = new Users_Model();
        $Authenticate = new Authenticate_Model();

        // Validate username
        if($UserData = $UserModel->validateUser($Username))
        {
            if($UserData instanceof VO_TagUsers )
            {
                // Validate password
                if (!$Authenticate->validatePassword($Password, $UserData->getUserpassord()))
                {
                    echo json_encode(array('result' => 'Error', 'element' => 'password', 'msg' => parent::$Translate->_("Website_Login_Error_Invalid_Password", $this->view->Lang)));die;
                }
                else
                {
                    // Store username and password to cookie
                    /*if($this->getRequest()->getParam("remember",0) == 1) {
                        setcookie("cookie[UserName]", $Username, time()+60*60*24*30, '', SITE_URL);
                        setcookie("cookie[UserPass]", $Password, time()+60*60*24*30, '', SITE_URL);
                    }*/

                    $this->session->UserName = htmlentities($this->getRequest()->getParam("username",""));
                    $this->session->UserPass = $this->getRequest()->getParam("password","");

                    //Api_Model_Users::updateLoginAttempts($UserData->getUserid());

                    $this->session->UserObject = $UserData;
                    //Zend_Session::namespaceUnset("AdminNamespace");
                    echo json_encode(array('result' => "Success"));die;
                }
            }
        }
        else
        {
            echo json_encode(array('result' => 'Error', 'element' => 'username', 'msg' => "Website_Login_Error_Email_Id_not_exist"));die;
        }
    }
} 