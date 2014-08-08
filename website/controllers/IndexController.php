<?php
/**
 * Created by PhpStorm.
 * User: rama
 * Date: 4/8/14
 * Time: 3:17 PM
 */

class IndexController extends Resource_Controller_Frontend
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $_SESSION['asdasd'] = 'dasdasdasd';
    }

    public function testAction()
    {
        echo 'this is from test action';die;
    }
} 