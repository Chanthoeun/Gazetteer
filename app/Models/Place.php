<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Place extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
        'official_note',
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
            ->useLogName('Place')
            ->logOnly([
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
                'official_note',
            ])
            ->setDescriptionForEvent(fn(string $eventName) => "Place has been {$eventName}")
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function backups(): HasMany
    {
        return $this->hasMany(Backup::class);
    }

    public function getAllDescendantIds(): array
    {
        $allDescendantIds = [];
        $levelIds = [$this->id];

        while (count($levelIds) > 0) {
            $childrenIds = self::whereIn('parent_id', $levelIds)->pluck('id')->toArray();
            if (count($childrenIds) > 0) {
                $allDescendantIds = array_merge($allDescendantIds, $childrenIds);
                $levelIds = $childrenIds;
            } else {
                $levelIds = [];
            }
        }

        return $allDescendantIds;
    }
}
