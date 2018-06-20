<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cache System
    |--------------------------------------------------------------------------
    |
    */
    'default' => 'transient',
    'stores' => [

        'transient' => [
            'driver' => 'transient'
        ],

        'file' => [
            'driver' => 'file',
            'path' => ROOT_DIR . '/runtime/cache'
        ]

    ],

];
