<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'locked_at',
        'locked_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'locked_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Kiểm tra và áp dụng tự mở khóa sau X giờ (soft-ban). Trả về true nếu vẫn đang khóa.
     */
    public function refreshLockState(): bool
    {
        if ($this->locked_at === null) {
            return false;
        }
        $hours = (int) \App\Models\SecuritySetting::get('devtools_auto_unlock_hours', '0');
        if ($hours <= 0) {
            return true;
        }
        if ($this->locked_at->addHours($hours)->isPast()) {
            $this->update(['locked_at' => null, 'locked_reason' => null]);
            $this->refresh();
            SystemLog::add($this, 'user_auto_unlocked', 'Tài khoản được tự động mở khóa sau ' . $hours . ' giờ.', ['hours' => $hours]);
            return false;
        }
        return true;
    }

    public function isLocked(): bool
    {
        return $this->refreshLockState();
    }

    public function devtoolsViolations(): HasMany
    {
        return $this->hasMany(DevtoolsViolation::class);
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }
}
