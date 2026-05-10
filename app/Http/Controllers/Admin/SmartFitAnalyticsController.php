<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmartFitUsageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SmartFitAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(static function (Request $request, $next) {
            if (! session()->has('admin')) {
                return redirect('/admin/login');
            }

            return $next($request);
        });
    }

    public function index(Request $request): View
    {
        $filters = $this->resolveFilters($request);
        $baseQuery = SmartFitUsageLog::query()->filter($filters);

        $totalUsage = (clone $baseQuery)->count();
        $calculatedUsage = (clone $baseQuery)->where('flow_type', SmartFitUsageLog::FLOW_CALCULATED)->count();
        $knownUsage = (clone $baseQuery)->where('flow_type', SmartFitUsageLog::FLOW_KNOWN)->count();
        $todayUsage = (clone $baseQuery)->whereDate('created_at', now()->toDateString())->count();

        $topCountry = $this->countryDistribution(clone $baseQuery, 1)->first();
        $topBodyType = $this->bodyTypeDistribution(clone $baseQuery, 1)->first();

        return view('admin.smartfit-analytics.index', [
            'filters' => $filters,
            'stats' => [
                'total_usage' => $totalUsage,
                'calculated_usage' => $calculatedUsage,
                'known_usage' => $knownUsage,
                'today_usage' => $todayUsage,
                'top_country' => $topCountry,
                'top_body_type' => $topBodyType,
            ],
            'monthlyTrend' => $this->monthlyTrend(clone $baseQuery),
            'flowDistribution' => $this->flowDistribution(clone $baseQuery),
            'countryDistribution' => $this->countryDistribution(clone $baseQuery, 8),
            'bodyTypeDistribution' => $this->bodyTypeDistribution(clone $baseQuery, 8),
            'styleDistribution' => $this->styleDistribution(clone $baseQuery, 8),
            'recentLogs' => (clone $baseQuery)->latest()->paginate(12)->withQueryString(),
            'countryOptions' => $this->countryOptions(),
            'bodyTypes' => config('smartfit.body_types', []),
            'labels' => config('smartfit.labels', []),
        ]);
    }

    private function resolveFilters(Request $request): array
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'flow_type' => ['nullable', 'in:'.SmartFitUsageLog::FLOW_CALCULATED.','.SmartFitUsageLog::FLOW_KNOWN],
            'country_code' => ['nullable', 'alpha', 'size:2'],
            'morphotype' => ['nullable', 'in:'.implode(',', config('smartfit.body_types', []))],
        ]);

        return [
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'flow_type' => $validated['flow_type'] ?? null,
            'country_code' => isset($validated['country_code']) ? strtoupper((string) $validated['country_code']) : null,
            'morphotype' => $validated['morphotype'] ?? null,
        ];
    }

    private function monthlyTrend($query)
    {
        $startDate = now()->startOfMonth()->subMonths(11);

        $rows = $query
            ->where('created_at', '>=', $startDate)
            ->get(['created_at'])
            ->groupBy(fn (SmartFitUsageLog $log): string => $log->created_at->format('Y-m'))
            ->map->count();

        return collect(range(11, 0))->map(function (int $monthsAgo) use ($rows): array {
            $date = now()->startOfMonth()->subMonths($monthsAgo);
            $dateKey = $date->format('Y-m');

            return [
                'date' => $dateKey,
                'label' => Carbon::parse($dateKey.'-01')->format('M y'),
                'total' => (int) ($rows[$dateKey] ?? 0),
            ];
        });
    }

    private function flowDistribution($query)
    {
        return $query
            ->selectRaw('flow_type, COUNT(*) as total')
            ->groupBy('flow_type')
            ->orderByDesc('total')
            ->get()
            ->map(fn (SmartFitUsageLog $item): array => [
                'key' => $item->flow_type,
                'label' => $item->flow_label,
                'total' => (int) $item->total,
            ]);
    }

    private function countryDistribution($query, int $limit)
    {
        return $query
            ->selectRaw('country_code, country_name, COUNT(*) as total')
            ->groupBy('country_code', 'country_name')
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->map(fn (SmartFitUsageLog $item): array => [
                'country_code' => $item->country_code,
                'country_name' => $item->country_name ?: ($item->country_code ?: 'Unknown'),
                'total' => (int) $item->total,
            ]);
    }

    private function bodyTypeDistribution($query, int $limit)
    {
        $labels = config('smartfit.labels', []);

        return $query
            ->whereNotNull('morphotype')
            ->selectRaw('morphotype, body_type, COUNT(*) as total')
            ->groupBy('morphotype', 'body_type')
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->map(fn (SmartFitUsageLog $item): array => [
                'key' => $item->morphotype,
                'label' => $labels[$item->morphotype] ?? $item->body_type ?? Str::title(str_replace('_', ' ', (string) $item->morphotype)),
                'total' => (int) $item->total,
            ]);
    }

    private function styleDistribution($query, int $limit)
    {
        return $query
            ->whereNotNull('style_preference')
            ->selectRaw('style_preference, COUNT(*) as total')
            ->groupBy('style_preference')
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->map(fn (SmartFitUsageLog $item): array => [
                'label' => $item->style_preference,
                'total' => (int) $item->total,
            ]);
    }

    private function countryOptions()
    {
        return SmartFitUsageLog::query()
            ->whereNotNull('country_code')
            ->select('country_code', 'country_name')
            ->distinct()
            ->orderBy('country_name')
            ->get();
    }
}
