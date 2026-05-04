<?php

return [

    'custom' => [

        'event_id' => [
            'required' => 'The event_id is required',

        ],

        'bank_transaction_id' => [
            'required' => 'The bank_transaction_id is required',

        ],

        'payment_code' => [
            'required' => 'The payment_code is required',

        ],

        'merchant_id' => [
            'required' => 'The merchant_id is required',

        ],

        'customer_document' => [
            'required' => 'The customer_document is required',

        ],

        'amount' => [
            'required' => 'The amount is required',

        ],

        'currency' => [
            'required' => 'The currency is required',

        ],

        'status' => [
            'required' => 'Status is required',
        ],

        'description' => [
            'required' => 'Description is required',
        ],

        'bank' => [
            'required' => 'Bank required',
        ],

        'processing_date' => [
            'required' => 'Required processing date',
            'date_format' => 'Invalid processing date',
        ],

        'transactions' => [
            'required' => 'Required transactions',
        ],

    ],

    'attributes' => [
        'event_id' => 'Event ID',
        'bank_transaction_id' => 'Bank transaction ID',
        'payment_code' => 'Payment code',
        'merchant_id' => 'Merchant',
        'customer_document' => 'customer document',
        'quantity' => 'amount',
        'currency' => 'currency',
        'status' => 'status',
        'description' => 'description',
        'bank' => 'bank',
        'process_date' => 'processing date',
        'transactions' => 'transactions',
    ]

];