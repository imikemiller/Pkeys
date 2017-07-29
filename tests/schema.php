<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 29/07/17
 * Time: 08:12
 */

return [
    /*
     * Real world schema usage examples.
     */
    'schema'=>[
        'redis'=>[
            'user'=>[
                'messages'=>'user:{id|numeric}:messages'
            ],
            'users'=>[
                'count'=>'users:{status}:{day|date}:count'
            ]
        ],
        'cache'=>[
            'user'=>[
                'profile'=>'user.{id}.profile'
            ]
        ],
        'events'=>[
            'subscription'=>'subscription-{type|alpha}-{event}'
        ],
        'channels'=>[
            'presence'=>[
                'user'=>'user:{id}:present'
            ]
        ]
    ],
    /*
     * Optionally set the delimiters the parser will use.
     */
    'delimiters'=>[
        '~',':','*'
    ]
];