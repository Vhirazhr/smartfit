@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Usage Analytics')

@section('content')
<div class="admin-wrapper">
    @include('admin.partials.sidebar', ['active' => 'smartfit-analytics'])

    <main class="admin-main">
        <div class="top-bar">
            <div class="top-bar-left">
                <div class="top-bar-text">
                    <h1>SmartFIT Usage Analytics</h1>
                    <p>Analisis penggunaan fitur body type calculation dan known body type.</p>
                </div>
            </div>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

        <div class="content-area smartfit-analytics-page">
            <div class="category-header analytics-page-header">
                <div class="category-title">
                    <h1>SmartFIT Analytics</h1>
                    <p>Data ini hanya mencatat user yang memakai fitur SmartFIT, bukan semua visitor website.</p>
                </div>

                <div class="category-actions">
                    <a href="{{ route('admin.dashboard') }}">Back to Dashboard</a>
                </div>
            </div>

            @if($errors->any())
                <div class="alert-box alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Filter tidak valid:</strong>
                        <ul style="margin: 6px 0 0 16px; padding: 0;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="GET" action="{{ route('admin.smartfit-analytics.index') }}" class="category-filter analytics-filter">
                <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" aria-label="Start date">
                <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" aria-label="End date">

                <select name="flow_type" aria-label="Flow type">
                    <option value="">All flows</option>
                    <option value="calculated" @selected(($filters['flow_type'] ?? '') === 'calculated')>Calculated</option>
                    <option value="known" @selected(($filters['flow_type'] ?? '') === 'known')>Known Body Type</option>
                </select>

                <select name="country_code" aria-label="Country">
                    <option value="">All countries</option>
                    @foreach($countryOptions as $country)
                        <option value="{{ $country->country_code }}" @selected(($filters['country_code'] ?? '') === $country->country_code)>
                            {{ $country->country_name ?: $country->country_code }}
                        </option>
                    @endforeach
                </select>

                <select name="morphotype" aria-label="Body type">
                    <option value="">All body types</option>
                    @foreach($bodyTypes as $bodyType)
                        <option value="{{ $bodyType }}" @selected(($filters['morphotype'] ?? '') === $bodyType)>
                            {{ $labels[$bodyType] ?? $bodyType }}
                        </option>
                    @endforeach
                </select>

                <button type="submit">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>

                <a href="{{ route('admin.smartfit-analytics.index') }}" class="analytics-reset-link">Reset</a>
            </form>

            <div class="analytics-grid smartfit-summary-grid">
                <div class="analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-card-icon primary"><i class="fas fa-chart-line"></i></div>
                    </div>
                    <div class="analytics-card-value">{{ number_format($stats['total_usage']) }}</div>
                    <div class="analytics-card-label">Total Usage</div>
                    <div class="analytics-card-change positive"><i class="fas fa-calendar-day"></i> {{ number_format($stats['today_usage']) }} today</div>
                </div>

                <div class="analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-card-icon success"><i class="fas fa-ruler-combined"></i></div>
                    </div>
                    <div class="analytics-card-value">{{ number_format($stats['calculated_usage']) }}</div>
                    <div class="analytics-card-label">Calculated Flow</div>
                    <div class="analytics-card-change positive">Input bust, waist, and hip</div>
                </div>

                <div class="analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-card-icon warning"><i class="fas fa-user-check"></i></div>
                    </div>
                    <div class="analytics-card-value">{{ number_format($stats['known_usage']) }}</div>
                    <div class="analytics-card-label">Known Flow</div>
                    <div class="analytics-card-change positive">Manual body type selection</div>
                </div>

                <div class="analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-card-icon info"><i class="fas fa-globe-asia"></i></div>
                    </div>
                    <div class="analytics-card-value analytics-card-text-value">
                        {{ $stats['top_country']['country_name'] ?? 'Unknown' }}
                    </div>
                    <div class="analytics-card-label">Top Country</div>
                    <div class="analytics-card-change positive">
                        {{ number_format($stats['top_country']['total'] ?? 0) }} usage records
                    </div>
                </div>

                <div class="analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-card-icon primary"><i class="fas fa-shapes"></i></div>
                    </div>
                    <div class="analytics-card-value analytics-card-text-value">
                        {{ $stats['top_body_type']['label'] ?? 'Unknown' }}
                    </div>
                    <div class="analytics-card-label">Top Body Type</div>
                    <div class="analytics-card-change positive">
                        {{ number_format($stats['top_body_type']['total'] ?? 0) }} usage records
                    </div>
                </div>
            </div>

            <div class="analytics-panel-grid">
                <article class="info-panel analytics-wide-panel">
                    <div class="info-panel-header">
                        <h3><i class="fas fa-chart-column" style="color:#0EA5E9;"></i> Usage Trend</h3>
                        <span class="analytics-panel-note">Last 12 months</span>
                    </div>
                    <div class="info-panel-body">
                        @php
                            $trendItems = collect($monthlyTrend)->values();
                            $maxDailyUsage = max(1, (int) $trendItems->max('total'));
                            $chartWidth = max(760, $trendItems->count() * 66);
                            $chartHeight = 280;
                            $paddingTop = 42;
                            $paddingRight = 28;
                            $paddingBottom = 58;
                            $paddingLeft = 42;
                            $plotWidth = $chartWidth - $paddingLeft - $paddingRight;
                            $plotHeight = $chartHeight - $paddingTop - $paddingBottom;
                            $bottomY = $paddingTop + $plotHeight;
                            $lastIndex = max(1, $trendItems->count() - 1);

                            $trendPoints = $trendItems->map(function (array $day, int $index) use ($paddingLeft, $paddingTop, $plotWidth, $plotHeight, $lastIndex, $maxDailyUsage): array {
                                $x = $paddingLeft + (($plotWidth / $lastIndex) * $index);
                                $y = $paddingTop + ($plotHeight - (((int) $day['total'] / $maxDailyUsage) * $plotHeight));

                                return array_merge($day, [
                                    'x' => round($x, 2),
                                    'y' => round($y, 2),
                                ]);
                            });

                            $linePoints = $trendPoints
                                ->map(fn (array $point): string => $point['x'].','.$point['y'])
                                ->join(' ');

                            $areaPoints = $trendPoints->isNotEmpty()
                                ? $trendPoints->first()['x'].','.$bottomY.' '.$linePoints.' '.$trendPoints->last()['x'].','.$bottomY
                                : '';
                        @endphp
                        <div class="usage-line-chart" role="img" aria-label="SmartFIT usage trend for the last 12 months">
                            <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" preserveAspectRatio="xMidYMid meet">
                                <defs>
                                    <linearGradient id="usageLineGradient" x1="0" y1="0" x2="1" y2="0">
                                        <stop offset="0%" stop-color="#0EA5E9" />
                                        <stop offset="100%" stop-color="#14B8A6" />
                                    </linearGradient>
                                    <linearGradient id="usageAreaGradient" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#0EA5E9" stop-opacity="0.18" />
                                        <stop offset="100%" stop-color="#14B8A6" stop-opacity="0.02" />
                                    </linearGradient>
                                </defs>

                                @for($i = 0; $i <= 4; $i++)
                                    @php
                                        $gridY = $paddingTop + (($plotHeight / 4) * $i);
                                    @endphp
                                    <line class="usage-grid-line" x1="{{ $paddingLeft }}" y1="{{ $gridY }}" x2="{{ $chartWidth - $paddingRight }}" y2="{{ $gridY }}" />
                                @endfor

                                <polygon class="usage-area" points="{{ $areaPoints }}" />
                                <polyline class="usage-line" points="{{ $linePoints }}" />

                                @foreach($trendPoints as $point)
                                    <g class="usage-point-group">
                                        <line class="usage-point-guide" x1="{{ $point['x'] }}" y1="{{ $point['y'] }}" x2="{{ $point['x'] }}" y2="{{ $bottomY }}" />
                                        <circle class="usage-point-pulse" cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="8" />
                                        <circle class="usage-point" cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="4.5" />
                                        <text class="usage-point-value" x="{{ $point['x'] }}" y="{{ max(16, $point['y'] - 14) }}">{{ $point['total'] }}</text>
                                        <text class="usage-point-label" x="{{ $point['x'] }}" y="{{ $chartHeight - 20 }}">{{ $point['label'] }}</text>
                                    </g>
                                @endforeach
                            </svg>
                        </div>
                    </div>
                </article>

                <article class="info-panel">
                    <div class="info-panel-header">
                        <h3><i class="fas fa-code-branch" style="color:#0EA5E9;"></i> Flow Split</h3>
                    </div>
                    <div class="info-panel-body">
                        @include('admin.smartfit-analytics.partials.metric-bars', ['items' => $flowDistribution, 'empty' => 'No flow data yet.'])
                    </div>
                </article>
            </div>

            <div class="analytics-panel-grid analytics-panel-grid-three">
                <article class="info-panel">
                    <div class="info-panel-header">
                        <h3><i class="fas fa-earth-asia" style="color:#0EA5E9;"></i> Top Countries</h3>
                    </div>
                    <div class="info-panel-body">
                        @include('admin.smartfit-analytics.partials.metric-bars', ['items' => $countryDistribution->map(fn ($item) => ['label' => $item['country_name'], 'total' => $item['total']]), 'empty' => 'No country data yet.'])
                    </div>
                </article>

                <article class="info-panel">
                    <div class="info-panel-header">
                        <h3><i class="fas fa-shapes" style="color:#0EA5E9;"></i> Body Types</h3>
                    </div>
                    <div class="info-panel-body">
                        @include('admin.smartfit-analytics.partials.metric-bars', ['items' => $bodyTypeDistribution, 'empty' => 'No body type data yet.'])
                    </div>
                </article>

                <article class="info-panel">
                    <div class="info-panel-header">
                        <h3><i class="fas fa-gem" style="color:#0EA5E9;"></i> Style Preference</h3>
                    </div>
                    <div class="info-panel-body">
                        @include('admin.smartfit-analytics.partials.metric-bars', ['items' => $styleDistribution, 'empty' => 'No style data yet.'])
                    </div>
                </article>
            </div>

            <div class="category-table-card analytics-table-card">
                <table class="category-table analytics-usage-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Flow</th>
                            <th>Country</th>
                            <th>Body Type</th>
                            <th>Style</th>
                            <th>Measurement</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                            <tr>
                                <td>{{ $log->created_at?->format('d M Y H:i') }}</td>
                                <td><span class="mini-badge neutral">{{ $log->flow_label }}</span></td>
                                <td>
                                    <strong>{{ $log->country_name ?: ($log->country_code ?: 'Unknown') }}</strong>
                                    @if($log->city || $log->region)
                                        <div class="analytics-muted">{{ collect([$log->city, $log->region])->filter()->join(', ') }}</div>
                                    @endif
                                </td>
                                <td>{{ $labels[$log->morphotype] ?? $log->body_type ?? '-' }}</td>
                                <td>{{ $log->style_preference ?? '-' }}</td>
                                <td>
                                    @if($log->flow_type === 'calculated')
                                        <span class="analytics-muted">B {{ number_format((float) $log->bust, 1) }} / W {{ number_format((float) $log->waist, 1) }} / H {{ number_format((float) $log->hip, 1) }}</span>
                                    @else
                                        <span class="analytics-muted">Manual selection</span>
                                    @endif
                                </td>
                                <td>{{ $log->masked_ip_address }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="category-empty">No SmartFIT usage records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="category-pagination">
                {{ $recentLogs->links() }}
            </div>
        </div>
    </main>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush
