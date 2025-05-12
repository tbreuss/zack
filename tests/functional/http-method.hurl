DELETE {{host}}/http-method/delete
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>delete.delete.htm - Zack!</title>"
body contains "<h1>delete.delete.htm</h1>"

GET {{host}}/http-method/get
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>get.get.htm - Zack!</title>"
body contains "<h1>get.get.htm</h1>"

HEAD {{host}}/http-method/head
HTTP 405
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains ""

OPTIONS {{host}}/http-method/options
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>options.options.htm - Zack!</title>"
body contains "<h1>options.options.htm</h1>"

PATCH {{host}}/http-method/patch
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>patch.patch.htm - Zack!</title>"
body contains "<h1>patch.patch.htm</h1>"

POST {{host}}/http-method/post
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>post.post.htm - Zack!</title>"
body contains "<h1>post.post.htm</h1>"

PUT {{host}}/http-method/put
HTTP 200
Content-Type: text/html; charset=UTF-8
[Asserts]
body contains "<title>put.put.htm - Zack!</title>"
body contains "<h1>put.put.htm</h1>"
