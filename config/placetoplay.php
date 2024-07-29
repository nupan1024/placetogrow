<?php

return [
    'login' => env('PLACE_TO_PAY_LOGIN'),
    'secret_key' => env('PLACE_TO_PAY_SECRET_KEY'),
    'base_url' => env('PLACE_TO_PAY_BASE_URL'),
    'timeout' => env('PLACE_TO_PAY_MINUTES_TIMEOUT', 10),
];
