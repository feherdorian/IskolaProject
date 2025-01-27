<template>
  <div
    class="alert alert-danger alert-dismissible fade show text-center"
    role="alert"
    v-if="errorMessages"
  >
      {{ errorMessages }}
  </div>
</template>

<script>
export default {
  props: ["errorMessages"],
  methods: {
    onClose() {
      this.$emit("close");
    },
  },
  watch: {
    errorMessages(newVal) {
      if (newVal) {
        setTimeout(() => {
          this.$emit("close");
        }, 4000);
      }
    },
  },
};
</script>

<style>
</style>
