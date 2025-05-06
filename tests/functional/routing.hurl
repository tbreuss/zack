# Page not found
GET {{host}}/routing/not-existing
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
GET {{host}}/routing/redirect-301
HTTP 301
Content-Type: text/html; charset=utf-8
[Asserts]
body contains "<title>Redirecting to /</title>"

# Redirect 302
GET {{host}}/routing/redirect-302
HTTP 302
Content-Type: text/html; charset=utf-8
[Asserts]
body contains "<title>Redirecting to /</title>"
