<?php

return [

    'default' => env('ROBOT_DEFAULT', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Robot limit allow send message.
    |--------------------------------------------------------------------------
    |
    */
    'rate_cache_key' => 'robot',
    'rate_allow' => [2, 1],

    'connections' => [

        'default' => [
            'driver' => 'ding',
            'base_uri' => '',
            'token' => '',
            'secret' => ''
        ],

    ],

];
