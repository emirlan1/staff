<?php

use Phalcon\Config;
use Phalcon\Logger;

return new Config([
    'privateResources' => [
        'users' => [
          'changePassword',
        ],
        'permissions' => [
            'index'
        ],
        'staff' => [
            'index',
            'startTime',
            'stopTime',
            'dinner',
            'dinnerAndLate',
            'holiday'
        ],
        'nonworkdays' => [
            'index',
            'holidayRepeat',
            'holidayDelete'
        ]

    ]
]);
