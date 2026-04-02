import { computed, defineComponent } from 'vue';
import MeasurementForm from './MeasurementForm';
import ResultPanel from './ResultPanel';

export default defineComponent({
    name: 'SmartfitApp',
    components: {
        MeasurementForm,
        ResultPanel,
    },
    setup() {
        const currentYear = computed(() => new Date().getFullYear());

        return {
            currentYear,
        };
    },
    template: `
        <div class="sf-page">
            <div class="sf-atmosphere" aria-hidden="true"></div>

            <header class="sf-header sf-reveal">
                <div>
                    <p class="sf-kicker">SmartFIT Expert System</p>
                    <h1>Body Morphotype Analyzer</h1>
                    <p class="sf-lead">Quantitative shape analysis based on Bust, Waist, and Hip ratios with immediate outfit direction.</p>
                </div>
                <div class="sf-pill-row" aria-label="System highlights">
                    <span class="sf-pill">Ratio Engine</span>
                    <span class="sf-pill">Rule Based</span>
                    <span class="sf-pill">Composition API + Pinia</span>
                </div>
            </header>

            <main class="sf-grid">
                <MeasurementForm class="sf-reveal sf-delay-1" />
                <ResultPanel class="sf-reveal sf-delay-2" />
            </main>

            <footer class="sf-footer sf-reveal sf-delay-3">
                <p>SmartFIT phase roadmap active • {{ currentYear }}</p>
            </footer>
        </div>
    `,
});