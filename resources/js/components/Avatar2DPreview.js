import { computed, defineComponent } from 'vue';
import { useSmartfitStore } from '../stores/useSmartfitStore';

const AVATAR_THEME = {
    hourglass: { torso: '#b85e38', shoulders: '#da8f6e', hips: '#a94d2b', badge: 'Balanced Curves' },
    rectangle: { torso: '#0e7481', shoulders: '#73bac3', hips: '#0b5f6a', badge: 'Linear Balance' },
    spoon: { torso: '#8b6f2f', shoulders: '#cbb26f', hips: '#745a20', badge: 'Lower Emphasis' },
    triangle: { torso: '#6e7b3a', shoulders: '#a8bf68', hips: '#5a662d', badge: 'Bottom Dominant' },
    y_shape: { torso: '#6d5aa0', shoulders: '#a291cb', hips: '#574286', badge: 'Upper Emphasis' },
    inverted_u: { torso: '#525d66', shoulders: '#88939d', hips: '#3f4a54', badge: 'Compact Upper' },
    undefined: { torso: '#90615a', shoulders: '#b98d86', hips: '#73453e', badge: 'Boundary Case' },
};

export default defineComponent({
    name: 'Avatar2DPreview',
    setup() {
        const store = useSmartfitStore();

        const theme = computed(() => AVATAR_THEME[store.activeMorphotype] ?? AVATAR_THEME.undefined);

        return {
            store,
            theme,
        };
    },
    template: `
        <aside class="sf-avatar-panel" aria-label="2D avatar preview">
            <div class="sf-avatar-headline">
                <h3>2D Avatar Preview</h3>
                <span class="sf-avatar-badge">{{ theme.badge }}</span>
            </div>

            <div class="sf-avatar-shell">
                <svg viewBox="0 0 220 260" role="img" aria-label="Morphotype avatar silhouette">
                    <circle cx="110" cy="42" r="24" :fill="theme.shoulders" />
                    <ellipse cx="110" cy="110" rx="62" ry="44" :fill="theme.shoulders" />
                    <rect x="76" y="95" width="68" height="86" rx="30" :fill="theme.torso" />
                    <ellipse cx="110" cy="192" rx="72" ry="42" :fill="theme.hips" />
                    <rect x="82" y="184" width="22" height="58" rx="11" :fill="theme.torso" />
                    <rect x="116" y="184" width="22" height="58" rx="11" :fill="theme.torso" />
                </svg>
            </div>

            <p class="sf-avatar-caption">This avatar is a conceptual silhouette mapped from the current morphotype classification.</p>
        </aside>
    `,
});