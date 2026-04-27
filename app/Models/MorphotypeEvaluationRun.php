<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MorphotypeEvaluationRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dataset_label',
        'total_samples',
        'agreements',
        'agreement_percentage',
        'confusion_matrix',
        'baseline_percentage',
        'notes',
        'evaluated_at',
    ];

    protected $casts = [
        'confusion_matrix' => 'array',
        'agreement_percentage' => 'decimal:2',
        'baseline_percentage' => 'decimal:2',
        'evaluated_at' => 'datetime',
    ];
}
