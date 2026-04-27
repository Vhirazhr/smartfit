@extends('layouts.app')

@section('title', 'SMARTfit - Standardized Body Measurement Guide')

@section('content')
<section class="smartfit-measure">
	<div class="smartfit-measure-container">
		<div class="progress-indicator">
			<div class="progress-step completed">
				<span class="step-number">1</span>
				<span class="step-label">Start</span>
			</div>
			<div class="progress-line"></div>
			<div class="progress-step active">
				<span class="step-number">2</span>
				<span class="step-label">Measure</span>
			</div>
			<div class="progress-line"></div>
			<div class="progress-step">
				<span class="step-number">3</span>
				<span class="step-label">Review</span>
			</div>
			<div class="progress-line"></div>
			<div class="progress-step">
				<span class="step-number">4</span>
				<span class="step-label">Result</span>
			</div>
		</div>

		<div class="measure-grid">
			<aside class="guidance-card reveal-item">
				<div class="guidance-tag">ISO 8559-1 Aligned</div>
				<h2>How to Measure Correctly</h2>
				<p>
					Follow these anthropometric instructions to minimize human error and improve classification accuracy.
				</p>

				<ul class="guideline-list">
					<li><i class="fa-regular fa-person-standing"></i>Stand upright with a relaxed posture.</li>
					<li><i class="fa-regular fa-ruler"></i>Use a flexible measuring tape in centimeters.</li>
					<li><i class="fa-regular fa-minus"></i>Keep tape horizontal and snug, without compression.</li>
					<li><i class="fa-solid fa-tshirt"></i>Wear light or fitted clothing while measuring.</li>
					<li><i class="fa-regular fa-repeat"></i>Re-measure when values look inconsistent.</li>
				</ul>

				<div class="mistake-box">
					<h4><i class="fa-regular fa-lightbulb"></i>Common Mistakes</h4>
					<p>Tilted tape, breathing in too much, and measuring the wrong waist point can change results significantly.</p>
				</div>
			</aside>

			<form action="{{ route('smartfit.calculate') }}" method="POST" class="measure-form reveal-item" id="measurementWizardForm" novalidate>
				@csrf

				<div class="form-header">
					<h1>Body Measurement Wizard</h1>
					<p>Complete each step and enter values in <strong>cm</strong>.</p>
				</div>

				@if ($errors->any())
					<div class="form-alert" role="alert">
						<h4>Please re-check your measurements</h4>
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif

				<div class="wizard-steps" aria-live="polite">
					<button type="button" class="wizard-tab active" data-step="1">Bust</button>
					<button type="button" class="wizard-tab" data-step="2">Waist</button>
					<button type="button" class="wizard-tab" data-step="3">Hip</button>
					<button type="button" class="wizard-tab" data-step="4">Review</button>
				</div>

				<div class="wizard-panel active" data-panel="1">
					<div class="panel-head">
						<h3>Bust Circumference (B)</h3>
						<span class="help-tip" data-tip="Measure around the fullest part of the chest with tape parallel to the floor.">?</span>
					</div>
					<p class="panel-text">Keep your arms relaxed and ensure tape is not twisted.</p>
					<div class="diagram bust-diagram" aria-hidden="true"></div>
					<label for="bust" class="field-label">Bust (cm)</label>
					<div class="field-wrap">
						<input
							type="number"
							step="0.1"
							min="60"
							max="170"
							id="bust"
							name="bust"
							value="{{ old('bust') }}"
							required
							inputmode="decimal"
							placeholder="e.g. 89.5"
						>
						<span>cm</span>
					</div>
					@error('bust')
						<p class="field-error">{{ $message }}</p>
					@enderror
				</div>

				<div class="wizard-panel" data-panel="2">
					<div class="panel-head">
						<h3>Waist Circumference (W)</h3>
						<span class="help-tip" data-tip="Measure the natural waistline: narrowest torso point between ribcage and hip.">?</span>
					</div>
					<p class="panel-text">Do not pull your stomach in. Breathe normally before recording.</p>
					<div class="diagram waist-diagram" aria-hidden="true"></div>
					<label for="waist" class="field-label">Waist (cm)</label>
					<div class="field-wrap">
						<input
							type="number"
							step="0.1"
							min="50"
							max="160"
							id="waist"
							name="waist"
							value="{{ old('waist') }}"
							required
							inputmode="decimal"
							placeholder="e.g. 71.0"
						>
						<span>cm</span>
					</div>
					@error('waist')
						<p class="field-error">{{ $message }}</p>
					@enderror
				</div>

				<div class="wizard-panel" data-panel="3">
					<div class="panel-head">
						<h3>Hip Circumference (H)</h3>
						<span class="help-tip" data-tip="Measure around the fullest part of hips and buttocks, tape level all around.">?</span>
					</div>
					<p class="panel-text">Keep feet together and maintain horizontal tape position.</p>
					<div class="diagram hip-diagram" aria-hidden="true"></div>
					<label for="hip" class="field-label">Hip (cm)</label>
					<div class="field-wrap">
						<input
							type="number"
							step="0.1"
							min="70"
							max="180"
							id="hip"
							name="hip"
							value="{{ old('hip') }}"
							required
							inputmode="decimal"
							placeholder="e.g. 96.0"
						>
						<span>cm</span>
					</div>
					@error('hip')
						<p class="field-error">{{ $message }}</p>
					@enderror
				</div>

				<div class="wizard-panel" data-panel="4">
					<div class="panel-head">
						<h3>Review and Submit</h3>
					</div>
					<p class="panel-text">Confirm values before generating your morphotype result.</p>
					<div class="review-grid">
						<div class="review-item">
							<span>Bust</span>
							<strong id="reviewBust">-</strong>
						</div>
						<div class="review-item">
							<span>Waist</span>
							<strong id="reviewWaist">-</strong>
						</div>
						<div class="review-item">
							<span>Hip</span>
							<strong id="reviewHip">-</strong>
						</div>
						<div class="review-item">
							<span>B/W Ratio Preview</span>
							<strong id="reviewBwRatio">-</strong>
						</div>
						<div class="review-item">
							<span>H/W Ratio Preview</span>
							<strong id="reviewHwRatio">-</strong>
						</div>
					</div>
					<p class="review-note">
						If one value looks unusual, go back and re-measure before submission.
					</p>
				</div>

				<div class="form-actions">
					<a href="{{ route('smartfit.check') }}" class="btn-back">
						<i class="fa-solid fa-arrow-left"></i> Back
					</a>
					<div class="action-right">
						<button type="button" class="btn-secondary" id="prevStep">Previous</button>
						<button type="button" class="btn-primary" id="nextStep">Next</button>
						<button type="submit" class="btn-primary hidden" id="submitWizard">
							Analyze Measurements <i class="fa-solid fa-arrow-right"></i>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/smartfit-measure.css') }}">
