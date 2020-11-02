<?php

return [

    /*
    |--------------------------------------------------------------------------
    | public registration activation
    |--------------------------------------------------------------------------
    |
    | activate public user registration
    |
    */

    'front_registration_enabled' => env('PUBLIC_REGISTER_ACTIVATION', false),


    /*
    |--------------------------------------------------------------------------
    | profil admin
    |--------------------------------------------------------------------------
    |
    | profil name for admin
    |
    */

    'profil_admin_name' => env('PROFIL_ADMIN_NAME', 'Super Admin'),

    /*
    |--------------------------------------------------------------------------
    | profil default
    |--------------------------------------------------------------------------
    |
    | profil name for default user
    |
    */

    'profil_default_name' => env('PROFIL_DEFAULT_NAME', 'Default')
];
