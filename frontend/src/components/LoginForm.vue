<template>
  <v-form @submit.prevent="login">
    <v-container>
      <h1>Log in</h1>
      <v-row>
        <v-col cols="12">
          <v-text-field
            label="Email"
            v-model="email"
            type="email"
            required
          ></v-text-field>
        </v-col>
        <v-col cols="12">
          <v-text-field
            label="Password"
            v-model="password"
            type="password"
            required
          ></v-text-field>
        </v-col>
        <v-col cols="12">
          <v-btn color="primary" type="submit">Log in</v-btn>
        </v-col>
        <v-col cols="12" v-if="error">
          <v-alert type="error">{{ error }}</v-alert>
        </v-col>
        <v-col cols="12">
          <router-link to="/create" class="register-link">Create Account</router-link> <!-- Enlace a la página de registro -->
        </v-col>
      </v-row>
    </v-container>
  </v-form>
</template>


<script>
import axios from 'axios';
import { global } from '../services/global';

export default {
  data() {
    return {
      email: '',
      password: '',
      error: '',
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post(global.url + 'login', {
          email: this.email,
          password: this.password,
        }, {
          headers: {
            'Content-Type': 'application/json'
          }
        });

        if (response.data) {
          localStorage.setItem('token', response.data.token);
          this.$router.push('/list');
        }
      } catch (error) {
        if (error.response) {
          this.error = error.response.data.message || 'Failed to login.';
        } else {
          this.error = 'An error has occurred. Please try again.';
        }
      }
    }
  },
};
</script>

<style scoped>
.register-link {
  color: #1976d2; /* Color del enlace */
  text-decoration: none; /* Sin subrayado */
  cursor: pointer;
}

.register-link:hover {
  text-decoration: underline; /* Subrayado al pasar el cursor */
}

.error {
  color: red;
  margin-top: 10px;
}
</style>
