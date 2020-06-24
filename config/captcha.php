<?php

return [
    'secret' => env('RECAPTCHA_LOGIN_PRIVATE_KEY'),
    'sitekey' => env('RECAPTCHA_LOGIN_PUBLIC_KEY'),
    'options' => [
        'timeout' => 30,
    ],
];
