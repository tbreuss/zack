# Page not found
GET {{host}}/routing-not-existing
HTTP 404
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<h2>Page Not Found</h2>"

# Server error
GET {{host}}/routing/server-error
HTTP 500
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<h2>Error 500</h2>"

# Redirect 301
GET {{host}}/routing/redirect/301
HTTP 301
Content-Type: text/html; charset=utf-8
[Asserts]
body contains "<title>Redirecting to /</title>"

# Redirect 302
POST {{host}}/routing/redirect/302
HTTP 302
Content-Type: text/html; charset=utf-8
[Asserts]
body contains "<title>Redirecting to /</title>"

# One Parameter
GET {{host}}/routing/params/value1
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "value1"

# Two Parameters
GET {{host}}/routing/params/value1/value2
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "value1 value2"

# Three Parameters
GET {{host}}/routing/params/value1/value2/value3
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "value1 value2 value3"
