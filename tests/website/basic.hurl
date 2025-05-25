# About page
GET {{host}}/
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>About - Zack!</title>"
body contains "A compact, file-based routing microframework" # hero
body contains "Zack! is a compact microframework" # content

# Examples page
GET {{host}}/examples
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Examples - Zack!</title>"
body contains "<h2>Examples</h2>"
body contains "<h3>This Website</h3>"
body contains "<h3>Petstore OAS 3.0</h3>"

# Documentation page
GET {{host}}/doc
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Documentation - Zack!</title>"
body contains "<h2>Documentation</h2>"
body contains "<h3>Requirements</h3>"
body contains "<h3>Installation</h3>"

# Development page
GET {{host}}/development
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Development - Zack!</title>"
body contains "<h2>Development</h2>"
body contains "<h3>Support</h3>"
body contains "<h3>Bug Reports</h3>"

# Page not found
GET {{host}}/not-existing-route
HTTP 404
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Page Not Found - Zack!</title>"
body contains "<h2>Page Not Found</h2>"
