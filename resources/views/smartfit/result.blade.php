@extends('layouts.app')

@section('title', 'SMARTfit - Measurement Result')

@section('content')
<section class="smartfit-result">
	<div class="smartfit-result-container">
		<div class="progress-indicator">
			<div class="progress-step completed">
				<span class="step-number">1</span>
				<span class="step-label">Start</span>
			</div>
			<div class="progress-line"></div>
			<div class="progress-step completed">
				<span class="step-number">2</span>
				<span class="step-label">Measure</span>
			</div>
			<div class="progress-line"></div>
			<div class="progress-step completed">
				<span class="step-number">3</span>
				<span class="step-label">Review</span>
			</div>
			<div class="progress-line"></div>
			<div class="progress-step active">
				<span class="step-number">4</span>
				<span class="step-label">Result</span>
			</div>
		</div>

		<div class="result-hero reveal-item">
			<div class="hero-tag">SMARTfit Analysis</div>
			<h1>Your Body Type: <span>{{ $bodyType }}</span></h1>
			<p>{{ $description }}</p>
			<div class="meta-row">
				@if($measurementId)
					<span><i class="fa-regular fa-hashtag"></i>Record #{{ $measurementId }}</span>
				@endif
				@if($measurementStandard)
					<span><i class="fa-regular fa-square-check"></i>{{ $measurementStandard }}</span>
				@endif
				<span><i class="fa-regular fa-database"></i>Source: {{ ucfirst($source) }}</span>
			</div>
		</div>

		<div class="result-layout">
			<article class="measurement-card reveal-item">
				<h3>Measurement Summary (cm)</h3>
				<div class="measurement-grid">
					<div class="measurement-item">
						<span>Bust</span>
						<strong>{{ $bust ?? '-' }}</strong>
					</div>
					<div class="measurement-item">
						<span>Waist</span>
						<strong>{{ $waist ?? '-' }}</strong>
					</div>
					<div class="measurement-item">
						<span>Hip</span>
						<strong>{{ $hip ?? '-' }}</strong>
					</div>
				</div>

				@if($bwRatio && $hwRatio)
					<div class="ratio-row">
						<div class="ratio-box">
							<span>B/W Ratio</span>
							<strong>{{ number_format((float) $bwRatio, 2) }}</strong>
						</div>
						<div class="ratio-box">
							<span>H/W Ratio</span>
							<strong>{{ number_format((float) $hwRatio, 2) }}</strong>
						</div>
					</div>
				@endif

				<p class="small-note">
					Ratios are prepared for standardized morphotype calculation and outfit recommendation logic.
				</p>
			</article>

			<article class="recommendation-card reveal-item">
				<h3>Styling Guidance</h3>

				@if($recommendationFocus)
					<div class="focus-box">
						<h4>Focus</h4>
						<p>{{ $recommendationFocus }}</p>
					</div>
				@endif

				@if(!empty($recommendationTops))
					<div class="list-block">
						<h4>Recommended Tops</h4>
						<ul>
							@foreach($recommendationTops as $item)
								<li>{{ $item }}</li>
							@endforeach
						</ul>
					</div>
				@endif

				@if(!empty($recommendationBottoms))
					<div class="list-block">
						<h4>Recommended Bottoms</h4>
						<ul>
							@foreach($recommendationBottoms as $item)
								<li>{{ $item }}</li>
							@endforeach
						</ul>
					</div>
				@endif

				@if(!empty($avoid))
					<div class="list-block avoid">
						<h4>Avoid</h4>
						<ul>
							@foreach($avoid as $item)
								<li>{{ $item }}</li>
							@endforeach
						</ul>
					</div>
				@endif

				@if($styleTip)
					<p class="tip-line"><i class="fa-regular fa-shirt"></i>{{ $styleTip }}</p>
				@endif

				@if($colorTip)
					<p class="tip-line"><i class="fa-regular fa-palette"></i>{{ $colorTip }}</p>
				@endif
			</article>
		</div>

		<div class="result-actions reveal-item">
			<a href="{{ route('smartfit.measurements.index') }}" class="btn-secondary">
				<i class="fa-solid fa-chart-column"></i> View Dashboard
			</a>
			<a href="{{ route('smartfit.input') }}" class="btn-secondary">
				<i class="fa-solid fa-rotate-right"></i> Re-measure
			</a>
			<a href="{{ route('smartfit.start') }}" class="btn-primary">
				New SmartFit Session <i class="fa-solid fa-arrow-right"></i>
			</a>
		</div>
	</div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/smartfit-result.css') }}">
@endpush
