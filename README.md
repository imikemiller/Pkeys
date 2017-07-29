# Pkeys
A framework agnostic key management library to keep your key strings consistent, to avoid your team mates duplicating or overwriting your keys, to protect against typo's and unexpected data types and to stop the crime of inlining keys in your code.

## What is it for?
Initially it was a solution for managing Redis keys to avoid keys being duplicated or overwritten, but then the solution found a place in the managment of key strings for analytics events, cache items, realtime messaging channels or really anything that is identified by a key string. 

For example maybe you have the need to store a count of users active in a particular day. You stick it in Redis under the key `users:active:20170801` and move on with your life. Little did you know your team mate needs the same thing and has stuck it under `active:users:20170801`. This is hard to spot scattered around your code base. By storing all keys in the Pkeys schema you and your team mates can see any existing keys and avoid duplicating them.

A similar issue can arise when you misspell or forget the key you have previously used. Perhaps you need to store all the messages for a user under the key `user:21:messages`. You will need to do SADD and SREM (or similar CRUD operations) in different places in your code. If you misspell your key Redis wont tell you and it can be tricky to debug at 11pm when your eyes are misting over.

These same problems will arise with realtime messaging channels (PubNub, Pusher, Ably), cache keys (Memcache, Redis), events (Segment, Facebook or Google) and session storage. Its on you to make sure you have consitently used your key strings.

## How does Pkeys solve these problems?
it errors at you! If you misspell your key index or you forget to pass in a required parameter, or the parameter is of an unexpected data type Pkeys will throw an exception making it much harder to make the mistakes described above.

By storing your keys in a schema it makes consistency easier and makes it clear what is already being stored to your team mates and stops you overwriting each others keys. It also allows you to see a consolidated list of all your events and messaging channels and makes sure they are clean and consistent. And finally if you need to change a key due to a clash then its a simple change in the schema and you don't need to scan your code to refactor out each incorrect key. 

## How to

### Install

### Use

#### Define Schema
First off define your schema. Keys are accessible by referring to their array path using the array dot notation eg `redis.user.messages`. Parameters are defined using curly brackets `{}`. They can be optional by including a `?` and can be validated by seperating the parameter name with the validation rule by using a pipe `|`. See below for realworld example schema and list of available validation rules.

NOTE: Your schema must include an array under the key `schema` and can optionally include an array of delimiters under the key `delimiters`. By default Pkeys allows the following delimiters `:` `.` `-`

#### Generate Key
Once your schema has been defined it can be loaded into Pkeys by passing in the schema file path to the constructor. 

  `$pkey = new Pkey('path/to/schema.php');`

And a key object can be generated from the schema by doing the following:

  `$key = $pkey->make('redis.user.messages',['id'=>21]);`

Pkeys will stringify and can usually be passed straight into whichever client library you are using. Otherwise to get the key string call:
 
  `$key->getKey();`
  
That is it! A simple solution to an annoying problem.

#### Available Validation Rules

 * `alpha`: Ensures the param contains only alpha chars.
 * `alhpaNum`: Ensures the param contains only alpha and numeric chars.
 * `numeric`: Ensures the param is numeric.
 * `date`: Ensures the param is a valid date.
 * `email`: Ensures the param is a valid email.
 * `in`: Ensures the param is inside a CSV list of acceptable options
 * `notIn`: Ensures the param is not inside a CSV list
 * `json`: Ensures the param is a valid JSON - its sometimes handy to use a full JSON as a key in Redis.
 
#### Custom Validation
If you need to add a custom validator you can extend the existing `\Pkeys\Validation\ValidationRules` class or you can write your own that implmenents the `\Pkeys\Interfaces\ValidatorInterface` and pass it into the Pkey constructor.

  `$pkey = new Pkey('path/to/schema.php',$customValidator);`  

Validation rules in the schema should reference methods on the validator class.

#### Example Schema 
```return [
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
        ]
    ],
    /*
     * Optionally set the delimiters the parser will use.
     
     * These allow the parser to tidy up any doubled up delimiters and to trim the key.
     */
    'delimiters'=>[
        '~',':','*'
    ]
