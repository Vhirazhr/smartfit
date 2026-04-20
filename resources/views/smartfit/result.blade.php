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

		@if(session('csrf_expired'))
			<div class="session-expired-banner reveal-item" role="alert">
				<i class="fa-regular fa-clock"></i>
				<span>{{ session('csrf_expired') }}</span>
			</div>
		@endif

		@if(session('recommendation_missing'))
			<div class="session-expired-banner reveal-item" role="alert">
				<i class="fa-regular fa-circle-info"></i>
				<span>{{ session('recommendation_missing') }}</span>
			</div>
		@endif

		<div class="result-layout">
			<article class="measurement-card reveal-item">
				<div class="visual-wrap">
					<h3>Body Shape Visualization</h3>
					<p class="visual-caption">
						@if($source === 'calculated')
							Visual ini menyesuaikan hasil ukur Bust, Waist, dan Hip Anda.
						@else
							Visual ini menyesuaikan profil body type yang Anda pilih.
						@endif
					</p>

					<div class="visual-grid">
						<div class="shape-badge">
							<div class="shape-illustration-card">
								@if($bodyVisual['shape_key'] === 'hourglass')
									<svg viewBox="0 0 100 120" width="76" height="96" aria-label="Hourglass shape">
										<path d="M35,15 L35,40 Q50,50 35,60 L35,90 Q45,100 35,105 L65,105 Q55,100 65,90 L65,60 Q50,50 65,40 L65,15 Z" fill="none" stroke="#C5B09F" stroke-width="2.5" stroke-linecap="round"/>
										<circle cx="50" cy="50" r="10" fill="none" stroke="#C5B09F" stroke-width="2"/>
									</svg>
								@elseif($bodyVisual['shape_key'] === 'spoon')
									<svg viewBox="0 0 100 120" width="76" height="96" aria-label="Spoon shape">
										<path d="M35,15 L35,45 Q40,55 35,65 L35,95 Q40,105 35,110 L65,110 Q60,105 65,95 L65,65 Q60,55 65,45 L65,15 Z" fill="none" stroke="#C5B09F" stroke-width="2.5" stroke-linecap="round"/>
										<circle cx="50" cy="35" r="8" fill="none" stroke="#C5B09F" stroke-width="2"/>
									</svg>
								@elseif($bodyVisual['shape_key'] === 'triangle')
									<svg viewBox="0 0 100 120" width="76" height="96" aria-label="Triangle shape">
										<polygon points="50,15 30,105 70,105" fill="none" stroke="#C5B09F" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								@elseif($bodyVisual['shape_key'] === 'inverted')
									<svg viewBox="0 0 100 120" width="76" height="96" aria-label="Inverted triangle shape">
										<polygon points="30,15 70,15 50,105" fill="none" stroke="#C5B09F" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								@else
									<svg viewBox="0 0 100 120" width="76" height="96" aria-label="Rectangle shape">
										<rect x="30" y="15" width="40" height="90" rx="8" fill="none" stroke="#C5B09F" stroke-width="2.5" stroke-linecap="round"/>
									</svg>
								@endif
							</div>
							<p>{{ $bodyType }}</p>
						</div>

						<div class="proportion-visual" role="img" aria-label="Proporsi bust, waist, dan hip berdasarkan hasil ukuran">
							<div class="proportion-row">
								<span>Bust</span>
								<div class="proportion-track">
									<div class="proportion-bar bust" style="width: {{ $bodyVisual['bust_width'] }}%;"></div>
								</div>
								<strong>{{ number_format((float) $bodyVisual['bust_value'], 1) }} cm</strong>
							</div>
							<div class="proportion-row">
								<span>Waist</span>
								<div class="proportion-track">
									<div class="proportion-bar waist" style="width: {{ $bodyVisual['waist_width'] }}%;"></div>
								</div>
								<strong>{{ number_format((float) $bodyVisual['waist_value'], 1) }} cm</strong>
							</div>
							<div class="proportion-row">
								<span>Hip</span>
								<div class="proportion-track">
									<div class="proportion-bar hip" style="width: {{ $bodyVisual['hip_width'] }}%;"></div>
								</div>
								<strong>{{ number_format((float) $bodyVisual['hip_value'], 1) }} cm</strong>
							</div>
						</div>
					</div>
				</div>

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
				@if(session('recommendation_updated'))
					<div class="recommendation-updated-banner" role="status">
						<i class="fa-regular fa-circle-check"></i>
						<span>{{ session('recommendation_updated') }}</span>
					</div>
				@endif

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

				@php
					$stylePreferenceCards = [
						[
							'name' => 'Casual',
							'icon' => 'fa-solid fa-shirt',
							'description' => 'Relaxed, simple, and comfortable for everyday wear.',
							'example' => 'T-shirt - Jeans - Sneakers',
						],
						[
							'name' => 'Formal',
							'icon' => 'fa-solid fa-briefcase',
							'description' => 'Neat, elegant, and polished for work or special occasions.',
							'example' => 'Blazer - Trousers - Heels',
						],
						[
							'name' => 'Bohemian',
							'icon' => 'fa-solid fa-leaf',
							'description' => 'Free-spirited, artistic, and soft with layered details.',
							'example' => 'Flowy Dress - Layered - Earth Tone',
						],
						[
							'name' => 'Classic',
							'icon' => 'fa-solid fa-gem',
							'description' => 'Timeless, refined, and balanced with clean silhouettes.',
							'example' => 'Midi Dress - Neutral Tone - Pumps',
						],
						[
							'name' => 'Sporty',
							'icon' => 'fa-solid fa-dumbbell',
							'description' => 'Active, practical, and energetic with easy movement.',
							'example' => 'Hoodie - Jogger - Running Shoes',
						],
					];
					$selectedStylePreference = (string) ($stylePreference ?? '');
				@endphp

				<section id="style-preference-panel" class="style-preference-panel" aria-label="Style preference options">
					<div class="style-preference-head">
						<span class="style-step-badge">02</span>
						<div class="style-heading-copy">
							<h4>Style Preference</h4>
							<p>Choose the fashion style that best matches your personality and daily look.</p>
						</div>
					</div>

					<form action="{{ route('smartfit.get.recommendation') }}" method="POST" class="style-preference-form">
						@csrf
						<div class="style-preference-grid">
							@foreach($stylePreferenceCards as $styleCard)
								<label class="style-preview-option">
									<input
										type="radio"
										name="style_preference"
										value="{{ $styleCard['name'] }}"
										{{ strcasecmp($selectedStylePreference, $styleCard['name']) === 0 ? 'checked' : '' }}
										required
									>
									<div class="style-preview-card">
										<div class="style-preview-icon">
											<i class="{{ $styleCard['icon'] }}"></i>
										</div>
										<div class="style-preview-text">
											<span class="style-preview-title">{{ $styleCard['name'] }}</span>
											<p class="style-preview-desc">{{ $styleCard['description'] }}</p>
											<span class="style-preview-example">{{ $styleCard['example'] }}</span>
										</div>
										<div class="style-preview-check">
											<i class="fa-regular fa-circle-check"></i>
										</div>
									</div>
								</label>
							@endforeach
						</div>
					</form>
				</section>
			</article>
		</div>

		<div class="result-actions reveal-item">
			{{-- <a href="{{ route('smartfit.measurements.index') }}" class="btn-secondary">
				<i class="fa-solid fa-chart-column"></i> View Dashboard
			</a> --}}
			<a href="{{ route('smartfit.input') }}" class="btn-secondary">
				<i class="fa-solid fa-rotate-right"></i> Re-measure
			</a>
			@if($stylePreference)
				<a href="{{ route('smartfit.recommendation') }}" class="btn-primary">
					See your recommendation <i class="fa-solid fa-arrow-right"></i>
				</a>
			@else
				<a href="#style-preference-panel" class="btn-primary">
					Select style first <i class="fa-solid fa-arrow-right"></i>
				</a>
			@endif
		</div>
	</div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/smartfit-result.css') }}">
@endpush
