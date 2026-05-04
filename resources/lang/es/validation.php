<?php

return [

    'custom' => [

        'event_id' => [
            'required' => 'El event_id es requerido',
        ],

        'bank_transaction_id' => [
            'required' => 'El bank_transaction_id es requerido',
        ],

        'payment_code' => [
            'required' => 'El payment_code es requerido',
        ],

        'merchant_id' => [
            'required' => 'El merchant_id es requerido',
        ],

        'customer_document' => [
            'required' => 'El customer_document es requerido',
        ],

        'amount' => [
            'required' => 'El amount es requerido',
        ],

        'currency' => [
            'required' => 'La currency es requerida',
        ],

        'status' => [
            'required' => 'El status es requerido',
        ],

        'description' => [
            'required' => 'La description es requerida',
        ],

        'bank' => [
            'required' => 'Banco requerido',
        ],

        'process_date' => [
            'required' => 'Fecha de proceso requerida',
            'date_format' => 'Fecha de proceso inválida',
        ],

        'movements' => [
            'required' => 'Movimientos requeridos',
        ],

    ],

    'attributes' => [
        'event_id' => 'ID del evento',
        'bank_transaction_id' => 'ID de transacción bancaria',
        'payment_code' => 'código de pago',
        'merchant_id' => 'comercio',
        'customer_document' => 'documento del cliente',
        'amount' => 'monto',
        'currency' => 'moneda',
        'status' => 'estado',
        'description' => 'descripción',
        'bank' => 'banco',
        'process_date' => 'fecha de proceso',
        'movements' => 'movimientos',
    ],

];