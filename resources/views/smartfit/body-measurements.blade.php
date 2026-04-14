@extends('layouts.app')

@section('title', 'SMARTfit - Body Measurement Dashboard')

@section('content')
<section class="smartfit-body-measurements">
    <div class="smartfit-body-measurements-container">
        <div class="dashboard-header reveal-item">
            <div>
                <p class="header-tag">Operational Analytics</p>
                <h1>Body Measurement Dashboard</h1>
                <p class="header-subtitle">Track accepted/rejected attempts and monitor morphotype input distribution.</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('smartfit.input') }}" class="btn-secondary">New Measurement</a>
                <a href="{{ route('smartfit.start') }}" class="btn-primary">SmartFit Home</a>
            </div>
        </div>

        <div class="metrics-grid reveal-item">
            <article class="metric-card">
                <span>Total Measurements</span>
                <strong>{{ number_format($totalMeasurements) }}</strong>
            </article>
            <article class="metric-card">
                <span>Today</span>
                <strong>{{ number_format($todayMeasurements) }}</strong>
            </article>
            <article class="metric-card">
                <span>Avg B/W</span>
                <strong>{{ number_format($avgBwRatio, 2) }}</strong>
            </article>
            <article class="metric-card">
                <span>Avg H/W</span>
                <strong>{{ number_format($avgHwRatio, 2) }}</strong>
            </article>
            <article class="metric-card warning">
                <span>Rejected Attempts</span>
                <strong>{{ number_format($rejectedAttempts) }}</strong>
                <em>{{ number_format($rejectionRate, 2) }}% of all attempts</em>
            </article>
            <article class="metric-card warning">
                <span>Consistency Rejections</span>
                <strong>{{ number_format($consistencyRejections) }}</strong>
                <em>{{ number_format($consistencyRejectionRate, 2) }}% of rejected</em>
            </article>
        </div>

        <div class="dashboard-layout">
            <article class="dashboard-card reveal-item">
                <div class="card-head">
                    <h3>Morphotype Distribution</h3>
                </div>

                @if($morphotypeDistribution->isEmpty())
                    <p class="empty-state">No body measurement data available yet.</p>
                @else
                    <ul class="distribution-list">
                        @foreach($morphotypeDistribution as $item)
                            <li>
                                <span>{{ $item->morphotype_label }}</span>
                                <strong>{{ $item->total }}</strong>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </article>

            <article class="dashboard-card reveal-item">
                <div class="card-head">
                    <h3>Recent Rejected Attempts</h3>
                </div>

                @if($latestRejections->isEmpty())
                    <p class="empty-state">No rejected attempts recorded.</p>
                @else
                    <ul class="rejections-list">
                        @foreach($latestRejections as $attempt)
                            <li>
                                <div>
                                    <strong>#{{ $attempt->id }}</strong>
                                    <span>{{ $attempt->created_at?->format('d M Y H:i') }}</span>
                                </div>
                                <p>
                                    {{ implode(' | ', $attempt->rejection_reasons ?? ['Validation rejected']) }}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </article>
        </div>

        <article class="dashboard-card reveal-item table-card">
            <div class="card-head split">
                <h3>Measurement Records</h3>
                <form method="GET" action="{{ route('smartfit.measurements.index') }}" class="filter-form">
                    <label for="morphotype">Filter</label>
                    <select id="morphotype" name="morphotype" onchange="this.form.submit()">
                        <option value="">All Morphotypes</option>
                        @foreach(config('smartfit.labels') as $key => $label)
                            <option value="{{ $key }}" @selected($selectedMorphotype === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if($measurements->isEmpty())
                <p class="empty-state">No measurement records found for the selected filter.</p>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Bust</th>
                                <th>Waist</th>
                                <th>Hip</th>
                                <th>B/W</th>
                                <th>H/W</th>
                                <th>Morphotype</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($measurements as $measurement)
                                <tr>
                                    <td>#{{ $measurement->id }}</td>
                                    <td>{{ $measurement->created_at?->format('d M Y H:i') }}</td>
                                    <td>{{ number_format((float) $measurement->bust, 1) }}</td>
                                    <td>{{ number_format((float) $measurement->waist, 1) }}</td>
                                    <td>{{ number_format((float) $measurement->hip, 1) }}</td>
                                    <td>{{ number_format((float) $measurement->bust_to_waist_ratio, 2) }}</td>
                                    <td>{{ number_format((float) $measurement->hip_to_waist_ratio, 2) }}</td>
                                    <td>{{ $measurement->morphotype_label }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrap">
                    {{ $measurements->links() }}
                </div>
            @endif
        </article>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/smartfit-body-measurements.css') }}">
@endpush
