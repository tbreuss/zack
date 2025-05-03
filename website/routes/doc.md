## Documentation

### Requirements

TBD

### Installation

TBD

### Routing

Zack! is using file-based routing for your routes. 
Files are automatically mapped to Symfony routes. 
Defining a route is as simple as creating a file inside the `routes` directory.

You can only define one handler per files and you can append the HTTP method to the filename to define a specific request method.

    routes/
      api/
        test.php          <-- /api/test
      index.get.php       <-- GET /
      contact.get.php     <-- GET /contact
      contact.post.php    <-- POST /contact

You can nest routes by creating subdirectories.

    routes/
      communities/
        index.get.php
        index.post.php
        [id]/
          index.get.php
          index.post.php
      hello.get.php
      hello.post.php

#### Simple routes

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

#### Route with params

##### Single param

To define a route with params, use the `[<param>]` syntax where `<param>` is the name of the param.
The param will be available in `$request->attributes` object.

~~~php
#routes/hello/[name].php

<?php

use Symfony\Component\HttpFoundation\Response;

$name = $request->attributes->get('name');

return new Response('Hello ' . $name . '!', 200);
~~~

Call the route with the param /hello/zack, you will get:

~~~text
#Response

Hello nitro!
~~~

##### Multiple params

You can define multiple params in a route using `[<param1>]/[<param2>]` syntax where each param is a folder.
You cannot define multiple params in a single filename of folder.

~~~php
#routes/hello/[name]/[age].php

<?php

use Symfony\Component\HttpFoundation\Response;

$name = $request->attributes->get('name');
$age = $request->attributes->get('age');

return new Response("Hello ${name}! You are ${age} years old.", 200);
~~~

##### Catch all params

You can capture all the remaining parts of a URL using `[...<param>]` syntax. This will include the `/` in the param.

~~~php
#routes/hello/[...name].php

<?php

use Symfony\Component\HttpFoundation\Response;

$name = $request->attributes->get('name');

return new Response("Hello ${name}!", 200);
~~~

Call the route with the param `/hello/zack/is/nice`, you will get:

~~~txt
#Response

Hello zack/is/nice!
~~~

#### Specific request method

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

#### Catch all route

You can create a special route that will match all routes that are not matched by any other route.
This is useful for creating a default route.

To create a catch all route, create a file named `[...].php` in the `routes` directory.

~~~php
#routes/[...].php

<?php

use Symfony\Component\HttpFoundation\Response;

$path = $request->attributes->get('path');

return new Response("Hello ${path}!", 200);
~~~

### Route Handler

TBD

### Events

TBD
