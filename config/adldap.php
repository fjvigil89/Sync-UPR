<?php
return [

    'connections' => [

        'default' => [

            'auto_connect' => true,

            'connection' => Adldap\Connections\Ldap::class,

            'schema' => Adldap\Schemas\ActiveDirectory::class,

            'connection_settings' => [

        		
                'account_prefix' => env('ADLDAP_ACCOUNT_PREFIX', ''),

                'account_suffix' => env('ADLDAP_ACCOUNT_SUFFIX', ''),


                'domain_controllers' => explode(' ', env('ADLDAP_CONTROLLERS', '10.2.24.35')),

                'port' => env('ADLDAP_PORT', 389),

                'timeout' => env('ADLDAP_TIMEOUT', 5),

              
                'base_dn' => env('ADLDAP_BASEDN', 'dc=upr,dc=edu,dc=cu'),

                'admin_account_suffix' => env('ADLDAP_ADMIN_ACCOUNT_SUFFIX', ''),
                'admin_account_prefix' => env('ADLDAP_ADMIN_ACCOUNT_SUFFIX', ''),

                'admin_username' => env('ADLDAP_ADMIN_USERNAME', 'upr\Administrator'),
                'admin_password' => env('ADLDAP_ADMIN_PASSWORD', 'mistake*ad.20'),

                'follow_referrals' => false,

                'use_ssl' => false,
                'use_tls' => false,

            ],

        ],

    ],

];
