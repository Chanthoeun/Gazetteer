<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Mchev\Banhammer\Traits\Bannable;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use TomatoPHP\FilamentLanguageSwitcher\Traits\InteractsWithLanguages;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Bannable, HasRoles, LogsActivity, AuthenticationLoggable, InteractsWithLanguages;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('User')
            ->logOnly([
                'name',
                'email',
            ])
            ->setDescriptionForEvent(fn(string $eventName) => "User has been {$eventName}")
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
