<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Teams Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the Laravel Teams package.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | The user model that will be used for team members.
    |
    */
    'user_model' => config('auth.providers.users.model', App\Models\User::class),

    /*
    |--------------------------------------------------------------------------
    | Team Roles
    |--------------------------------------------------------------------------
    |
    | Available roles for team members.
    |
    */
    'roles' => [
        'owner' => 'owner',
        'admin' => 'admin',
        'member' => 'member',
    ],

    /*
    |--------------------------------------------------------------------------
    | Invitation Statuses
    |--------------------------------------------------------------------------
    |
    | Available statuses for team invitations.
    |
    */
    'invitation_statuses' => [
        'pending' => 'pending',
        'accepted' => 'accepted',
        'declined' => 'declined',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Number of items per page for team listings.
    |
    */
    'pagination' => [
        'per_page' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | Route configuration for team management.
    |
    */
    'routes' => [
        'prefix' => 'teams',
        'middleware' => ['web', 'auth'],
    ],
]; 