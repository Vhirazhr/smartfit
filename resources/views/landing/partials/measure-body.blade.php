<section id="measure-body" class="measure-body-section">
    <div class="measure-body-container">
        <div class="measure-head">
            <span class="measure-badge">BODY MEASUREMENT ANALYZER</span>
            <h2>Measure Your Body, Get Instant Fashion Direction</h2>
            <p>Input Bust, Waist, and Hip values to run SmartFIT ratio-based classification and recommendations.</p>
        </div>

        <div class="measure-grid">
            <form id="measureBodyForm" class="measure-form" novalidate>
                <div class="measure-field">
                    <label for="bustInput">Bust (cm)</label>
                    <input id="bustInput" name="bust" type="number" min="0.1" step="0.1" placeholder="e.g. 92" required>
                </div>

                <div class="measure-field">
                    <label for="waistInput">Waist (cm)</label>
                    <input id="waistInput" name="waist" type="number" min="0.1" step="0.1" placeholder="e.g. 72" required>
                </div>

                <div class="measure-field">
                    <label for="hipInput">Hip (cm)</label>
                    <input id="hipInput" name="hip" type="number" min="0.1" step="0.1" placeholder="e.g. 98" required>
                </div>

                <div class="measure-actions">
                    <button id="measureSubmit" type="submit" class="btn-primary">Analyze My Morphotype</button>
                    <button id="measureDemo" type="button" class="btn-secondary">Use Demo Data</button>
                </div>

                <p id="measureBodyStatus" class="measure-status" aria-live="polite"></p>
            </form>

            <div id="measureBodyResult" class="measure-result hidden" aria-live="polite">
                <h3 id="resultMorphotype">-</h3>
                <p id="resultFocus">-</p>

                <div class="ratio-preview">
                    <div>
                        <span>B/W</span>
                        <strong id="resultBRatio">-</strong>
                    </div>
                    <div>
                        <span>H/W</span>
                        <strong id="resultHRatio">-</strong>
                    </div>
                </div>

                <div class="result-list">
                    <h4>Tops</h4>
                    <ul id="resultTops"></ul>
                </div>

                <div class="result-list">
                    <h4>Bottoms</h4>
                    <ul id="resultBottoms"></ul>
                </div>

                <div class="result-list">
                    <h4>Avoid</h4>
                    <ul id="resultAvoid"></ul>
                </div>
            </div>
        </div>
    </div>
</section>