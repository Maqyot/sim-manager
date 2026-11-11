<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_STAFF = 'staff';
    public const ROLE_STUDENT = 'student';

    protected $fillable = ['name', 'email', 'password', 'role', 'student_id'];

    protected $hidden = ['password', 'remember_token', 'api_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_STAFF], true);
    }

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /** Creates a new API token. Only the SHA-256 hash is stored; the plain value is returned once. */
    public function issueApiToken(): string
    {
        $plain = Str::random(60);
        $this->forceFill(['api_token' => hash('sha256', $plain)])->save();

        return $plain;
    }

    public function revokeApiToken(): void
    {
        $this->forceFill(['api_token' => null])->save();
    }
}
