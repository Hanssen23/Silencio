<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Member extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'uid',
        'member_number',
        'membership',
        'subscription_status',
        'first_name',
        'last_name',
        'mobile_number',
        'email',
        'password',
        'status',
        'role',
        'current_membership_period_id',
        'membership_starts_at',
        'membership_expires_at',
        'accepted_terms_at',
        'current_plan_type',
        'current_duration_type',
    ];

    protected $casts = [
        'membership_starts_at' => 'date',
        'membership_expires_at' => 'date',
        'accepted_terms_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        // Return UID to pool when member is deleted
        static::deleting(function ($member) {
            if ($member->uid) {
                \App\Models\UidPool::returnUid($member->uid);
            }
        });
    }

    public function currentMembershipPeriod(): BelongsTo
    {
        return $this->belongsTo(MembershipPeriod::class, 'current_membership_period_id');
    }

    public function membershipPeriods(): HasMany
    {
        return $this->hasMany(MembershipPeriod::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function activeSessions(): HasMany
    {
        return $this->hasMany(ActiveSession::class);
    }

    public function rfidLogs(): HasMany
    {
        return $this->hasMany(RfidLog::class);
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' && 
               ($this->membership_expires_at === null || Carbon::parse($this->membership_expires_at)->isFuture());
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->membership_expires_at !== null && Carbon::parse($this->membership_expires_at)->isPast();
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Generate the next sequential member number
     */
    public static function generateMemberNumber(): string
    {
        // Get the highest existing member number
        $lastMember = self::where('member_number', 'like', 'MEM%')
            ->orderByRaw('CAST(SUBSTRING(member_number, 4) AS UNSIGNED) DESC')
            ->first();

        if ($lastMember) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastMember->member_number, 3);
            $nextNumber = $lastNumber + 1;
        } else {
            // Start from MEM001 if no members exist
            $nextNumber = 1;
        }

        return 'MEM' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get an available UID from the pool
     */
    public static function getAvailableUid(): ?string
    {
        return \App\Models\UidPool::getAvailableUid();
    }

    /**
     * Return a UID to the pool
     */
    public static function returnUidToPool(string $uid): bool
    {
        return \App\Models\UidPool::returnUid($uid);
    }

    /**
     * Check if member has an active subscription
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscription_status === 'active' && 
               $this->current_plan_type !== null &&
               ($this->membership_expires_at === null || Carbon::parse($this->membership_expires_at)->isFuture());
    }

    /**
     * Check if member is subscribed (has any plan)
     */
    public function isSubscribed(): bool
    {
        return $this->subscription_status !== 'not_subscribed' && 
               $this->current_plan_type !== null;
    }

    /**
     * Get display name for subscription status
     */
    public function getSubscriptionStatusDisplayAttribute(): string
    {
        if (!$this->isSubscribed()) {
            return 'Not Subscribed';
        }

        return match($this->subscription_status) {
            'pending' => 'Pending Payment',
            'active' => 'Active',
            'expired' => 'Expired',
            'cancelled' => 'Cancelled',
            default => 'Not Subscribed'
        };
    }

    /**
     * Get display name for plan type
     */
    public function getPlanTypeDisplayAttribute(): string
    {
        if (!$this->isSubscribed()) {
            return 'Not Subscribed';
        }

        return ucfirst($this->current_plan_type ?? 'Not Subscribed');
    }

    /**
     * Get display name for membership (legacy field)
     */
    public function getMembershipDisplayAttribute(): string
    {
        // If member has an active subscription, show the plan type
        if ($this->hasActiveSubscription()) {
            return ucfirst($this->current_plan_type);
        }
        
        // Otherwise show the basic membership field or "Not Subscribed"
        return $this->membership ? ucfirst($this->membership) : 'Not Subscribed';
    }

    public function getDaysUntilExpirationAttribute(): int
    {
        if ($this->membership_expires_at === null) {
            return -1; // No expiration
        }
        
        $expiresAt = Carbon::parse($this->membership_expires_at);
        $now = Carbon::now();
        
        // If membership is expired, return 0
        if ($expiresAt->isPast()) {
            return 0;
        }
        
        // Calculate days remaining (positive number)
        $daysLeft = $now->diffInDays($expiresAt, false);
        
        return max(0, (int) $daysLeft);
    }

    public function getMembershipStatusAttribute(): string
    {
        // Check if member has current membership period
        if ($this->currentMembershipPeriod) {
            if ($this->currentMembershipPeriod->is_expired) {
                return 'Expired';
            }

            if ($this->currentMembershipPeriod->days_until_expiration <= 7) {
                return 'Expiring Soon';
            }

            return 'Active';
        }

        // Fallback to direct membership expiration check
        if ($this->membership_expires_at) {
            $expiresAt = Carbon::parse($this->membership_expires_at);
            if ($expiresAt->isPast()) {
                return 'Expired';
            }
            if ($expiresAt->diffInDays(Carbon::now(), false) <= 7) {
                return 'Expiring Soon';
            }
            return 'Active';
        }

        return 'No Active Membership';
    }

    public function getIsMembershipActiveAttribute(): bool
    {
        return $this->currentMembershipPeriod && $this->currentMembershipPeriod->is_active;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'active')
                    ->where('membership_expires_at', '<', now());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('status', 'active')
                    ->where('membership_expires_at', '>', now())
                    ->where('membership_expires_at', '<=', now()->addDays($days));
    }

    /**
     * Scope a query to only include members whose memberships expire within the next 7 days.
     * This checks multiple sources: direct member expiration, payment expiration dates, and membership periods.
     */
    public function scopeExpiringThisWeek($query)
    {
        return $query->where('status', 'active')
                    ->where(function ($q) {
                        $q->where(function ($subQ) {
                            // Check direct member expiration
                            $subQ->where('membership_expires_at', '>', now())
                                  ->where('membership_expires_at', '<=', now()->addDays(7));
                        })->orWhereHas('payments', function ($paymentQ) {
                            // Check payment expiration dates
                            $paymentQ->where('membership_expiration_date', '>', now())
                                     ->where('membership_expiration_date', '<=', now()->addDays(7));
                        })->orWhereHas('membershipPeriods', function ($periodQ) {
                            // Check membership period expiration dates
                            $periodQ->where('expiration_date', '>', now())
                                    ->where('expiration_date', '<=', now()->addDays(7));
                        });
                    });
    }

    /**
     * Check if the member has admin role
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the member has member role
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    /**
     * Check if member is currently in the gym (has active RFID session)
     */
    public function isInGym(): bool
    {
        $hasActiveSession = $this->activeSessions()
            ->where('status', 'active')
            ->whereNull('check_out_time')
            ->exists();
            
        // Log consistency check
        if ($hasActiveSession) {
            Log::info('Member marked as in gym', [
                'member_id' => $this->id,
                'member_number' => $this->member_number,
                'method' => 'isInGym'
            ]);
        }
        
        return $hasActiveSession;
    }

    /**
     * Get current gym session if member is in gym
     */
    public function getCurrentGymSession()
    {
        return $this->activeSessions()
            ->where('status', 'active')
            ->whereNull('check_out_time')
            ->first();
    }

    /**
     * Get gym presence status
     */
    public function getGymPresenceStatusAttribute(): string
    {
        return $this->isInGym() ? 'In Gym' : 'Not in Gym';
    }

    /**
     * Get online status based on RFID check-in, not web session
     */
    public function getOnlineStatusAttribute(): string
    {
        return $this->isInGym() ? 'Online' : 'Offline';
    }
}
