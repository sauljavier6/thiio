    <template>
    <v-container>
      <UpdateUser />
    </v-container>
    </template>
  
    <script>
    import UpdateUser from '@/components/UpdateUser.vue';

    export default {
    components: {
        UpdateUser
    },
    beforeCreate() {
        const token = localStorage.getItem('token');
        if (!token) {
        this.$router.push('/login');
        }
    }
    };

    </script>
  
    <style scoped>
    /* Estilos específicos de la vista */
    </style>
    