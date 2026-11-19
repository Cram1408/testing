<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'google_id',
        'avatar',
        'password',
        'role',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']) || $this->email === 'adminram14@achaarg.com';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin' || $this->email === 'adminram14@achaarg.com';
    }

    public function hasPermission(string $module): bool
    {
        return \App\Services\AdminRoleService::hasPermission($this, $module);
    }

    public function getRoleTitleAttribute(): string
    {
        return \App\Services\AdminRoleService::getRoleTitle($this);
    }

    public function getIsDeactivatedAttribute(): bool
    {
        return \App\Services\AdminRoleService::isDeactivated($this->id);
    }

    /**
     * Get the user's avatar URL or default profile image.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (! empty($this->avatar)) {
            if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
                return $this->avatar;
            }
            return asset('storage/' . $this->avatar);
        }

        return asset('images/default-avatar.svg');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
