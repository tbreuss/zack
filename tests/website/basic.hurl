# About page
GET {{host}}/
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>File-Based Routing Micro Framework - Zack!</title>"
body contains "<mark>File-Based Routing</mark>" # hero
body contains "These features make Zack! a good choice for users looking for a simple and efficient solution for their small-scale projects." # content

# Examples page
GET {{host}}/examples
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Examples - Zack!</title>"
body contains "<h1>Examples</h1>"
body contains "<h2 id=\"this-website\"><a href=\"#this-website\" class=\"heading-permalink\" aria-hidden=\"true\" title=\"Permalink\">#</a>This Website</h2>"
body contains "<h2 id=\"petstore-oas-3.0\"><a href=\"#petstore-oas-3.0\" class=\"heading-permalink\" aria-hidden=\"true\" title=\"Permalink\">#</a>Petstore OAS 3.0</h2>"

# Documentation page
GET {{host}}/doc
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Documentation - Zack!</title>"
body contains "<h1>Documentation</h1>"
body contains "<h2 id=\"requirements\"><a href=\"#requirements\" class=\"heading-permalink\" aria-hidden=\"true\" title=\"Permalink\">#</a>Requirements</h2>"
body contains "<h2 id=\"installation\"><a href=\"#installation\" class=\"heading-permalink\" aria-hidden=\"true\" title=\"Permalink\">#</a>Installation</h2>"

# Development page
GET {{host}}/development
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Development - Zack!</title>"
body contains "<h1>Development</h1>"
body contains "<h2 id=\"support\"><a href=\"#support\" class=\"heading-permalink\" aria-hidden=\"true\" title=\"Permalink\">#</a>Support</h2>"
body contains "<h2 id=\"bug-reports\"><a href=\"#bug-reports\" class=\"heading-permalink\" aria-hidden=\"true\" title=\"Permalink\">#</a>Bug Reports</h2>"

# Page not found
GET {{host}}/not-existing-route
HTTP 404
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Page Not Found - Zack!</title>"
body contains "<h1>Page Not Found</h1>"
