<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->assets->addJs('js/login.js');
    }

}

