<?php

namespace Modules\AdminManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'status',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Personal access tokens (Sanctum compatible).
     */
    public function tokens()
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }

    /**
     * Create a new personal access token for the user.
     */
    public function createToken(string $name = 'auth_token', array $abilities = ['*'], ?\DateTimeInterface $expiresAt = null): object
    {
        $plainToken = bin2hex(random_bytes(32));

        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainToken),
            'abilities' => $abilities,
            'expires_at' => $expiresAt,
            'last_used_at' => null,
        ]);

        return (object)[
            'accessToken' => $token,
            'plainTextToken' => $token->id . '|' . $plainToken,
        ];
    }

    /**
     * Check if user password matches.
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * Set hashed password.
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check role.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
