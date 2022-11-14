<?php

namespace Staff\Controllers;

use Phalcon\Mvc\Controller;

use Staff\Auth\Exception as AuthException;
use Staff\Forms\LoginForm;
use Staff\Models\Times;
use Staff\Models\Users;


class UsersController extends Controller
{
    public function initialize()
    {
      //      $this->view->setTemplateBefore('private');
    }

    /**
     * Default action, shows the search form
     */
//    public function indexAction()
//    {
//        //$this->persistent->conditions = null;
//       // $this->view->form = new UsersForm();
//    }
    public function createUserAction(){

    }

}