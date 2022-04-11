<?php
return [

    "cache" => [
        "default" => 'redis',

        "redis" =>
            [
                "host" => 'redis',
                "port" => 6379
            ],

        "memcached" =>
            [
                "host" => 'memcached',
                "port" => 11211
            ]
    ]
];