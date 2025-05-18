GET {{host}}/doc-examples
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Hello World! - My application</title>"
body contains "<h1>Hello World!</h1>"

GET {{host}}/doc-examples/api/ping
HTTP 200
Content-Type: application/json
[Asserts]
body contains "{\"ping\": \"pong\"}"

GET {{host}}/doc-examples/hello/World
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "Hello World!"

GET {{host}}/doc-examples/hello/Mike/20
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "Hello Mike! You are 20 years old."

GET {{host}}/doc-examples/hello/catchall/zack/is/nice!
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "Hello zack/is/nice!"

POST {{host}}/doc-examples/users
HTTP 200
Content-Type: application/json
[Asserts]
body contains "{\"updated\": true}"

GET {{host}}/doc-examples/htm-page
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>htm-page - My application</title>"
body contains "<h1>htm-page</h1>"

GET {{host}}/doc-examples/html-page
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>html-page - My application</title>"
body contains "<h1>html-page</h1>"

GET {{host}}/doc-examples/json-page
HTTP 200
Content-Type: application/json
[Asserts]
body contains "json-page"

GET {{host}}/doc-examples/markdown-page
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>markdown-page - My application</title>"
body contains "<h1>markdown-page</h1>"

GET {{host}}/doc-examples/md-page
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>md-page - My application</title>"
body contains "<h1>md-page</h1>"

GET {{host}}/doc-examples/php-page
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<h1>php-page</h1>"
