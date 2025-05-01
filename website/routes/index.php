<?php declare(strict_types=1);

$apiRoutes = [
    [
        'name' => 'pet',
        'description' => 'Everything about your Pets',
        'routes' => [
            [
                'name' => 'Update an existing pet.',
                'method' => 'PUT',
                'url' => '/api/petstore3/pet',
                'path' => '/api/petstore3/pet',
                'body' => '{"id":10,"name":"doggie","category":{"id":1,"name":"Dogs"},"photoUrls":["string"],"tags":[{"id":0,"name":"string"}],"status":"available"}'
            ],
            [
                'name' => 'Add a new pet to the store.',
                'method' => 'POST',
                'url' => '/api/petstore3/pet',
                'path' => '/api/petstore3/pet',
                'body' => '{"id":10,"name":"doggie","category":{"id":1,"name":"Dogs"},"photoUrls":["string"],"tags":[{"id":0,"name":"string"}],"status":"available"}'
            ],
            [
                'name' => 'Finds Pets by status.',
                'method' => 'GET',
                'url' => '/api/petstore3/pet/findByStatus',
                'path' => '/api/petstore3/pet/findByStatus',
                'body' => null
            ],
            [
                'name' => 'Finds Pets by tags.',
                'method' => 'GET',
                'url' => '/api/petstore3/pet/findByTags',
                'path' => '/api/petstore3/pet/findByTags',
                'body' => null
            ],
            [
                'name' => 'Find pet by ID.',
                'method' => 'GET',
                'url' => '/api/petstore3/pet/10',
                'path' => '/api/petstore3/pet/{petId}',
                'body' => ''
            ],
            [
                'name' => 'Updates a pet in the store with form data.',
                'method' => 'POST',
                'url' => '/api/petstore3/pet/10?name=doggie&status=available',
                'path' => '/api/petstore3/pet/{petId}',
                'body' => ''
            ],
            [
                'name' => 'Deletes a pet.',
                'method' => 'DELETE',
                'url' => '/api/petstore3/pet/10',
                'path' => '/api/petstore3/pet/{petId}',
                'body' => ''
            ],
            [
                'name' => 'Uploads an image.',                
                'method' => 'POST',
                'url' => '/api/petstore3/pet/10/uploadImage?additionalMetadata=string',
                'path' => '/api/petstore3/pet/{petId}/uploadImage',
                'body' => '~~~application/octet-stream~~~'
            ],
        ],
    ],
    [
        'name' => 'store',
        'description' => 'Access to Petstore orders',
        'routes' => [
            [
                'name' => 'Returns pet inventories by status.',
                'method' => 'GET',
                'url' => '/api/petstore3/store/inventory',
                'path' => '/api/petstore3/store/inventory',
                'body' => null
            ],
            [
                'name' => 'Place an order for a pet.',
                'method' => 'POST',
                'url' => '/api/petstore3/store/order',
                'path' => '/api/petstore3/store/order',
                'body' => '{"id":10,"petId":198772,"quantity":7,"shipDate":"2025-05-01T05:43:25.571Z","status":"approved","complete":true}'
            ],
            [
                'name' => 'Find purchase order by ID.',
                'method' => 'GET',
                'url' => '/api/petstore3/store/order/10',
                'path' => '/api/petstore3/store/order/{orderId}',
                'body' => null
            ],
            [
                'name' => 'Delete purchase order by ID.',
                'method' => 'DELETE',
                'url' => '/api/petstore3/store/order/10',
                'path' => '/api/petstore3/store/order/{orderId}',
                'body' => null
            ],
        ],
    ],
    [
        'name' => 'user',
        'description' => 'Operations about user',
        'routes' => [
            [
                'name' => 'Create user.',
                'method' => 'POST',
                'url' => '/api/petstore3/user',
                'path' => '/api/petstore3/user',
                'body' => '{"id":10,"username":"string","firstName":"string","lastName":"string","email":"string","password":"string","phone":"string","userStatus":0}'
            ],
            [
                'name' => 'Creates list of users with given input string.',
                'method' => 'POST',
                'url' => '/api/petstore3/user/createWithList',
                'path' => '/api/petstore3/user/createWithList',
                'body' => '[{"id":10,"username":"string","firstName":"string","lastName":"string","email":"string","password":"string","phone":"string","userStatus":0}]'
            ],
            [
                'name' => 'Logs user into the system.',
                'method' => 'GET',
                'url' => '/api/petstore3/user/login?username=doggie&password=available',
                'path' => '/api/petstore3/user/login',
                'body' => null
            ],
            [
                'name' => 'Logs out current logged in user session.',
                'method' => 'GET',
                'url' => '/api/petstore3/user/logout',
                'path' => '/api/petstore3/user/logout',
                'body' => null
            ],
            [
                'name' => 'Get user by user name.',
                'method' => 'GET',
                'url' => '/api/petstore3/user/10',
                'path' => '/api/petstore3/user/{username}',
                'body' => null
            ],
            [
                'name' => "Updated user.",
                'method' => "PUT",
                "url" => "/api/petstore3/user/theUser",
                "path" => "/api/petstore3/user/{username}",
                "body" => '{"id":10,"username":"doggie","firstName":"doggie","lastName":"doggie","email":"}'
            ],
            [
                'name' => 'Delete user.',
                'method' => 'DELETE',
                'url' => '/api/petstore3/user/10',
                'path' => '/api/petstore3/user/{username}',
                'body' => null
            ],    
        ],
    ],
];

return $this->html('index.html.twig', [
    'title' => 'Home',
    'apiRoutes' => $apiRoutes,
]);
