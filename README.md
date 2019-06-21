# Symfony pusher library

This Symfony library provides easy to use implementation of push sending mechanism.
Push are defined from the `PushTemplate` and are sent by calling the `PushService->sendPush($template)` method.

## Requirements

* `PHP >= 7.1`
* `symfony >= 4.0`

## Installation 

```
composer require beapp/pusher-core
```

Then you must choose one client to use with the core library.

Actually, there is only the firebase one, so you must include it :

```
composer require beapp/pusher-client-firebase
```

## Configuration (with Firebase Client)

You will need to some service configuration in order to use the `PushService` :

First, you must provide your api key to the `FirebaseClient` :

```
  Beapp\Push\Core\Client\Firebase\FirebaseClient:
    arguments:
      $apiKey: 'your_awesome_key'
```

And then, you must enable sending push by passing boolean value to `PushService` 
and provide some logger by tagging it :

```
  
  Beapp\Push\Core\PushService:
    arguments:
      $enabled: true
    tags:
      - { name: monolog.logger }
```


Now you're ready to use it !
