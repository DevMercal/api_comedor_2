<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles ;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'cedula',
        'id_time_token',
        'id_expiry_month',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'cedula'
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

    public function employees() : BelongsTo
    {
        return $this->belongsTo(Employees::class, 'cedula', 'cedula');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getPasswordIsDefaultAttribute()
    {
        // Esta lógica verifica si la contraseña actual coincide con la cédula
        // Nota: Esto es lento porque tiene que procesar el Hash en cada login
        return Hash::check($this->cedula, $this->password);
    }

    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistories::class);
    }

    public function expiryMonth()
    {
        // El segundo parámetro es la llave foránea en tu tabla de usuarios
        // El tercer parámetro es la llave primaria en tu tabla expiry_months
        return $this->belongsTo(expiryMonths::class, 'id_expiry_month', 'id_expiry_month');
    }

    public function timeToken()
    {
        return $this->belongsTo(timeTokens::class, 'id_time_token', 'id_time_token');
    }
}
