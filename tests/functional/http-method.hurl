DELETE {{host}}/http-method/delete
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>delete.delete.htm - My application</title>"
body contains "<h2>delete.delete.htm</h2>"

GET {{host}}/http-method/get
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>get.get.htm - My application</title>"
body contains "<h2>get.get.htm</h2>"

HEAD {{host}}/http-method/head
HTTP 405
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains ""

OPTIONS {{host}}/http-method/options
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>options.options.htm - My application</title>"
body contains "<h2>options.options.htm</h2>"

PATCH {{host}}/http-method/patch
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>patch.patch.htm - My application</title>"
body contains "<h2>patch.patch.htm</h2>"

POST {{host}}/http-method/post
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>post.post.htm - My application</title>"
body contains "<h2>post.post.htm</h2>"

PUT {{host}}/http-method/put
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>put.put.htm - My application</title>"
body contains "<h2>put.put.htm</h2>"
