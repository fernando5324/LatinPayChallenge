<?php

namespace App\Enums;

enum Messages: string
{
    const CREATE_SUCCESS = 'Operación exitosa';
    const SETTLEMENT_SUCCESS = 'Liquidación exitosa';
    const BANK_NOTIFICATION_SUCCESS = 'Notificación bancaria exitosa';
    const EXTERNAL_NOTIFICATION_SUCCESS = 'Notificación externa exitosa';
    const INTERNAL_ERROR = 'Error inesperado';
}