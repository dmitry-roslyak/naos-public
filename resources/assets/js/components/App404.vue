<template>
  <div style="padding-top: 8rem;text-align:center;">
    <h4>404 not found</h4>
    <div style="margin:1rem">
      The requested URL {{url}} was not found on this server.
      <br />
    </div>
    <router-link :to="{ name: 'AppHome'}">Back to homepage</router-link>
  </div>
</template>

<script>
export default {
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      vm.url = to.redirectedFrom && to.redirectedFrom;
    });
  },
  data: function () {
    return {
      url: null,
    };
  },
};
</script>
