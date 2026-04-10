<?php

return [
    'merchant_id'     => env('JAZZCASH_MERCHANT_ID', ''),
    'password'        => env('JAZZCASH_PASSWORD', ''),
    'integrity_salt'  => env('JAZZCASH_INTEGRITY_SALT', ''),

    'sandbox_url' => 'https://sandbox.jazzcash.com.pk/ApplicationAPI/API/2.0/Purchase/DoMWalletTransaction',
    'live_url'    => 'https://payments.jazzcash.com.pk/ApplicationAPI/API/2.0/Purchase/DoMWalletTransaction',

    'env' => env('JAZZCASH_ENV', 'sandbox'), // sandbox | live
];
