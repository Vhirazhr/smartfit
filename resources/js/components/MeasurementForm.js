import { defineComponent } from 'vue';
import { useSmartfitStore } from '../stores/useSmartfitStore';

export default defineComponent({
    name: 'MeasurementForm',
    setup() {
        const store = useSmartfitStore();

        return {
            store,
        };
    },
    template: `
        <section class="sf-card sf-form-card">
            <h2>Input Measurements</h2>
            <p class="sf-muted">Use centimeters for consistent proportion results.</p>

            <form @submit.prevent="store.submitMeasurements" class="sf-form">
                <label>
                    Bust (B)
                    <input v-model="store.form.bust" type="number" min="0.1" step="0.1" placeholder="e.g. 92" />
                    <small v-if="store.firstError('bust')" class="sf-error">{{ store.firstError('bust') }}</small>
                </label>

                <label>
                    Waist (W)
                    <input v-model="store.form.waist" type="number" min="0.1" step="0.1" placeholder="e.g. 72" />
                    <small v-if="store.firstError('waist')" class="sf-error">{{ store.firstError('waist') }}</small>
                </label>

                <label>
                    Hip (H)
                    <input v-model="store.form.hip" type="number" min="0.1" step="0.1" placeholder="e.g. 98" />
                    <small v-if="store.firstError('hip')" class="sf-error">{{ store.firstError('hip') }}</small>
                </label>

                <div v-if="store.apiError" class="sf-api-error">{{ store.apiError }}</div>

                <div class="sf-action-row">
                    <button type="submit" :disabled="store.loading" class="sf-btn sf-btn-primary">
                        {{ store.loading ? 'Analyzing...' : 'Analyze Shape' }}
                    </button>
                    <button type="button" class="sf-btn" @click="store.useDemoValues">Demo Values</button>
                    <button type="button" class="sf-btn" @click="store.clearForm">Reset</button>
                </div>
            </form>
        </section>
    `,
});