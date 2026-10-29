<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->isAdmin() || $this->isDtr();
        }
        return true;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'club_id',
        'name',
        'email',
        'role',
        'is_active',
        'phone',
        'password',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function isClub(): bool
    {
        return $this->role === 'club' && !empty($this->club_id);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || str_contains($this->email, 'admin');
    }

    public function isDtr(): bool
    {
        return in_array($this->role, ['dtr', 'technical_director', 'technique']) || str_contains($this->email, 'technique');
    }

    /**
     * Check if user is authorized to view confidential player/talent data
     */
    public function canAccessTalentData(?int $playerClubId = null): bool
    {
        if ($this->isAdmin() || $this->isDtr()) {
            return true;
        }

        if ($this->isClub() && $playerClubId && (int)$this->club_id === (int)$playerClubId) {
            return true;
        }

        return false;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
}
