# Route handler without HTTP method
GET {{host}}/route-handler/htm
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>htm.htm - My application</title>"
body contains "<h2>htm.htm</h2>"

GET {{host}}/route-handler/html
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>html.html - My application</title>"
body contains "<h2>html.html</h2>"

GET {{host}}/route-handler/json
HTTP 200
Content-Type: application/json
[Asserts]
body contains "json.json"

GET {{host}}/route-handler/markdown
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>markdown.markdown - My application</title>"
body contains "<h2>markdown.markdown</h2>"

GET {{host}}/route-handler/md
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>md.md - My application</title>"
body contains "<h2>md.md</h2>"

GET {{host}}/route-handler/php
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<h2>php.php</h2>"

# Route handler with HTTP method
GET {{host}}/route-handler-with-method/htm
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>htm.get.htm - My application</title>"
body contains "<h2>htm.get.htm</h2>"

GET {{host}}/route-handler-with-method/html
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>html.get.html - My application</title>"
body contains "<h2>html.get.html</h2>"

GET {{host}}/route-handler-with-method/json
HTTP 200
Content-Type: application/json
[Asserts]
body contains "json.get.json"

GET {{host}}/route-handler-with-method/markdown
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>markdown.get.markdown - My application</title>"
body contains "<h2>markdown.get.markdown</h2>"

GET {{host}}/route-handler-with-method/md
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>md.get.md - My application</title>"
body contains "<h2>md.get.md</h2>"

GET {{host}}/route-handler-with-method/php
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<h2>php.get.php</h2>"