@endpush

@push('scripts')
<script>
	(() => {
		const form = document.getElementById('measurementWizardForm');
		if (!form) {
			return;
		}

		const tabs = Array.from(form.querySelectorAll('.wizard-tab'));
		const panels = Array.from(form.querySelectorAll('.wizard-panel'));
		const nextButton = document.getElementById('nextStep');
		const prevButton = document.getElementById('prevStep');
		const submitButton = document.getElementById('submitWizard');

		const bustInput = document.getElementById('bust');
		const waistInput = document.getElementById('waist');
		const hipInput = document.getElementById('hip');

		const reviewBust = document.getElementById('reviewBust');
		const reviewWaist = document.getElementById('reviewWaist');
		const reviewHip = document.getElementById('reviewHip');
		const reviewBwRatio = document.getElementById('reviewBwRatio');
		const reviewHwRatio = document.getElementById('reviewHwRatio');

		let currentStep = 1;

		const validateField = (input) => {
			if (!input) {
				return false;
			}

			const value = Number(input.value);
			const min = Number(input.min);
			const max = Number(input.max);

			if (!Number.isFinite(value)) {
				input.reportValidity();
				return false;
			}

			if (value < min || value > max) {
				input.setCustomValidity(`Value must be between ${min} and ${max} cm.`);
				input.reportValidity();
				input.setCustomValidity('');
				return false;
			}

			input.setCustomValidity('');
			return true;
		};

		const updateReview = () => {
			const bust = Number(bustInput.value);
			const waist = Number(waistInput.value);
			const hip = Number(hipInput.value);

			reviewBust.textContent = Number.isFinite(bust) ? `${bust.toFixed(1)} cm` : '-';
			reviewWaist.textContent = Number.isFinite(waist) ? `${waist.toFixed(1)} cm` : '-';
			reviewHip.textContent = Number.isFinite(hip) ? `${hip.toFixed(1)} cm` : '-';

			if (Number.isFinite(bust) && Number.isFinite(waist) && waist > 0) {
				reviewBwRatio.textContent = (bust / waist).toFixed(2);
			} else {
				reviewBwRatio.textContent = '-';
			}

			if (Number.isFinite(hip) && Number.isFinite(waist) && waist > 0) {
				reviewHwRatio.textContent = (hip / waist).toFixed(2);
			} else {
				reviewHwRatio.textContent = '-';
			}
		};

		const setStep = (step) => {
			currentStep = Math.min(Math.max(step, 1), panels.length);

			tabs.forEach((tab) => {
				tab.classList.toggle('active', Number(tab.dataset.step) === currentStep);
			});

			panels.forEach((panel) => {
				panel.classList.toggle('active', Number(panel.dataset.panel) === currentStep);
			});

			prevButton.disabled = currentStep === 1;

			const onReviewStep = currentStep === panels.length;
			nextButton.classList.toggle('hidden', onReviewStep);
			submitButton.classList.toggle('hidden', !onReviewStep);

			if (onReviewStep) {
				updateReview();
			}
		};

		const canMoveForward = () => {
			if (currentStep === 1) {
				return validateField(bustInput);
			}

			if (currentStep === 2) {
				return validateField(waistInput);
			}

			if (currentStep === 3) {
				return validateField(hipInput);
			}

			return true;
		};

		tabs.forEach((tab) => {
			tab.addEventListener('click', () => {
				const targetStep = Number(tab.dataset.step);
				if (targetStep <= currentStep || canMoveForward()) {
					setStep(targetStep);
				}
			});
		});

		nextButton.addEventListener('click', () => {
			if (canMoveForward()) {
				setStep(currentStep + 1);
			}
		});

		prevButton.addEventListener('click', () => {
			setStep(currentStep - 1);
		});

		form.addEventListener('submit', (event) => {
			const bustOk = validateField(bustInput);
			const waistOk = validateField(waistInput);
			const hipOk = validateField(hipInput);

			if (!(bustOk && waistOk && hipOk)) {
				event.preventDefault();
				return;
			}

			const bust = Number(bustInput.value);
			const waist = Number(waistInput.value);
			const hip = Number(hipInput.value);

			if (waist > hip * 1.14 || waist > bust * 1.15 || Math.abs(bust - hip) > 70) {
				event.preventDefault();
				alert('Measurements look inconsistent. Please re-measure before continuing.');
			}
		});

		setStep({{ $errors->any() ? 1 : 1 }});
	})();
</script>
@endpush
