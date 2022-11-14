<?php
namespace Staff\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;

class LoginForm extends Form
{
    public function initialize()
    {
        // login
        $login = new Text('login', [
            'placeholder' => 'login',
            'class' => 'form-control'
        ]);

        $login->addValidators([
            new PresenceOf([
                'message' => 'The e-mail is required'

            ]),
        ]);

        $this->add($login);

        // Password
        $password = new Password('password', [
            'placeholder' => 'Password',
            'class' => 'form-control'
        ]);

        $password->addValidator(new PresenceOf([
            'message' => 'The password is required'
        ]));

        $password->clear();

        $this->add($password);

        // Remember

        // CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(new Identical([
            'value' => $this->security->getSessionToken(),
            'message' => 'CSRF validation failed'
        ]));

        $csrf->clear();

        $this->add($csrf);

        $this->add(new Submit('submit', [
            'class' => 'btn btn-success'
        ]));
    }
    public function getCsrfName()
    {
        if (empty($this->_csrf)) {
            $this->_csrf = $this->security->getTokenKey();
        }

        return $this->_csrf;
    }
}
