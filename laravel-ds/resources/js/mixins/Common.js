export default {
  methods: {
    $can(permission) {
      return this.$store.state.auth.permissions.indexOf(permission) !== -1;
    }
  }
};
