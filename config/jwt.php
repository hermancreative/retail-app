<?php

return [

    /*
    |--------------------------------------------------------------------------
    | JWT Secret
    |--------------------------------------------------------------------------
    |
    | The signing key used to sign and verify tokens issued by the
    | application. Keep this value secret and unique per environment.
    |
    */

    'secret' => env('JWT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | JWT Time To Live
    |--------------------------------------------------------------------------
    |
    | Number of minutes a token is considered valid after it is issued.
    |
    */

    'ttl' => (int) env('JWT_TTL', 60),

    /*
    |--------------------------------------------------------------------------
    | Signing Algorithm
    |--------------------------------------------------------------------------
    */

    'algo' => 'HS256',

    /*
    |--------------------------------------------------------------------------
    | Cookie Name
    |--------------------------------------------------------------------------
    |
    | The name of the httpOnly cookie used to store the issued token on the
    | browser so that the Inertia/Vue front-end never needs to touch it
    | directly.
    |
    */

    'cookie' => 'access_token',

];
