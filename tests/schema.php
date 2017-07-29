<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 29/07/17
 * Time: 08:12
 */

return [
    'redis'=>[
        'user'=>[
            'messages'=>'user:{id}:messages'
        ],
        'users'=>[
            'count'=>'users:{status}:{period}:count'
        ]
    ],
    'cache'=>[
        'user'=>[
            'profile'=>'user.{id}.profile'
        ]
    ],
    'events'=>[
        'subscription'=>'subscription-{type}-{event}'
    ],
    'channels'=>[
        'presence'=>[
            'user'=>'user:{id}:present'
        ]
    ]
];