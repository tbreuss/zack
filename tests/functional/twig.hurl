# Layout
GET {{host}}/twig/layout/html-default
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>HTML Default - My application</title>"
body contains "<h2>HTML Default</h2>"

GET {{host}}/twig/layout/html-invalid
HTTP 500
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Error 500 - My application</title>"
body contains "<h2>Error 500</h2>"
body contains "Unable to find template &quot;invalid.html.twig&quot;"

GET {{host}}/twig/layout/html-test
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>HTML Test - My application</title>"
body contains "<h2>HTML Test</h2>"
body contains "<h2>test.html.twig</h2>"

GET {{host}}/twig/layout/markdown-default
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Markdown Default - My application</title>"
body contains "<h2>Markdown Default</h2>"

GET {{host}}/twig/layout/markdown-invalid
HTTP 500
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Error 500 - My application</title>"
body contains "<h2>Error 500</h2>"
body contains "Unable to find template &quot;invalid.html.twig&quot;"

GET {{host}}/twig/layout/markdown-test
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Markdown Test - My application</title>"
body contains "<h2>Markdown Test</h2>"
body contains "<h2>test.html.twig</h2>"

GET {{host}}/twig/layout/php-echo-default
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>PHP Echo Default - My application</title>"
body contains "<h2>PHP Echo Default</h2>"

GET {{host}}/twig/layout/php-echo-full
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body not contains "<title>PHP Echo Full - My application</title>"
body contains "<h2>PHP Echo Full</h2>"

GET {{host}}/twig/layout/php-echo-invalid
HTTP 500
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Error 500 - My application</title>"
body contains "<h2>Error 500</h2>"
body contains "Unable to find template &quot;invalid.html.twig&quot;"

GET {{host}}/twig/layout/php-echo-test
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>PHP Echo Test - My application</title>"
body contains "<h2>PHP Echo Test</h2>"
body contains "<h2>test.html.twig</h2>"

GET {{host}}/twig/layout/php-return-default
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>PHP Return Default - My application</title>"
body contains "<h2>PHP Return Default</h2>"

GET {{host}}/twig/layout/php-return-full
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body not contains "<title>PHP Return Full - My application</title>"
body contains "<h2>PHP Return Full</h2>"

GET {{host}}/twig/layout/php-return-invalid
HTTP 500
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>Error 500 - My application</title>"
body contains "<h2>Error 500</h2>"
body contains "Unable to find template &quot;invalid.html.twig&quot;"

GET {{host}}/twig/layout/php-return-test
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>PHP Return Test - My application</title>"
body contains "<h2>PHP Return Test</h2>"
body contains "<h2>test.html.twig</h2>"
