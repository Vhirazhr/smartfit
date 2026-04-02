document.addEventListener('DOMContentLoaded', function () {
    const measureForm = document.getElementById('measureBodyForm');
    const demoBtn = document.getElementById('measureDemo');
    const statusEl = document.getElementById('measureBodyStatus');
    const resultBox = document.getElementById('measureBodyResult');

    if (!measureForm || !statusEl || !resultBox) {
        return;
    }

    const bustInput = document.getElementById('bustInput');
    const waistInput = document.getElementById('waistInput');
    const hipInput = document.getElementById('hipInput');
    const submitBtn = document.getElementById('measureSubmit');

    const morphotypeEl = document.getElementById('resultMorphotype');
    const focusEl = document.getElementById('resultFocus');
    const bRatioEl = document.getElementById('resultBRatio');
    const hRatioEl = document.getElementById('resultHRatio');
    const topsEl = document.getElementById('resultTops');
    const bottomsEl = document.getElementById('resultBottoms');
    const avoidEl = document.getElementById('resultAvoid');

    function renderList(container, items) {
        if (!container) {
            return;
        }

        container.innerHTML = '';
        (items || []).forEach(function (item) {
            const li = document.createElement('li');
            li.textContent = item;
            container.appendChild(li);
        });
    }

    function parseInput(input) {
        return Number.parseFloat((input && input.value) || '');
    }

    async function submitMeasureBody(event) {
        event.preventDefault();

        const payload = {
            bust: parseInput(bustInput),
            waist: parseInput(waistInput),
            hip: parseInput(hipInput),
        };

        if (Number.isNaN(payload.bust) || Number.isNaN(payload.waist) || Number.isNaN(payload.hip)) {
            statusEl.textContent = 'Please fill bust, waist, and hip with valid numbers.';
            resultBox.classList.add('hidden');
            return;
        }

        statusEl.textContent = 'Analyzing your body ratio...';
        submitBtn.disabled = true;

        try {
            const response = await fetch('/api/recommendations', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const json = await response.json();

            if (!response.ok || !json || !json.data || !json.data.classification) {
                const errorMessage = (json && json.message) || 'Unable to process measurement right now.';
                throw new Error(errorMessage);
            }

            const classification = json.data.classification;
            const recommendation = (json.data.recommendation && json.data.recommendation.recommendations) || {};

            morphotypeEl.textContent = classification.label || classification.morphotype || '-';
            focusEl.textContent = recommendation.focus || 'No recommendation focus available yet.';
            bRatioEl.textContent = (classification.ratios && classification.ratios.bust_to_waist) ?? '-';
            hRatioEl.textContent = (classification.ratios && classification.ratios.hip_to_waist) ?? '-';

            renderList(topsEl, recommendation.tops);
            renderList(bottomsEl, recommendation.bottoms);
            renderList(avoidEl, recommendation.avoid);

            resultBox.classList.remove('hidden');
            statusEl.textContent = 'Analysis complete.';
        } catch (error) {
            statusEl.textContent = error.message || 'Request failed. Please try again.';
            resultBox.classList.add('hidden');
        } finally {
            submitBtn.disabled = false;
        }
    }

    measureForm.addEventListener('submit', submitMeasureBody);

    if (demoBtn) {
        demoBtn.addEventListener('click', function () {
            if (bustInput) {
                bustInput.value = '92';
            }
            if (waistInput) {
                waistInput.value = '72';
            }
            if (hipInput) {
                hipInput.value = '98';
            }
            statusEl.textContent = 'Demo values loaded. Click Analyze My Morphotype.';
        });
    }
});
