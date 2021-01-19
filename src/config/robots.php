<?php

return [

    'default' => env('ROBOT_DEFAULT', 'default'),

    'level' => env('ROBOT_LEVEL', 200),

    /*
    |--------------------------------------------------------------------------
    | Robot limit allow send message.
    |--------------------------------------------------------------------------
    |
    */
    'rate_cache_key' => 'robot',
    'rate_allow' => [20, 60],

    'connections' => [

        'default' => [
            'driver' => 'ding',
            'base_uri' => '',
            'token' => '',
            'secret' => ''
        ],

    ],

];
