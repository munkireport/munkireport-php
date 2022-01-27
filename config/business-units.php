<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Single Sign-On Business Unit Claims Mapping
    |--------------------------------------------------------------------------
    |
    | This option allows you to map groups from your identity provider (IdP), such as
    | Azure AD or Google Workspace, to business units.
    |
    | Nominate the name of an Attribute (SAML) or token claim (oauth2) in the response which carries a list of
    | groups that the user is part of. Each business unit can then have an external identifier associated with it, that
    | corresponds to a group that the user is part of.
    */
    'claims_mapping' => [
        'saml' => [
            // AttributeName
        ],
        'oauth2' => [
            // token claim
        ],
    ]
];
