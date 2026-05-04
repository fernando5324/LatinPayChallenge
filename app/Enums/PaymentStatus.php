<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'PENDING';
    case PAID = 'PAID';
    case CANCELLED = 'CANCELLED';
    case OBSERVED = 'OBSERVED';
    case UNMATCHED = 'UNMATCHED';
    case RECONCILED = 'RECONCILED';
    case LATE_CONFIRMATION = 'LATE_CONFIRMATION';
    case INCONSISTENCY = 'INCONSISTENCY';

    
}