<?php

return [
    'qris' => [
        'qr_content' => env('QRIS_QR_CONTENT', ''),
        'description' => env('QRIS_DESCRIPTION', 'Scan QR Code di bawah untuk pembayaran'),
    ],
    'transfer_bank' => [
        [
            'bank_name' => env('BANK_1_NAME', 'Bank BCA'),
            'account_number' => env('BANK_1_NUMBER', '1234567890'),
            'account_holder' => env('BANK_1_HOLDER', 'PT Otomotif Shop'),
        ],
    ],
    'dana' => [
        'phone_number' => env('DANA_PHONE', '081234567890'),
        'account_name' => env('DANA_NAME', 'Otomotif Shop'),
        'qr_content' => env('DANA_QR_CONTENT', ''),
    ],
];
