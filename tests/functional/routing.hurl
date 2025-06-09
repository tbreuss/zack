# Page not found
GET {{host}}/routing-not-existing
HTTP 404
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Page Not Found - My application</title>"
body contains "<h2>Page Not Found</h2>"

# Server error
GET {{host}}/routing/server-error
HTTP 500
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Error 500 - My application</title>"
body contains "<h2>Error 500</h2>"

# Redirect 301
GET {{host}}/routing/redirect/301
HTTP 301
Content-Type: text/html; charset=utf-8
[Asserts]
body contains "<title>Redirecting to /</title>"
body contains "Redirecting to <a href=\"/\">/</a>"

# Redirect 302
POST {{host}}/routing/redirect/302
HTTP 302
Content-Type: text/html; charset=utf-8
[Asserts]
body contains "<title>Redirecting to /</title>"
body contains "Redirecting to <a href=\"/\">/</a>"

# Pathparams One Parameter
GET {{host}}/routing/path-params/value1
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>value1 - My application</title>"
body contains "<h2>value1</h2>"

# Pathparams Two Parameters
GET {{host}}/routing/path-params/value1/value2
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>value1 value2 - My application</title>"
body contains "<h2>value1 value2</h2>"

# Pathparams Three Parameters
GET {{host}}/routing/path-params/value1/value2/value3
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>value1 value2 value3 - My application</title>"
body contains "<h2>value1 value2 value3</h2>"

# Fileparams One Parameter
GET {{host}}/routing/file-params/value1
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>value1 - My application</title>"
body contains "<h2>value1</h2>"

# Fileparams Two Parameters
GET {{host}}/routing/file-params/value1/value2
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>value1 value2 - My application</title>"
body contains "<h2>value1 value2</h2>"

# Fileparams Three Parameters
GET {{host}}/routing/file-params/value1/value2/value3
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>value1 value2 value3 - My application</title>"
body contains "<h2>value1 value2 value3</h2>"

# Catch all params without method
GET {{host}}/routing/catch-all-params/without-method/value1/value2/value3
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Catch all params value1/value2/value3 - My application</title>"
body contains "<h2>Catch all params value1/value2/value3</h2>"

# Catch all params with method
PATCH {{host}}/routing/catch-all-params/with-method/value1/value2/value3
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>PATCH Catch all params value1/value2/value3 - My application</title>"
body contains "<h2>PATCH Catch all params value1/value2/value3</h2>"

POST {{host}}/routing/catch-all-params/with-method/value1/value2/value3
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>POST Catch all params value1/value2/value3 - My application</title>"
body contains "<h2>POST Catch all params value1/value2/value3</h2>"
