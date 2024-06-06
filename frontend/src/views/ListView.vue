<template>
  <v-container>
    <ListUsers />
  </v-container>
</template>

<script>
import ListUsers from '@/components/ListUsers.vue';

export default {
  components: {
    ListUsers
  },
  beforeCreate() {
    const token = localStorage.getItem('token');
    if (!token) {
      this.$router.push('/login');
    }
  }
};
</script>
