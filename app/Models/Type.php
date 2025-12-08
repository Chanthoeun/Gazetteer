<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Type extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasTranslations;

    public array $translatable = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
        ];
    }

    /**
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Type')
            ->logOnly([
                'name',
            ])
            ->setDescriptionForEvent(fn(string $eventName) => "Type has been {$eventName}")
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function places(): HasMany
    {
        return $this->hasMany(Place::class);
    }
}
