<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'role_id',
        'password',
        'role_id',
    ];

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

    /**
     * Relationship: User belongs to Role
     */
    public function role()
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    public function isAdmin(): bool
    {
        // Example 1: Check if user has a role column equal to 'admin'
        // return $this->role === 'admin';

        // Example 2: Check if user id is 1 (super admin)
        return $this->id === 1;

        // Modify this logic based on your app's roles system
    }

    // In User model
    public function getNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    protected $appends = ['name'];
}
