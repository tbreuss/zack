# Route handler without HTTP method
GET {{host}}/route-handler/htm
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>htm.htm - Zack!</title>"
body contains "<h1>htm.htm</h1>"

GET {{host}}/route-handler/html
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>html.html - Zack!</title>"
body contains "<h1>html.html</h1>"

GET {{host}}/route-handler/json
HTTP 200
Content-Type: application/json
[Asserts]
body contains "json.json"

GET {{host}}/route-handler/markdown
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>markdown.markdown - Zack!</title>"
body contains "<h1>markdown.markdown</h1>"

GET {{host}}/route-handler/md
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>md.md - Zack!</title>"
body contains "<h1>md.md</h1>"

GET {{host}}/route-handler/php
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<h1>php.php</h1>"

# Route handler with HTTP method
GET {{host}}/route-handler-with-method/htm
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>htm.get.htm - Zack!</title>"
body contains "<h1>htm.get.htm</h1>"

GET {{host}}/route-handler-with-method/html
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>html.get.html - Zack!</title>"
body contains "<h1>html.get.html</h1>"

GET {{host}}/route-handler-with-method/json
HTTP 200
Content-Type: application/json
[Asserts]
body contains "json.get.json"

GET {{host}}/route-handler-with-method/markdown
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>markdown.get.markdown - Zack!</title>"
body contains "<h1>markdown.get.markdown</h1>"

GET {{host}}/route-handler-with-method/md
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>md.get.md - Zack!</title>"
body contains "<h1>md.get.md</h1>"

GET {{host}}/route-handler-with-method/php
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<h1>php.get.php</h1>"
