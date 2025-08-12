# Route handler PHP
GET {{host}}/route-handler/php/echo-string-doctype
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body not contains "<html"
body contains "<h2>echo-string-doctype.get</h2>"

GET {{host}}/route-handler/php/echo-string-html-tag
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body not contains "<!doctype"
body contains "<h2>echo-string-html-tag.get</h2>"

GET {{host}}/route-handler/php/echo-string-layout-default
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<!doctype"
body contains "<html"
body contains "<h2>echo-string-layout-default.get</h2>"

GET {{host}}/route-handler/php/echo-string-layout-test
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<!doctype"
body contains "<html"
body contains "<h2>echo-string-layout-test.get</h2>"
body contains "<h2>test.html.twig</h2>"

GET {{host}}/route-handler/php/echo-string
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>echo-string.get - My application</title>"
body contains "<h2>echo-string.get</h2>"

GET {{host}}/route-handler/php/return-array
HTTP 200
Content-Type: application/json; charset=UTF-8
[Asserts]
jsonpath "$.value" == "Return array value"

GET {{host}}/route-handler/php/return-invalid
HTTP 500
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Error 500 - My application</title>"
body contains "<h2>Error 500</h2>"
body contains "<p>The PHP file must output something or return a string, an array or a response object: /routes/route-handler/php/return-invalid.get.php</p>"

GET {{host}}/route-handler/php/return-response
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "Return response value"

GET {{host}}/route-handler/php/return-string
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Return string value - My application</title>"
body contains "<h2>Return string value</h2>"

# Route handler with HTTP method
GET {{host}}/route-handler/with-method/htm
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>htm.get.htm - My application</title>"
body contains "<h2>htm.get.htm</h2>"

GET {{host}}/route-handler/with-method/html
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>html.get.html - My application</title>"
body contains "<h2>html.get.html</h2>"

GET {{host}}/route-handler/with-method/json
HTTP 200
Content-Type: application/json; charset=UTF-8
[Asserts]
jsonpath "$" == "json.get.json"

GET {{host}}/route-handler/with-method/markdown
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>markdown.get.markdown - My application</title>"
body contains "<h2>markdown.get.markdown</h2>"

GET {{host}}/route-handler/with-method/md
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>md.get.md - My application</title>"
body contains "<h2>md.get.md</h2>"

GET {{host}}/route-handler/with-method/php
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>php.get.php - My application</title>"
body contains "<h2>php.get.php</h2>"

GET {{host}}/route-handler/with-method/text
HTTP 200
Content-Type: text/plain; charset=UTF-8
[Asserts]
body contains "text.get.txt"

# Route handler without HTTP method
GET {{host}}/route-handler/without-method/htm
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>htm.htm - My application</title>"
body contains "<h2>htm.htm</h2>"

GET {{host}}/route-handler/without-method/html
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>html.html - My application</title>"
body contains "<h2>html.html</h2>"

GET {{host}}/route-handler/without-method/json
HTTP 200
Content-Type: application/json; charset=UTF-8
[Asserts]
jsonpath "$" == "json.json"

GET {{host}}/route-handler/without-method/markdown
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>markdown.markdown - My application</title>"
body contains "<h2>markdown.markdown</h2>"

GET {{host}}/route-handler/without-method/md
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>md.md - My application</title>"
body contains "<h2>md.md</h2>"

GET {{host}}/route-handler/without-method/php
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>php.php - My application</title>"
body contains "<h2>php.php</h2>"

GET {{host}}/route-handler/without-method/text
HTTP 200
Content-Type: text/plain; charset=UTF-8
[Asserts]
body contains "text.txt"
