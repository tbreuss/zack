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
body contains "<h3 id=\"this-website\"><a href=\"#this-website\" class=\"heading-permalink\" aria-hidden=\"true\" title=\"Permalink\">#</a>This Website</h3>"
body contains "<h3 id=\"petstore-oas-3.0\"><a href=\"#petstore-oas-3.0\" class=\"heading-permalink\" aria-hidden=\"true\" title=\"Permalink\">#</a>Petstore OAS 3.0</h3>"

# Documentation page
GET {{host}}/doc
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Documentation - Zack!</title>"
body contains "<h2>Documentation</h2>"
body contains "<h3 id=\"requirements\"><a href=\"#requirements\" class=\"heading-permalink\" aria-hidden=\"true\" title=\"Permalink\">#</a>Requirements</h3>"
body contains "<h3 id=\"installation\"><a href=\"#installation\" class=\"heading-permalink\" aria-hidden=\"true\" title=\"Permalink\">#</a>Installation</h3>"

# Development page
GET {{host}}/development
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Development - Zack!</title>"
body contains "<h2>Development</h2>"
body contains "<h3 id=\"support\"><a href=\"#support\" class=\"heading-permalink\" aria-hidden=\"true\" title=\"Permalink\">¶</a>Support</h3>"
body contains "<h3 id=\"bug-reports\"><a href=\"#bug-reports\" class=\"heading-permalink\" aria-hidden=\"true\" title=\"Permalink\">¶</a>Bug Reports</h3>"

# Page not found
GET {{host}}/not-existing-route
HTTP 404
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Page Not Found - Zack!</title>"
body contains "<h2>Page Not Found</h2>"
