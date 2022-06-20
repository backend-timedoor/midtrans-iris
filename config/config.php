<?php

return [
    'iris' => [
        'env'           => env('MIDTRANS_IRIS_ENV', 'sandbox'),
        'merchant_key'  => env('MIDTRANS_IRIS_MERCHANT_KEY'),
        'api_key'       => [
            'creator'   => env('MIDTRANS_IRIS_CREATOR_KEY'),
            'approver'  => env('MIDTRANS_IRIS_APPROVER_KEY'),
        ]
    ]
];