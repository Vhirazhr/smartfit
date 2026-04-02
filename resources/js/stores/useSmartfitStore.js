import { computed, reactive, ref } from 'vue';
import { defineStore } from 'pinia';
import { fetchRecommendations } from '../services/smartfitApi';

function toMeasurement(value) {
    return Number.parseFloat(value);
}

export const useSmartfitStore = defineStore('smartfit', () => {
    const form = reactive({
        bust: '',
        waist: '',
        hip: '',
    });

    const loading = ref(false);
    const apiError = ref('');
    const fieldErrors = ref({});
    const result = ref(null);

    const hasResult = computed(() => result.value !== null);
    const classification = computed(() => result.value?.classification ?? null);
    const recommendation = computed(() => result.value?.recommendation?.recommendations ?? null);
    const activeMorphotype = computed(() => classification.value?.morphotype ?? 'undefined');
    const ratios = computed(() => classification.value?.ratios ?? null);

    async function submitMeasurements() {
        apiError.value = '';
        fieldErrors.value = {};

        const payload = {
            bust: toMeasurement(form.bust),
            waist: toMeasurement(form.waist),
            hip: toMeasurement(form.hip),
        };

        if (Number.isNaN(payload.bust) || Number.isNaN(payload.waist) || Number.isNaN(payload.hip)) {
            apiError.value = 'Please fill bust, waist, and hip with valid numbers.';
            return;
        }

        loading.value = true;

        try {
            result.value = await fetchRecommendations(payload);
        } catch (error) {
            if (error.response?.status === 422) {
                fieldErrors.value = error.response.data?.errors ?? {};
                apiError.value = 'Some inputs are invalid. Please check the form values.';
            } else {
                apiError.value = 'Server connection failed. Please retry in a few seconds.';
            }
        } finally {
            loading.value = false;
        }
    }

    function useDemoValues() {
        form.bust = '92';
        form.waist = '72';
        form.hip = '98';
    }

    function clearForm() {
        form.bust = '';
        form.waist = '';
        form.hip = '';
        fieldErrors.value = {};
        apiError.value = '';
        result.value = null;
    }

    function firstError(field) {
        return fieldErrors.value[field]?.[0] ?? '';
    }

    return {
        form,
        loading,
        apiError,
        fieldErrors,
        result,
        hasResult,
        classification,
        recommendation,
        activeMorphotype,
        ratios,
        submitMeasurements,
        useDemoValues,
        clearForm,
        firstError,
    };
});