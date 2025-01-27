<template>
  <div>
    <h1 class="text-center my-4">Névsor</h1>
    <div class="container">
      <div class="row d-flex justify-content-center">
        <div
          class="spinner-border m-0 p-0 text-center"
          role="status"
          v-if="rows.length == 0"
        >
          <span class="visually-hidden m-0">Loading...</span>
        </div>
        <div class="row d-flex justify-content-center" v-if="rows.length > 0">
          <div class="box col-sm-3" v-for="(row, index) in rows" :key="index">
            <h2 class="text-center mt-2">
              {{ row.osztalyNev }}
            </h2>
            <hr :class="['mt-0', 'mb-4', getHrClass(index)]" />
            <div v-for="(nev, index) in row.nevek" :key="index" class="diak">
              {{ nev }}
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</template>
    
<script>
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "@/stores/useAuthStore.js";
import axios from "axios";
export default {
  data() {
    return {
      urlApi: BASE_URL,
      stateAuth: useAuthStore(),
      rows: [],
    };
  },
  mounted() {
    this.getDiaks();
  },
  methods: {
    async getDiaks() {
      const url = `${this.urlApi}/queryOsztalynevsorObj`;
      const headers = {
        Accept: "application/json",
      };
      const response = await axios.get(url, headers);
      this.rows = response.data.data;
    },
    getHrClass(index) {
      const classes = ["hr-blue", "hr-orange", "hr-red"];
      return classes[index % 3];
    },
  },
};
</script>
    
<style scoped>
.box {
  border: 1px solid #ccc;
  border-radius: 5px;
  margin-bottom: 15px;
  margin-left: 10px;
  width: 300px;
}

.diak {
  padding: 5px;
  border-bottom: 1px solid #ccc;
  transition: 0.3s;
}

.diak:last-child {
  border-bottom: none;
  margin-bottom: 20px;
}

.diak:hover {
  background: #22222209;
}

p {
  font-family: Arial, Helvetica, sans-serif;
  color: #666;
  margin-bottom: 5px;
}

hr {
  border-width: 5px;
  border-radius: 5px;
}

.hr-blue {
  border-color: blue;
  /* border-image: linear-gradient(to right, #fff 0%, blue 50%, #fff 100%) 1; */
}

.hr-orange {
  border-color: orange;
  /* border-image: linear-gradient(to right, #fff 0%, orange 50%, #fff 100%) 1; */
}

.hr-red {
  border-color: red;
  /* border-image: linear-gradient(to right, #fff 0%, red 50%, #fff 100%) 1; */
}
</style>
    