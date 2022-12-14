<?php

namespace Staff\Controllers;

use Phalcon\Mvc\Controller ;
use Phalcon\Mvc\Dispatcher;
/**
 * ControllerBase
 * This is the base controller for all controllers in the application
 *
 * @property \Staff\Auth\Auth auth
 */
class ControllerBase extends Controller
{
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {

        $controllerName = $dispatcher->getControllerName();
        // Only check permissions on private controllers
        if ($this->acl->isPrivate($controllerName)) {

            // Get the current identity
            $identity = $this->auth->getIdentity();

            // If there is no identity available the user is redirected to index/index
//            if (!is_array($identity)) {
//
//                $this->flash->notice('You don\'t have access to this module: private');
//
//                $dispatcher->forward([
//                    'controller' => 'login',
//                    'action' => 'index'
//                ]);
//                return false;
//            }

            if(!$identity['profile']){
                $identity['profile'] = "Read-Only";
            }

            // Check if the user have permission to the current option
            $actionName = $dispatcher->getActionName();
            if (!$this->acl->isAllowed($identity['profile'], $controllerName, $actionName)) {

                $this->flash->notice('You don\'t have access to this module: ' . $controllerName . ':' . $actionName);

                if ($this->acl->isAllowed($identity['profile'], $controllerName, 'index')) {
                    $dispatcher->forward([
                        'controller' => $controllerName,
                        'action' => 'index'
                    ]);
                } else {
                    $dispatcher->forward([
                        'controller' => 'login',
                        'action' => 'index'
                    ]);
                }

                return false;
            }
        }
    }
}
