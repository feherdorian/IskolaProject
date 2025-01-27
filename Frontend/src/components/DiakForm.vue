<template>
  <form
    @submit.prevent="onClickSubmit"
    class="row g-3 needs-validation was-validated"
  >
    <p v-if="debug">{{ itemForm }}</p>
    <div class="col-md-4 position-relative">
      <label for="nev" class="form-label">Név:</label>
      <input
        type="text"
        class="form-control"
        id="nev"
        required
        v-model="itemForm.nev"
      />
    </div>

    <div class="col-md-2 position-relative">
      <label for="osztalyId" class="form-label">osztályId:</label>
      <input
        type="text"
        class="form-control"
        id="osztalyId"
        required
        v-model="itemForm.osztalyId"
      />
    </div>

    <div class="col-md-2 position-relative">
      <label for="atlag" class="form-label">Átlag:</label>
      <input
        type="text"
        class="form-control"
        id="atlag"
        required
        v-model="itemForm.atlag"
      />
    </div>

    <div class="col-md-3 position-relative">
      <label for="osztondij" class="form-label">Ösztöndíj:</label>
      <input
        type="text"
        class="form-control"
        id="osztondij"
        required
        :value="formattedOsztondij"
        @input="updateOsztondij($event.target.value)"
      />
    </div>

    <div class="col-md-2 position-relative">
      <label for="neme" class="form-label">Neme:</label>
      <select class="form-control" id="neme" required v-model="itemForm.neme">
        <option :value="true">Férfi</option>
        <option :value="false">Nő</option>
      </select>
    </div>

    <div class="col-md-4 position-relative">
      <label for="helyseg" class="form-label">Helység:</label>
      <input
        type="text"
        class="form-control"
        id="helyseg"
        required
        v-model="itemForm.helyseg"
      />
    </div>

    <div class="col-md-3 position-relative">
      <label for="szuletett" class="form-label">Született:</label>
      <input
        type="date"
        class="form-control"
        id="szuletett"
        required
        v-model="itemForm.szuletett"
      />
    </div>
    <button type="submit" class="btn btn-success">Mentés</button>
  </form>
</template>

<script>
export default {
  props: ["itemForm", "osztalyok", "debug"],
  emits: ["saveItem"],
  computed: {
    formattedOsztondij() {
      if (!this.itemForm.osztondij) return "";
      return this.itemForm.osztondij
        .toString()
        .replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    },
  },
  methods: {
    onClickSubmit() {
      this.$emit("saveItem", this.itemForm);
    },
    updateOsztondij(value) {
      const numericValue = parseFloat(value.replace(/[^0-9.]/g, ""));
      this.itemForm.osztondij = isNaN(numericValue) ? 0 : numericValue;
    },
  },
};
</script>