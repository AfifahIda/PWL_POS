<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Casts\Attribute;

class UserModel extends Authenticatable implements JWTSubject
{
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';
    
    use HasFactory;

    protected $fillable = ['username', 'nama', 'password', 'level_id', 'foto', 'image', 'created_at', 'updated_at'];
    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed', 'foto' => 'string'];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('/storage/posts/' . $image)
        );
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    public function getRoleName(): string
    {
        return $this->level ? $this->level->level_nama : 'Unknown';
    }

    public function hasRole($role): bool
    {
        return $this->level && $this->level->level_kode === $role;
    }

    public function getRole()
    {
        return $this->level ? $this->level->level_kode : null;
    }
}
