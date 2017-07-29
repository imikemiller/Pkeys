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
                'count'=>'users:{status|in:active,new,returning}:{day|date}:count'
            ]
        ],
        'cache'=>[
            'user'=>[
                'profile'=>'user.{id}.profile'
            ]
        ],
        'events'=>[
            'subscription'=>'subscription-{type|alpha}-{event|in:active,renewed,cancelled?}'
        ],
        'channels'=>[
            'presence'=>[
                'user'=>'user-{id}-presence-{state|in:enter,leave}'
            ]
        ],
        'test'=>[
            'custom'=>
                [
                    'success'=>'user~{id|customSuccess}',
                    'fail'=>'user~{id|customFail}'
                ]
        ]
    ],
    /*
     * Optionally set the delimiters the parser will use.
     */
    'delimiters'=>[
        '~',':','*','.','-'
    ]
];