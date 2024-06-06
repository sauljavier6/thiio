<template>
    <v-form @submit.prevent="createUser">
      <v-container>
        <h1>Create account</h1>
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
            <v-text-field
              label="Password"
              v-model="password"
              type="password"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12">
            <v-btn color="primary" type="submit">Create</v-btn>
          </v-col>
          <v-col cols="12" v-if="error">
            <v-alert type="error">{{ error }}</v-alert>
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
        password: '',
        error: '',
      };
    },
    methods: {
      async createUser() {
        try {
          const response = await axios.post(global.url+'register', {
            name: this.name,
            surname: this.surname,
            email: this.email,
            password: this.password,
          }, {
            headers: {
              'Content-Type': 'application/json'
            }
          });
  
  
          if (response.data) {
            // Handle success, maybe redirect to another page
            this.$router.push('/list');
          }
        } catch (error) {
          if (error.response) {
            this.error = error.response.data.message || 'Error creating user.';
          } else {
            this.error = 'An error occurred. Please try again.';
          }
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .error {
    color: red;
    margin-top: 10px;
  }
  </style>
  