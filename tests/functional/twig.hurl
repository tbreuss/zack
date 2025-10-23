# Layout
GET {{host}}/twig/layout/html-default
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>HTML Default - My application</title>"
body contains "<h1>HTML Default</h1>"

GET {{host}}/twig/layout/html-invalid
HTTP 500
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Error 500 - My application</title>"
body contains "<h1>Error 500</h1>"
body contains "Unable to find template &quot;invalid.html.twig&quot;"

GET {{host}}/twig/layout/html-test
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>HTML Test - My application</title>"
body contains "<h1>HTML Test</h1>"
body contains "<h1>test.html.twig</h1>"

GET {{host}}/twig/layout/markdown-default
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Markdown Default - My application</title>"
body contains "<h1>Markdown Default</h1>"

GET {{host}}/twig/layout/markdown-invalid
HTTP 500
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Error 500 - My application</title>"
body contains "<h1>Error 500</h1>"
body contains "Unable to find template &quot;invalid.html.twig&quot;"

GET {{host}}/twig/layout/markdown-test
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Markdown Test - My application</title>"
body contains "<h1>Markdown Test</h1>"
body contains "<h1>test.html.twig</h1>"

GET {{host}}/twig/layout/php-echo-default
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>PHP Echo Default - My application</title>"
body contains "<h1>PHP Echo Default</h1>"

GET {{host}}/twig/layout/php-echo-full
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body not contains "<title>PHP Echo Full - My application</title>"
body contains "<h1>PHP Echo Full</h1>"

GET {{host}}/twig/layout/php-echo-invalid
HTTP 500
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Error 500 - My application</title>"
body contains "<h1>Error 500</h1>"
body contains "Unable to find template &quot;invalid.html.twig&quot;"

GET {{host}}/twig/layout/php-echo-test
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>PHP Echo Test - My application</title>"
body contains "<h1>PHP Echo Test</h1>"
body contains "<h1>test.html.twig</h1>"

GET {{host}}/twig/layout/php-return-default
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>PHP Return Default - My application</title>"
body contains "<h1>PHP Return Default</h1>"

GET {{host}}/twig/layout/php-return-full
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body not contains "<title>PHP Return Full - My application</title>"
body contains "<h1>PHP Return Full</h1>"

GET {{host}}/twig/layout/php-return-invalid
HTTP 500
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Error 500 - My application</title>"
body contains "<h1>Error 500</h1>"
body contains "Unable to find template &quot;invalid.html.twig&quot;"

GET {{host}}/twig/layout/php-return-test
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>PHP Return Test - My application</title>"
body contains "<h1>PHP Return Test</h1>"
body contains "<h1>test.html.twig</h1>"
