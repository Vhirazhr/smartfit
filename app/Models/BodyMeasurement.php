<?php

namespace App\Models;

use App\Models\BodyMeasurementAttempt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class BodyMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'bust',
        'waist',
        'hip',
        'bust_to_waist_ratio',
        'hip_to_waist_ratio',
        'morphotype',
        'morphotype_label',
        'measurement_standard',
        'source',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'bust' => 'decimal:2',
        'waist' => 'decimal:2',
        'hip' => 'decimal:2',
        'bust_to_waist_ratio' => 'decimal:4',
        'hip_to_waist_ratio' => 'decimal:4',
    ];

    public function attempts(): HasMany
    {
        return $this->hasMany(BodyMeasurementAttempt::class);
    }

    public function scopeForMorphotype($query, ?string $morphotype)
    {
        if (!$morphotype) {
            return $query;
        }

        return $query->where('morphotype', $morphotype);
    }
}
