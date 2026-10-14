<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $fillable = [
        'phone',
        'otp',
        'purpose',
        'is_used',
        'attempts',
        'expires_at',
    ];

    protected $casts = [
        'is_used'    => 'boolean',
        'expires_at' => 'datetime',
        'attempts'   => 'integer',
    ];

    /** Check whether this OTP record is still valid for verification */
    public function isValid(): bool
    {
        return ! $this->is_used
            && $this->attempts < 5
            && $this->expires_at->isFuture();
    }

    /** Check whether a new OTP can be sent (cooldown: 60 s from creation) */
    public function isOnCooldown(): bool
    {
        return $this->created_at->diffInSeconds(now()) < 60;
    }
}
