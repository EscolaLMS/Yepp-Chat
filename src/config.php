<?php

return [
    'enabled' => env('YEPP_CHAT_ENABLED'),
    'auth' => [
        'enabled' => env('YEPP_CHAT_AUTH_ENABLED'),
        'key' => env('YEPP_CHAT_AUTH_KEY'),
    ],
    'url' => env('YEPP_CHAT_URL', 'http://yepp-chat-ms-backend-service:3000'),
];
