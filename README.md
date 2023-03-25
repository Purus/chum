# Links
https://doeken.org/blog/using-symfony-mailer-without-framework
https://doeken.org/blog/event-dispatching-exploration
https://www.joyk.com/dig/detail/1608040717711209 - slim email
https://www.joyk.com/dig/detail/1608040671521008 - slikm filepond upload
https://www.joyk.com/dig/detail/1610926592369097 - spam check
https://www.joyk.com/dig/detail/1624529859783988 - slim console
https://github.com/selective-php/validation
https://www.joyk.com/dig/detail/1608040664302401 - validation
https://www.joyk.com/dig/detail/1608040713673416 - twig extensions
https://www.joyk.com/dig/detail/1608040755507388 - webpack
https://github.com/DavidePastore/Slim-Config
https://github.com/slimphp/Slim-Flash
https://github.com/vimeo/psalm
https://github.com/phpstan/phpstan
https://github.com/squizlabs/PHP_CodeSniffer
https://github.com/yangsuda/slimcms
https://github.com/svanlaere/slim4setup - pdo
https://www.youtube.com/watch?v=dPVotANrEYI - tailwind simple
https://www.joyk.com/dig/detail/1608040736234854 - query transaction

# Commands

```
docker build -t chum:latest .
docker run -d -p 80:80 chum:latest
```

```
npx tailwindcss -i ./input.css -o ./assets/css/output.css
```

```
composer dump-autoload -o
```

# TODO

- Social Login
- PhpStan Integration
- monolog/monolog Integration
- filepond
- mailer events
- respect/validation
- https://image.intervention.io/v2

## Retrieving the current route

$route = \Slim\Routing\RouteContext::fromRequest($request)->getRoute();

## Retrieving the current route arguments
$routeArguments = \Slim\Routing\RouteContext::fromRequest($request)
    ->getRoute()
    ->getArguments();

## Accessing the RouteParser
$routeParser = \Slim\Routing\RouteContext::fromRequest($request)->getRouteParser();

## Retrieving the base path
$basePath = \Slim\Routing\RouteContext::fromRequest($request)->getBasePath(),

## Reading the response body
$body = (string)$request->getBody();
If the request body is still empty, it could be an bug or an issue with chunked requests:

https://www.jeffgeerling.com/blog/2017/apache-fastcgi-proxyfcgi-and-empty-post-bodies-chunked-transfer

## Receiving input
To receive the submitted JSON / XML data you have to add the BodyParsingMiddleware:

$app = AppFactory::create();

$app->addBodyParsingMiddleware(); // <--- here

// ...

$app->run();
Notice: The BodyParsingMiddleware will only parse the body if the request header Content-Type contains a supported value. Supported values are:

application/json
application/x-www-form-urlencoded
application/xml
text/xml
The BodyParsingMiddleware also supports PUT requests.

More details: https://akrabat.com/receiving-input-into-a-slim-4-application/