<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BodyMeasurementAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'body_measurement_id',
        'bust',
        'waist',
        'hip',
        'status',
        'rejection_reasons',
        'is_consistency_issue',
        'measurement_standard',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'bust' => 'decimal:2',
        'waist' => 'decimal:2',
        'hip' => 'decimal:2',
        'rejection_reasons' => 'array',
        'is_consistency_issue' => 'boolean',
    ];

    public function measurement(): BelongsTo
    {
        return $this->belongsTo(BodyMeasurement::class, 'body_measurement_id');
    }
}
