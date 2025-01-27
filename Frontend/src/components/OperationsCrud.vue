<template>
  <div>
    <button
      type="button"
      class="btn btn-outline-danger btn-sm"
      data-bs-toggle="modal"
      data-bs-target="#modal"
      @click="onClickDeleteButton(data)"
    >
      <i class="bi bi-trash3"></i>
    </button>

    <button
      type="button"
      class="btn btn-outline-primary btn-sm ms-2"
      data-bs-toggle="modal"
      data-bs-target="#modal"
      @click="onClickUpdate(data)"
    >
      <i class="bi bi-pencil"></i>
    </button>

    <button
      type="button"
      class="btn btn-outline-success btn-sm ms-2"
      data-bs-toggle="modal"
      data-bs-target="#modal"
      @click="onClickCreate()"
    >
      <i class="bi bi-plus-lg"></i>
    </button>
  </div>
</template>

<script>
export default {
  props: ["data"],
  emits: ["onClickDeleteButton", "onClickUpdate", "onClickCreate"],
  methods: {
    onClickDeleteButton(data) {
      this.$emit("onClickDeleteButton", data);
    },

    onClickUpdate(data) {
      this.$emit("onClickUpdate", data);
    },

    onClickCreate() {
      this.$emit("onClickCreate");
    }
  },
};
</script>

<style>
</style>
