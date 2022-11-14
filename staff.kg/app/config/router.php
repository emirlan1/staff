<?php
/*
 * Define custom routes. File gets included in the router service definition.
 */

$router = new Phalcon\Mvc\Router();

$router = $di->getRouter();


$router->add('/', [
    'controller' => 'login',
    'action' => 'index'
]);

$router->add('/logout', [
    'controller' => 'login',
    'action' => 'logout'
]);
$router->add('/createUser', [
    'controller' => 'users',
    'action' => 'createUser'
]);
$router->add('/newHoliday', [
    'controller' => 'Nonworkdays',
    'action' => 'newHoliday'
]);
$router->add('/holidayRepeat', [
    'controller' => 'Nonworkdays',
    'action' => 'holidayRepeat'
]);
$router->add('/holidayDelete', [
    'controller' => 'Nonworkdays',
    'action' => 'holidayDelete'
]);
$router->add('/Nonworkdays', [
    'controller' => 'Nonworkdays',
    'action' => 'index'
]);
$router->add('/staff/{month}/{year}', [
    'controller' => 'staff',
    'action' => 'index'
]);
$router->add('/dinnerAndLate', [
    'controller' => 'staff',
    'action' => 'dinnerAndLate'
]);


$router->add('/startTime', [
    'controller' => 'staff',
    'action' => 'startTime'
]);
$router->add('/stopTime', [
    'controller' => 'staff',
    'action' => 'stopTime'
]);
$router->handle();
//return $router;