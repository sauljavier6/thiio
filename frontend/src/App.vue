<template>
  <v-app>
    <v-app-bar app color="primary">
      <v-toolbar-title>Thiio</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-row justify="center">
        <v-btn text v-if="isAuthenticated" to="/Create">Create</v-btn>
        <v-btn text v-if="isAuthenticated" to="/List">Users</v-btn>
      </v-row>
      <v-spacer></v-spacer>
      <v-btn text v-if="!isAuthenticated" to="/login">Log in</v-btn>
      <v-btn text v-if="isAuthenticated" to="/Logout">Log out</v-btn>
    </v-app-bar>
    <v-main>
      <v-container>
        <router-view />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: 'App',
  computed: {
    isAuthenticated() {
      return !!localStorage.getItem('token');
    }
  }
};
</script>


<style>
/* Puedes añadir estilos globales aquí */
</style>
