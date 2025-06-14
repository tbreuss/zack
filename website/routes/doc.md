## Documentation

<div id="toc"></div>

### Requirements

Zack! requirements are:

- PHP: 8.2 / 8.3 / 8.4
- Composer: 2.x

Composer `--no-dev` requirements are:

- symfony/dependency-injection: ^7.2
- symfony/event-dispatcher: ^7.2
- symfony/finder: ^7.2
- symfony/http-foundation: ^7.2
- symfony/http-kernel: ^7.2
- symfony/routing: ^7.2
- twig/markdown-extra: ^3.21
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
<h1>Hello World!</h1>
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

### Folder Structure

A typical project folder structure looks like the following:

~~~text
project/                # Project root folder on your server
├─ cache/               # Cached files
├─ config/              # Config files
├─ logs/                # Log files
├─ routes/              # Routes for your website
│  └─ index.get.html    # The only route in this example
├─ vendor/              # Composer dependencies
├─ views/               # Twig templates
│  ├─ base.html.twig    # Twig base layout file
│  └─ error.html.twig   # Twig file for displaying errors
└─ web/                 # Web server public folder
   ├─ assets/           # Asset files like css or js
   └─ index.php         # Website bootstrap file
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
│  └─ test.patch.php   # PATCH /api/test
├─ index.php           # ANY   /
├─ contact.get.php     # GET   /contact
└─ contact.post.php    # POST  /contact
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

The content of the HTML file is taken.
The Twig layout is determined via the layout comment `<!-- layout: my-layout.html.twig -->` in the HTML content.
The page title is determined by the H1-H3 headings in the HTML content.
The layout is applied and output together with the page title and the HTML content.

#### JSON Route Handler

File extension: json \
Response content-type: application/json

The content of the JSON file is read and output.

#### Markdown Route Handler

File extensions: markdown, md \
Response content-type: text/html

The content of the Markdown file is taken.
The markdown is converted to HTML using one of the following Composer packages:

- league/commonmark
- michelf/php-markdown
- erusev/parsedown

The Twig layout is determined via the layout comment `<!-- layout: my-layout.html.twig -->` in the HTML content.
The page title is determined by the H1-H3 headings in the HTML content.
The layout is applied and output together with the page title and the HTML content.

#### PHP Route Handler

File extension: php \
Response content-type: text/html, application/json, or other

The content-type of the response can be set explicitly in a PHP route handler.

##### Echoing Content

The echoed content of the PHP file is taken.

If the HTML content contains a `html` element or a `Doctype`, the HTML content is taken as is.

Otherwise the Twig layout is determined via the layout comment `<!-- layout: my-layout.html.twig -->` in the HTML content.
The page title is determined by the H1-H3 headings in the HTML content.
The layout is applied and output together with the page title and the HTML content.

##### Returning Response

If you want finer control over the HTTP response, you can return a string, an array or a `Symfony\Component\HttpFoundation\Response` object.

If the return value is a string, it is output as is with a `text/html` content-type.

If return value is an array, it is JSON encoded and output with a `application/json` content-type.

If return value is a `Response` object, it is output as is.

### Events

#### Zack! Events

Zack! ships with the following events:

- **zack.container**: This event is dispatched after the container has been built.
- **zack.controller**: This event is dispatched just before the controller (i.e. the route handler) is determined.
- **zack.routes**: This event is dispatched after the routes have been built.

#### HttpKernel Events

Zack! supports the following Symfony HttpKernel events:

- **kernel.controller**: This event is dispatched very early, before the controller is determined.
- **kernel.controller_arguments**: This event is dispatched after the controller has been resolved but before executing it.
- **kernel.view**: This event is dispatched just before a controller is called. 
- **kernel.response**: This event is dispatched after the controller or any kernel.view listener returns a Response object.
- **kernel.finish_request**: This event is dispatched after the kernel.response event.
- **kernel.terminate**: This event is dispatched after the response has been sent (after the execution of the handle() method). 
- **kernel.exception**: This event is dispatched as soon as an error occurs during the handling of the HTTP request.

Read [Built-in Symfony Events](https://symfony.com/doc/current/reference/events.html#kernel-events) for more information.

### Development Environment

#### Create Docker Image

Create Docker image based on the latest supported PHP version

    docker build -t zack https://github.com/tbreuss/zack.git

Optionally you can also use an older PHP version

    docker build --build-arg PHP_VERSION=8.2 -t zack https://github.com/tbreuss/zack.git
    docker build --build-arg PHP_VERSION=8.3 -t zack https://github.com/tbreuss/zack.git

#### Run Website

Clone project

    git clone https://github.com/tbreuss/zack.git

Change directory

    cd zack

Install packages

    docker run --rm -it -v .:/app zack composer install

Run website

    docker run --rm -v .:/app -p 8888:8888 zack php -S 0.0.0.0:8888 -t /app/website/web

### Testing

#### Coding Style

Fix coding style issues using [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)

    ./bin/coding-style.sh

#### Static Code Analysis

Analyse code using [PHPStan](https://phpstan.org/)

    ./bin/code-analysis.sh

#### Functional Tests

Run functional tests using [Hurl](https://hurl.dev/)

    ./bin/functional.sh localhost:9330

#### Website Tests

Run website tests using [Hurl](https://hurl.dev/)

    ./bin/website.sh localhost:9331
