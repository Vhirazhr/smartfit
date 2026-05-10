@php
    $items = collect($items ?? []);
    $maxTotal = max(1, (int) $items->max('total'));
@endphp

@if($items->isEmpty())
    <p class="analytics-empty-state">{{ $empty ?? 'No data available.' }}</p>
@else
    <div class="analytics-bar-list">
        @foreach($items as $item)
            @php
                $label = is_array($item) ? ($item['label'] ?? '-') : ($item->label ?? '-');
                $total = (int) (is_array($item) ? ($item['total'] ?? 0) : ($item->total ?? 0));
                $width = max(4, ($total / $maxTotal) * 100);
            @endphp
            <div class="analytics-bar-row">
                <div class="analytics-bar-row-head">
                    <span>{{ $label }}</span>
                    <strong>{{ number_format($total) }}</strong>
                </div>
                <div class="analytics-bar-track">
                    <span style="width: {{ $width }}%;"></span>
                </div>
            </div>
        @endforeach
    </div>
@endif
