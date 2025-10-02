<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfidLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_uid',
        'member_id',
        'action',
        'status',
        'message',
        'timestamp',
        'device_id',
        'source',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    /**
     * Get the member associated with this RFID log
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Scope for successful events
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed events
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for today's events
     */
    public function scopeToday($query)
    {
        return $query->whereDate('timestamp', today());
    }

    /**
     * Scope for unknown card events
     */
    public function scopeUnknownCards($query)
    {
        return $query->where('action', 'unknown_card');
    }
}
