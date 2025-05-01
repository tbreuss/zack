<?php declare(strict_types=1);

$apiRoutes = [
    [
        'name' => 'pet',
        'description' => 'Everything about your Pets',
        'routes' => [
            [
                'name' => 'Update an existing pet.',
                'method' => 'PUT',
                'path' => '/api/petstore/pet',
                'body' => '{"id":10,"name":"doggie","category":{"id":1,"name":"Dogs"},"photoUrls":["string"],"tags":[{"id":0,"name":"string"}],"status":"available"}'
            ],
            [
                'name' => 'Add a new pet to the store.',
                'method' => 'POST',
                'path' => '/api/petstore/pet',
                'body' => '{"id":10,"name":"doggie","category":{"id":1,"name":"Dogs"},"photoUrls":["string"],"tags":[{"id":0,"name":"string"}],"status":"available"}'
            ],
            [
                'name' => 'Finds Pets by status.',
                'method' => 'GET',
                'path' => '/api/petstore/pet/findByStatus',
                'body' => null
            ],
            [
                'name' => 'Finds Pets by tags.',
                'method' => 'GET',
                'path' => '/api/petstore/pet/findByTags',
                'body' => null
            ],
            [
                'name' => 'Find pet by ID.',
                'method' => 'GET',
                'path' => '/api/petstore/pet/10',
                'body' => ''
            ],
            [
                'name' => 'Updates a pet in the store with form data.',
                'method' => 'POST',
                'path' => '/api/petstore/pet/10?name=doggie&status=available',
                'body' => ''
            ],
            [
                'name' => 'Deletes a pet.',
                'method' => 'DELETE',
                'path' => '/api/petstore/pet/10',
                'body' => ''
            ],
            [
                'name' => 'Uploads an image.',                
                'method' => 'POST',
                'path' => '/api/petstore/pet/10/uploadImage?additionalMetadata=string',
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
                'path' => '/api/petstore/store/inventory',
                'body' => null
            ],
            [
                'name' => 'Place an order for a pet.',
                'method' => 'POST',
                'path' => '/api/petstore/store/order',
                'body' => '{"id":10,"petId":198772,"quantity":7,"shipDate":"2025-05-01T05:43:25.571Z","status":"approved","complete":true}'
            ],
            [
                'name' => 'Find purchase order by ID.',
                'method' => 'GET',
                'path' => '/api/petstore/store/order/10',
                'body' => null
            ],
            [
                'name' => 'Delete purchase order by ID.',
                'method' => 'DELETE',
                'path' => '/api/petstore/store/order/10',
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
                'path' => '/api/petstore/user',
                'body' => '{"id":10,"username":"string","firstName":"string","lastName":"string","email":"string","password":"string","phone":"string","userStatus":0}'
            ],
            [
                'name' => 'Creates list of users with given input string.',
                'method' => 'POST',
                'path' => '/api/petstore/user/createWithList',
                'body' => '[{"id":10,"username":"string","firstName":"string","lastName":"string","email":"string","password":"string","phone":"string","userStatus":0}]'
            ],
            [
                'name' => 'Logs user into the system.',
                'method' => 'GET',
                'path' => '/api/petstore/user/login?username=doggie&password=available',
                'body' => null
            ],
            [
                'name' => 'Logs out current logged in user session.',
                'method' => 'GET',
                'path' => '/api/petstore/user/logout',
                'body' => null
            ],
            [
                'name' => 'Get user by user name.',
                'method' => 'GET',
                'path' => '/api/petstore/user/10',
                'body' => null
            ],
            [
                'name' => "Updated user.",
                'method' => "PUT",
                "path" => "/api/petstore/user/theUser",
                "body" => '{"id":10,"username":"doggie","firstName":"doggie","lastName":"doggie","email":"}'
            ],
            [
                'name' => 'Delete user.',
                'method' => 'DELETE',
                'path' => '/api/petstore/user/10',
                'body' => null
            ],    
        ],
    ],
];

return $this->html('index.html.twig', [
    'title' => 'Home',
    'apiRoutes' => $apiRoutes,
]);
