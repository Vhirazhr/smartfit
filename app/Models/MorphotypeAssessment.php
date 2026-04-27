<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MorphotypeAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'body_measurement_id',
        'assessor_code',
        'assessed_morphotype',
        'predicted_morphotype',
        'bw_ratio',
        'hw_ratio',
        'notes',
        'assessed_at',
    ];

    protected $casts = [
        'bw_ratio' => 'decimal:4',
        'hw_ratio' => 'decimal:4',
        'assessed_at' => 'datetime',
    ];

    public function measurement(): BelongsTo
    {
        return $this->belongsTo(BodyMeasurement::class, 'body_measurement_id');
    }
}
