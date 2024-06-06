
// src/plugins/vuetify.js
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'myCustomTheme',
    themes: {
      myCustomTheme: {
        dark: false,
        colors: {
          primary: '#1976D2', // Color primario
          secondary: '#424242', // Color secundario
          accent: '#82B1FF', // Color de acento
          error: '#FF5252', // Color de error
          info: '#2196F3', // Color de información
          success: '#4CAF50', // Color de éxito
          warning: '#FFC107', // Color de advertencia
        },
      },
    },
  },
});

export default vuetify;
