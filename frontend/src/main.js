// src/main.js
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import vuetify from './plugins/vuetify'; // Importar Vuetify
import { loadFonts } from './plugins/webfontloader'; // Importar WebFontLoader

loadFonts();

const app = createApp(App);
app.use(store);
app.use(router);
app.use(vuetify); // Usar Vuetify
app.mount('#app');
