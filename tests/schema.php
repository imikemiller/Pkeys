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
                /*
                 * Must have the param `id` passed in and must be numeric
                 */
                'messages'=>'user:{id|numeric}:messages'
            ],
            'users'=>[
                /*
                 * Must have params `status` and `day` passed in.
                 * `status` must be either "active","new" or "returning"
                 * `day` must be a valid date
                 */
                'count'=>'users:{status|in:active,new,returning}:{day|date}:count'
            ]
        ],
        'cache'=>[
            'user'=>[
                /*
                 * Must have param `id` and will accept any value
                 */
                'profile'=>'user.{id}.profile'
            ]
        ],
        'events'=>[
            /*
             * Must have the `type` param passed in which must be pure alpha chars
             * Optionally requires the `event` param which must be either "active","renewed" or "cancelled"
             */
            'subscription'=>'subscription-{type|alpha}-{event|in:active,renewed,cancelled?}'
        ],
        'channels'=>[
            'presence'=>[
                /*
                 * Must have the `id` and `state` params passed in.
                 * `state` must be either "enter" or "leave"
                 */
                'user'=>'user-{id}-presence-{state|in:enter,leave}'
            ]
        ],
        /*
         * Unit testing keys
         */
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
     * These allow the parser to tidy up any doubled up delimiters and to trim the key when optional params are used.
     */
    'delimiters'=>[
        '~',':','*','.','-'
    ]
];