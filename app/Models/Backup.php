<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Backup extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'place_id',
        'type_id',
        'parent_id',
        'code',
        'khmer',
        'latin',
        'postal_code',
        'geo_location',
        'geo_boundary',
        'reference',
        'issued_date',
        'note',
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
            'place_id' => 'integer',
            'type_id' => 'integer',
            'parent_id' => 'integer',
            'issued_date' => 'date',
        ];
    }

    /**
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Backup')
            ->logOnly([
                'place_id',
                'type_id',
                'parent_id',
                'code',
                'khmer',
                'latin',
                'postal_code',
                'geo_location',
                'geo_boundary',
                'reference',
                'issued_date',
                'note',
            ])
            ->setDescriptionForEvent(fn(string $eventName) => "Backup has been {$eventName}")
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Place::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Place::class, 'parent_id');
    }
}
