
  <template>
    <div>Cerrando sesión...</div>
  </template>
  
  <script>
  export default {
    created() {
      localStorage.removeItem('token');
      this.$router.push('/login');
    },
  };
  </script>
  