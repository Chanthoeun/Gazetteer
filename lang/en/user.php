<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during User for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'single' => 'User',
    'plural' => 'Users',

    'name' => 'Name',
    'email' => 'Email',
    'email_verified_at' => 'Email Verified At',
    'password' => 'Password',
    'password_confirmation' => 'Password Confirmation',
    'current_password' => 'Current Password',
    'new_password' => 'New Password',

    'messages' => [
        'user' => 'Your account has been banned.',
        'ip' => 'Access from your IP address is restricted.',
        'country' => 'Access from your country is restricted.',
    ],

    'status' => [
        'active' => 'Active',
        'banned' => 'Banned',
    ],

    'action' => [
        'ban' => 'Ban',
        'unban' => 'Unban',

        'description' => [],

        'notification' => [
            'banned' => 'User has been banned successfully.',
            'unbanned' => 'User has been unbanned successfully.',
        ]
    ],
];
