<!-- src/views/LoginView.vue -->
<template>
  <div>
    <LoginForm />
  </div>
</template>

<script>
import LoginForm from '../components/LoginForm.vue';

export default {
  name: 'LoginView',
  components: {
    LoginForm,
  },
};
</script>

<style scoped>
/* Estilos específicos de la vista de inicio de sesión */
</style>
