<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\PaymentStatus;

class ExternalNotifications extends Model
{
    protected $table = 'external_notifications';

    #protected $connection = 'bd-name';

    protected $fillable = [
        'payment_id',
        'status',
        'attempts',
        'error',
    ];

    protected $casts = [
        'attempts' => 'integer',
        'error' => 'string',
    ];

    public $timestamps = true;
}
