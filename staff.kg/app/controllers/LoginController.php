<?php

namespace Staff\Controllers;

//use Phalcon\Mvc\Controller;

use Staff\Auth\Exception as AuthException;
use Staff\Forms\LoginForm;
use Staff\Models\Times;
use Staff\Models\Users;


class LoginController extends ControllerBase
{

    public function indexAction()
    {
        $form = new LoginForm();
        try {
            if ($this->request->isPost()) {
                if ($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->warning($message);
                    }
                } else {
                    $this->auth->check([
                        'login' => $this->request->getPost('login'),
                        'password' => $this->request->getPost('password')
                    ]);
                    $this->flash->success('all good');
                }
            }
        } catch (AuthException $e) {
         $this->flash->error($e->getMessage());
        }
        $this->view->form = $form;
    }
    public function logoutAction()
    {
        $this->auth->remove();
        $this->response->redirect('');
    }
}