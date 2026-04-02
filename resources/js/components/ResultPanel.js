import { computed, defineComponent } from 'vue';
import { MORPHOTYPE_META } from '../data/morphotypeMeta';
import { useSmartfitStore } from '../stores/useSmartfitStore';
import Avatar2DPreview from './Avatar2DPreview';

export default defineComponent({
    name: 'ResultPanel',
    components: {
        Avatar2DPreview,
    },
    setup() {
        const store = useSmartfitStore();

        const activeMeta = computed(() => MORPHOTYPE_META[store.activeMorphotype] ?? MORPHOTYPE_META.undefined);

        return {
            store,
            activeMeta,
        };
    },
    template: `
        <section class="sf-card sf-result-card">
            <template v-if="store.hasResult">
                <h2>Result</h2>
                <p class="sf-morphotype">{{ activeMeta.title }}</p>
                <p class="sf-muted">{{ activeMeta.insight }}</p>

                <div class="sf-ratio-row" v-if="store.ratios">
                    <div class="sf-ratio-chip">
                        <span>B/W</span>
                        <strong>{{ store.ratios.bust_to_waist }}</strong>
                    </div>
                    <div class="sf-ratio-chip">
                        <span>H/W</span>
                        <strong>{{ store.ratios.hip_to_waist }}</strong>
                    </div>
                </div>

                <div class="sf-recommendation" v-if="store.recommendation">
                    <h3>Styling Focus</h3>
                    <p>{{ store.recommendation.focus }}</p>

                    <h3>Recommended Tops</h3>
                    <ul>
                        <li v-for="item in store.recommendation.tops" :key="'top-' + item">{{ item }}</li>
                    </ul>

                    <h3>Recommended Bottoms</h3>
                    <ul>
                        <li v-for="item in store.recommendation.bottoms" :key="'bottom-' + item">{{ item }}</li>
                    </ul>

                    <h3>Avoid</h3>
                    <ul>
                        <li v-for="item in store.recommendation.avoid" :key="'avoid-' + item">{{ item }}</li>
                    </ul>
                </div>

                <Avatar2DPreview />
            </template>

            <template v-else>
                <h2>Waiting for Analysis</h2>
                <p class="sf-muted">Enter your measurements to generate morphotype and style recommendations.</p>
                <div class="sf-placeholder">
                    <span>B/W and H/W ratios plus avatar preview will appear here</span>
                </div>
            </template>
        </section>
    `,
});