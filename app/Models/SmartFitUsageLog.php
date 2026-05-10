<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmartFitUsageLog extends Model
{
    use HasFactory;

    public const FLOW_CALCULATED = 'calculated';
    public const FLOW_KNOWN = 'known';

    protected $table = 'smartfit_usage_logs';

    protected $fillable = [
        'flow_type',
        'body_measurement_id',
        'body_type',
        'morphotype',
        'style_preference',
        'color_tone',
        'bust',
        'waist',
        'hip',
        'bust_to_waist_ratio',
        'hip_to_waist_ratio',
        'ip_address',
        'country_code',
        'country_name',
        'region',
        'city',
        'user_agent',
    ];

    protected $casts = [
        'bust' => 'decimal:2',
        'waist' => 'decimal:2',
        'hip' => 'decimal:2',
        'bust_to_waist_ratio' => 'decimal:4',
        'hip_to_waist_ratio' => 'decimal:4',
    ];

    public function bodyMeasurement(): BelongsTo
    {
        return $this->belongsTo(BodyMeasurement::class, 'body_measurement_id');
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when(filled($filters['start_date'] ?? null), function ($query) use ($filters): void {
                $query->whereDate('created_at', '>=', $filters['start_date']);
            })
            ->when(filled($filters['end_date'] ?? null), function ($query) use ($filters): void {
                $query->whereDate('created_at', '<=', $filters['end_date']);
            })
            ->when(filled($filters['flow_type'] ?? null), function ($query) use ($filters): void {
                $query->where('flow_type', $filters['flow_type']);
            })
            ->when(filled($filters['country_code'] ?? null), function ($query) use ($filters): void {
                $query->where('country_code', strtoupper((string) $filters['country_code']));
            })
            ->when(filled($filters['morphotype'] ?? null), function ($query) use ($filters): void {
                $query->where('morphotype', $filters['morphotype']);
            });
    }

    public function getFlowLabelAttribute(): string
    {
        return match ($this->flow_type) {
            self::FLOW_CALCULATED => 'Calculated',
            self::FLOW_KNOWN => 'Known Body Type',
            default => ucfirst((string) $this->flow_type),
        };
    }

    public function getMaskedIpAddressAttribute(): string
    {
        $ipAddress = (string) $this->ip_address;

        if ($ipAddress === '') {
            return '-';
        }

        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ipAddress);

            return $parts[0].'.***.***.'.$parts[3];
        }

        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $parts = explode(':', $ipAddress);

            return ($parts[0] ?: '****').'::****';
        }

        return '***';
    }
}
