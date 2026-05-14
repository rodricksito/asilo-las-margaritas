<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'sucursal_id', 'rol'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Roles válidos del sistema (debe coincidir con la migración add_sucursal_and_rol_to_users).
     */
    public const ROLES = ['admin', 'recepcionista', 'doctor', 'enfermera'];

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

    /* -----------------------------------------------------------------------
     | Filament panel access
     | -----------------------------------------------------------------------
     */

    /**
     * Solo usuarios con rol válido pueden entrar al panel.
     * Filament llama esto automáticamente al iniciar sesión.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->rol, self::ROLES, true);
    }

    /* -----------------------------------------------------------------------
     | Role helpers — usados en las policies para decisiones de acceso
     | -----------------------------------------------------------------------
     */

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function isRecepcionista(): bool
    {
        return $this->rol === 'recepcionista';
    }

    public function isDoctor(): bool
    {
        return $this->rol === 'doctor';
    }

    public function isEnfermera(): bool
    {
        return $this->rol === 'enfermera';
    }

    /**
     * Verifica si el usuario tiene cualquiera de los roles dados.
     * Ej: $user->hasAnyRole(['admin', 'recepcionista'])
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->rol, $roles, true);
    }

    /**
     * Devuelve la etiqueta legible del rol (útil para UI).
     */
    public function getRolLabelAttribute(): string
    {
        return match ($this->rol) {
            'admin' => 'Administrador',
            'recepcionista' => 'Recepcionista',
            'doctor' => 'Doctor',
            'enfermera' => 'Enfermera',
            default => 'Sin rol asignado',
        };
    }

    /* -----------------------------------------------------------------------
     | Relaciones
     | -----------------------------------------------------------------------
     */

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class, 'usuario_id');
    }

    public function enfermera(): HasOne
    {
        return $this->hasOne(Enfermera::class, 'usuario_id');
    }

    public function traspasosRegistrados(): HasMany
    {
        return $this->hasMany(Traspaso::class, 'usuario_id');
    }
}
