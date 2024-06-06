<template>
    <v-form @submit.prevent="updateUser">
      <v-container>
        <h1>Update User</h1>
        <v-row>
          <v-col cols="12">
            <v-text-field
              label="Name"
              v-model="name"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12">
            <v-text-field
              label="Last name"
              v-model="surname"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12">
            <v-text-field
              label="Email"
              v-model="email"
              type="email"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12">
            <v-btn color="primary" type="submit">Update</v-btn>
          </v-col>
          <v-col cols="12" v-if="error">
            <v-alert type="error">{{ error }}</v-alert>
          </v-col>
          <v-col cols="12" v-if="success">
            <v-alert type="success">{{ success }}</v-alert>
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
        name: '',
        surname: '',
        email: '',
        error: '',
      };
    },
    async created() {
      const userId = this.$route.params.id;
      await this.fetchUser(userId);
    },
    methods: {
        async fetchUser(userId) {
            try {
            const response = await axios.get(global.url+'user/detail/'+userId);

            const user = response.data.user;
            this.name = user.name;
            this.surname = user.surname;
            this.email = user.email;
            } catch (error) {
            this.error = 'Error fetching user details: ' + error.message;
            }
        },
        async updateUser() {
            const userId = this.$route.params.id;
            try {
                // Se hace la solicitud PUT a la URL de la API con el ID del usuario en la ruta
                const response = await axios.put(global.url+'user/update/'+userId, {
                name: this.name,
                surname: this.surname,
                email: this.email,
                }, {
                headers: {
                    'Content-Type': 'application/json', // Especifica que el cuerpo de la solicitud es JSON
                    'Authorization': localStorage.getItem('token') // Incluye el token de autenticación
                }
                });

                // Maneja la respuesta exitosa
                if (response.data) {
                // Redirige a la lista de usuarios o a la página deseada
                this.$router.push('/list');
                }
            } catch (error) {
                // Maneja los errores
                if (error.response) {
                this.error = error.response.data.message || 'Error updating user.';
                } else {
                this.error = 'An error occurred. Please try again.';
                }
            }
        }

    }
  };
  </script>
  
  <style scoped>
  .error {
    color: red;
    margin-top: 10px;
  }
  </style>
  