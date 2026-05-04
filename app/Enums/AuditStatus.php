<?php

namespace App\Enums;

enum AuditStatus: string
{
    const CREATED = 'CREATED';
    const PAID_CONFIRMED = 'PAID_CONFIRMED';
    const VALIDATION_FAILED = 'VALIDATION_FAILED';
    const EXTERNAL_NOTIFICATION = 'EXTERNAL_NOTIFICATION';
    const NO_PENDING = 'NO_PENDING';
    const INCONSISTENCY = 'INCONSISTENCY';
}