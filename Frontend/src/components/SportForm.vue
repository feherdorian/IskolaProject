<template>
  <form @submit.prevent="onClickSubmit" class="row g-4 needs-validation was-validated">
    <p v-if="debug">{{ itemForm }}</p>
    <div class="col-md-8 position-relative">
      <label for="sportNev" class="form-label">sportNév:</label>
      <input
        type="text"
        class="form-control"
        id="sportNev"
        required
        v-model="itemForm.sportNev"
      />
    </div>
    <button type="submit" class="btn btn-success">Mentés</button>
  </form>
</template>

<script>
export default {
  props: ["itemForm", "debug"],
  emits: ["saveItem"],
  methods: {
    onClickSubmit() {
      this.$emit("saveItem", this.sportForm);
    },
  },
};
</script>

<style>
</style>
