<?php

return [
    'title' => env('APP_NAME', 'Template Project'),
    'subtitle' => 'Software House',

    'logo_auth' => 'files/images/logo.png',
    'logo_auth_background' => 'white',

    'logo_panel' => 'files/images/logo_long.png',
    'logo_panel_background' => 'white',

    'registration_route' => 'register',
    'registration_default_role' => 'Member',

    'forgot_password_route' => 'password.request',
    'reset_password_route' => 'password.reset',

    // 'email_verification_route' => 'verification.index',
    'email_verification_route' => '',
    'email_verification_delay_time' => 30,

    'email_verify_route' => 'verification.verify',

    'profile_route' => 'profile',
    'profile_image' => 'assets/media/avatars/profile.png',

    'menu' => [
        [
            'text' => 'Dashboard',
            'route'  => 'dashboard.index',
            'icon' => 'ki-duotone ki-element-11',
        ],
        // ADMIN
        [
            'text' => 'Admin',
            'icon' => 'ki-duotone ki-shield-tick',
            'submenu' => [
                [
                    'text' => 'Pengguna',
                    'route' => 'user.index',
                    'icon_color' => 'success',
                ],
                [
                    'text' => 'Jabatan',
                    'route' => 'role.index',
                    'icon_color' => 'primary',
                ],
                [
                    'text' => 'Akses',
                    'route' => 'permission.index',
                    'icon_color' => 'primary',
                ],
            ],
        ],
        [
            'text' => 'Hotspot Bulanan',
            'route'  => 'monthly_hotspot.index',
            'icon' => 'ki-duotone ki-element-11',
        ],
        [
            'text' => 'Produk',
            'route'  => 'product.index',
            'icon' => 'ki-duotone ki-element-11',
        ],
        [
            'text' => 'Hotspot Member',
            'route'  => 'hotspot_member.index',
            'icon' => 'ki-duotone ki-element-11',
        ],
        [
            'text' => 'Transaksi',
            'route'  => 'transaction.index',
            'icon' => 'ki-duotone ki-element-11',
        ],
        [
            'text' => 'Metode Pembayaran',
            'route'  => 'payment_method.index',
            'icon' => 'ki-duotone ki-element-11',
        ],

        // MEMBER
        [
            'text' => 'Tagihan Saya',
            'route'  => 'bill.index',
            'icon' => 'ki-duotone ki-element-11',
        ],
    ],
];
