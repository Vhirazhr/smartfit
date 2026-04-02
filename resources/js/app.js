import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import { createPinia } from 'pinia';
import SmartfitApp from './components/SmartfitApp';

const app = createApp(SmartfitApp);

app.use(createPinia());
app.mount('#app');
