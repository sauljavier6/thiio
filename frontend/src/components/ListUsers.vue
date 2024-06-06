<template>
  <v-container>
    <h1>Users</h1>
    <v-row>
      <v-col
        v-for="user in users"
        :key="user.id"
        cols="12"
        md="4"
      >
        <v-card>
          <v-card-title>{{ user.name }} {{ user.surname }}</v-card-title>
          <v-card-actions>
            <v-btn color="primary" @click="updateUser(user.id)">Update</v-btn>
            <v-btn color="error" @click="deleteUser(user.id)">Delete</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
    <div v-if="error" class="error">{{ error }}</div>
  </v-container>
</template>

<script>
import axios from 'axios';
import { global } from '../services/global'; // Importar global para la ruta de la API

export default {
  data() {
    return {
      users: [], // Aquí se almacenarán los usuarios obtenidos de la API
      error: null // Aquí se almacenarán los errores de la API
    };
  },
  mounted() {
    this.fetchUsers();
  },
  methods: {
    async fetchUsers() {
      try {
        const token = localStorage.getItem('token');
        if (!token) {
          throw new Error('No token found');
        }
        const response = await axios.get(global.url+'user/list', {
          headers: {
            'Authorization': token
          }
        });
        this.users = response.data;
      } catch (error) {
        this.error = 'Error fetching users: ' + error.message;
      }
    },

    updateUser(userId) {
      this.$router.push('/update/'+userId);
    },

    async deleteUser(userId) {
  
      try {
        const token = localStorage.getItem('token');
        if (!token) {
          throw new Error('No token found');
        }
        await axios.delete(global.url+'user/delete/'+userId, {
          headers: {
            'Authorization': token
          }
        });
        this.users = this.users.filter(user => user.id !== userId);
      } catch (error) {
        this.error = 'Error deleting user: ' + error.message;
      }
    }
  }
};
</script>

<style scoped>
/* Estilos específicos del componente */
.error {
  color: red;
}
</style>
