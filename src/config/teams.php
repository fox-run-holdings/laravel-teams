<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Team Model
    |--------------------------------------------------------------------------
    |
    | This is the model that will be used for teams.
    |
    */
    'team_model' => \FoxRunHoldings\LaravelTeams\Models\Team::class,

    /*
    |--------------------------------------------------------------------------
    | Team Invitation Model
    |--------------------------------------------------------------------------
    |
    | This is the model that will be used for team invitations.
    |
    */
    'team_invitation_model' => \FoxRunHoldings\LaravelTeams\Models\TeamInvitation::class,

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | This is the model that will be used for users.
    |
    */
    'user_model' => config('auth.providers.users.model'),

    /*
    |--------------------------------------------------------------------------
    | Team Roles
    |--------------------------------------------------------------------------
    |
    | These are the roles that can be assigned to team members.
    |
    */
    'roles' => [
        'owner' => 'Owner',
        'admin' => 'Admin',
        'member' => 'Member',
        'viewer' => 'Viewer',
    ],

    /*
    |--------------------------------------------------------------------------
    | Team Permissions
    |--------------------------------------------------------------------------
    |
    | These are the permissions that can be assigned to team roles.
    |
    */
    'permissions' => [
        'owner' => ['*'],
        'admin' => ['read', 'write', 'delete', 'invite'],
        'member' => ['read', 'write'],
        'viewer' => ['read'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Personal Team
    |--------------------------------------------------------------------------
    |
    | Whether to automatically create a personal team for new users.
    |
    */
    'personal_team' => true,

    /*
    |--------------------------------------------------------------------------
    | Team Invitations
    |--------------------------------------------------------------------------
    |
    | Whether to enable team invitations.
    |
    */
    'invitations' => true,
]; 