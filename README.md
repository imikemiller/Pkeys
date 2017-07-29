# Pkeys
A key management library to keep your key strings consistent, to avoid your team mates duplicating or overwriting your keys, to protect against typo's and unexpected data types and to stop the crime of inlining keys in your code.

## What is it for?
Initially it was a solution to managing Redis keys to avoid keys being duplicated or overwritten but then the solution found a place in the managment of key strings for analytics events, cache items, realtime messaging channels or really anything that is identified by a key string. 

For example maybe you have the need to store a count of users active in a particular day. You stick it in Redis under the key `users:active:20170801` and move on with your life. Little did you know your team mate needs the same thing and has stuck it under `active:users:20170801`. This is hard to spot scattered around your code base. By storing all keys in the Pkeys schema you and your team mates can see any existing keys and avoid duplicating them.

A similar issue can arise when you misspell or forget the key you have previously used. Perhaps you need to store all the messages for a user under the key `user:21:messages`. You will need to do SADD and SREM (or similar CRUD operations) in different places in your code. If you misspell your key Redis wont tell you and it can be tricky to debug at 11pm when your eyes are misting over.

These same problems will arise with realtime messaging channels (PubNub, Pusher, Ably), cache keys (Memcache, Redis), events (Segment, Facebook or Google) and session storage. Its on you to make sure you have consitently used your key strings.

## How does Pkeys solve these problems?
it errors at you! If you misspell your key index or you forget to pass in a required parameter, or the parameter is of an unexpected data type Pkeys will throw an exception making it much harder to make the silent mistakes badly described above.

By storing your keys in a schema it makes consistency easier and makes it clear what is already being stored to your team mates and stops you overwriting each others keys.

## How to

### Install

### Use

