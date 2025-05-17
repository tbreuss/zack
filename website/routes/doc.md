## Documentation

### Requirements

Zack! requirements are:

- PHP: 8.2 / 8.3 / 8.4
- Composer: 2.x

Composer `--no-dev` requirements are:

- league/commonmark: ^2.6
- symfony/dependency-injection: ^7.2
- symfony/event-dispatcher: ^7.2
- symfony/finder: ^7.2
- symfony/http-foundation: ^7.2
- symfony/http-kernel: ^7.2
- symfony/routing: ^7.2
- twig/twig: ^3.20

### Installation

Create a new project folder and change into it.

~~~bash
mkdir myproject
cd myproject
~~~

Install Zack! using Composer:

~~~bash
composer require tebe/zack:dev-main
~~~

In your `myproject` folder add the following folders and files:

~~~text
myproject/
├─ routes/
│  └─ index.get.html
└─ web/
   └─ index.php
~~~

Add the following content to the files:

routes/index.get.html

~~~html
<h1>Hello Zack!</h1>
~~~

web/index.php

~~~php
<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$config = [
    'basePath' => dirname(__DIR__),
];

(new tebe\zack\Zack($config))->run();
~~~

Start PHP's built-in web server:

~~~bash
cd myproject
php -S localhost:8888 -t web
~~~

Open <http://localhost:8888> with your preferred web browser.

### Project Folder Structure

A typical project folder structure looks like the following:

~~~text
project/                     <- Project root folder on your server
├─ cache/                    <- Folder with cached files
├─ config/                   <- Folder with config files
├─ logs/                     <- Folder with log files
├─ routes/                   <- Folder with routes for your website
│  └─ index.get.html         <- The only route in this example
├─ vendor/                   <- Folder with Composer dependencies
├─ views/                    <- Folder with twig templates
│  ├─ base.html.twig         <- Twig base layout file
│  └─ error.html.twig        <- Twig file for displaying errors
└─ web/                      <- Web server public folder
   ├─ assets/                <- Folder with asset files like css or js
   └─ index.php              <- Website bootstrap file
~~~

Normally you only work in the `routes` and `views` folders.

### File-Based Routing

Zack! is using file-based routing for your routes. 
Files are automatically mapped to Symfony routes. 
Defining a route is as simple as creating a file inside the `routes` directory.

You can only define one handler per files and you can append the HTTP method to the filename to define a specific request method.
If no method is specified, the route applies to all methods.

~~~text
routes/
├─ api/
│  └─ test.patch.php <- PATCH /api/test
├─ index.php         <- ANY   /
├─ contact.get.php   <- GET   /contact
└─ contact.post.php  <- POST  /contact
~~~

You can nest routes by creating subdirectories.

~~~text
routes/
├─ communities/
│  ├─ index.get.php
│  ├─ index.post.php
│  └─ [id]/
│     ├─ index.get.php
│     └─ index.post.php
├─ hello.get.php
└─ hello.post.php
~~~

#### Simple Routes

First, create a file in `routes` directory.
The filename will be the route path.

Then, create a file that returns a JSON response.
This file will be executed when the route is matched.

~~~php
#routes/api/ping.php

<?php

use Symfony\Component\HttpFoundation\Response;

return new Response('{"ping": "pong"}', 200, [
    'Content-Type' => 'application/json',
]);
~~~

#### Route With Params

##### Single Param

To define a route with params, use the `[<param>]` syntax where `<param>` is the name of the param.
The param will be available in the `$request->attributes` object.

~~~php
#routes/hello/[name].php

<?php

use Symfony\Component\HttpFoundation\Response;

$name = $request->attributes->get('name');

return new Response('Hello ' . $name . '!', 200);
~~~

Call the route with the param `/hello/zack`, you will get:

~~~text
#Response

Hello zack!
~~~

##### Multiple Params

You can define multiple params in a route using `[<param1>]/[<param2>]` syntax where each param is a folder.
You cannot define multiple params in a single filename of folder.

~~~php
#routes/hello/[name]/[age].php

<?php

use Symfony\Component\HttpFoundation\Response;

$name = $request->attributes->get('name');
$age = $request->attributes->get('age');

return new Response("Hello $name! You are $age years old.", 200);
~~~

##### Catch All Params

You can capture all the remaining parts of a URL using `[...<param>]` syntax. This will include the `/` in the param.

~~~php
#routes/hello/[...name].php

<?php

use Symfony\Component\HttpFoundation\Response;

$name = $request->attributes->get('name');

return new Response("Hello $name!", 200);
~~~

Call the route with the param `/hello/zack/is/nice`, you will get:

~~~txt
#Response

Hello zack/is/nice!
~~~

#### Specific Request Method

You can append the HTTP method to the filename to force the route to be matched only for a specific HTTP request method.
For example `hello.get.php` will only match for GET requests. 
You can use any HTTP method you want.

Example with POST method.

~~~php
# routes/users.post.php

<?php

use Symfony\Component\HttpFoundation\Response;

// Do something with body like saving it to a database

return new Response('{"updated": true}', 200, [
    'Content-Type' => 'application/json',
]);
~~~

#### Catch All Route

You can create a special route that will match all routes that are not matched by any other route.
This is useful for creating a default route.

To create a catch all route, create a file named `[...].php` in the `routes` directory.

~~~php
#routes/[...].php

<?php

use Symfony\Component\HttpFoundation\Response;

$path = $request->attributes->get('path');

return new Response("Hello $path!", 200);
~~~

### Route Handler

You can use the file extension of a route file to force the route to be handled by a specific route handler.

~~~txt
routes/
├─ htm-page.htm
├─ html-page.html
├─ json-page.json
├─ md-page.md
├─ markdown-page.markdown
└─ php-page.php
~~~

Zack! is currently delivered with the following route handlers:

#### HTML Route Handler

File extensions: htm, html \
Response content-type: text/html

#### JSON Route Handler

File extension: json \
Response content-type: application/json

#### Markdown Route Handler

File extensions: markdown, md \
Response content-type: text/html

#### PHP Route Handler

File extension: php \
Response content-type: text/html

The content-type of the response can be set explicitly in a PHP route handler.

### Events

#### Zack! Events

Zack! ships with the following events:

- **zack.container**: This event is dispatched after the container has been built.
- **zack.controller**: This event is dispatched just before the controller (i.e. the route handler) is determined.
- **zack.routes**: This event is dispatched after the routes have been built.

#### Symfony HttpKernel Events

Zack! supports the following Symfony HttpKernel events:

- **kernel.controller**: This event is dispatched very early, before the controller is determined.
- **kernel.controller_arguments**: This event is dispatched after the controller has been resolved but before executing it.
- **kernel.view**: This event is dispatched just before a controller is called. 
- **kernel.response**: This event is dispatched after the controller or any kernel.view listener returns a Response object.
- **kernel.finish_request**: This event is dispatched after the kernel.response event.
- **kernel.terminate**: This event is dispatched after the response has been sent (after the execution of the handle() method). 
- **kernel.exception**: This event is dispatched as soon as an error occurs during the handling of the HTTP request.

Read [Built-in Symfony Events](https://symfony.com/doc/current/reference/events.html#kernel-events) for more information.
