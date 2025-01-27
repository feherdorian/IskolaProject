<template>
  <form @submit.prevent="onClickSubmit" class="row g-4 needs-validation was-validated">
    <p v-if="debug">{{ itemForm }}</p>
    <div class="col-md-8 position-relative">
      <label for="osztalyNev" class="form-label">osztalyNév:</label>
      <input
        type="text"
        class="form-control"
        id="osztalyNev"
        required
        v-model="itemForm.osztalyNev"
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
      this.$emit("saveItem", this.osztalyForm);
    },
  },
};
</script>

<style>
</style>
