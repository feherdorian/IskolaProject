<template>
  <div>
    <h1>Diák kereső</h1>
    <div class="d-flex" role="search">
      <input
        class="form-control me-2"
        type="search"
        placeholder="Search"
        aria-label="Search"
        v-model="searchInput"
      />
    </div>

    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Osztálynév</th>
          <th>Név</th>
          <th>Nem</th>
          <th>Születési idő</th>
          <th>Helység</th>
          <th>Ösztöndíj</th>
          <th>Átlag</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in paginatedRows" :key="row.id">
          <td data-label="ID">{{ row.id }}</td>
          <td data-label="Osztály">{{ getClassName(row.osztalyId) }}</td>
          <td data-label="Név">{{ row.nev }}</td>
          <td data-label="Nem">{{ row.neme ? "Férfi" : "Nő" }}</td>
          <td data-label="Születési idő">
            {{ row.szuletett }} (<span class="eletkor">{{ getEletkor(row.szuletett) }}</span> éves)
          </td>
          <td data-label="Helység">{{ row.helyseg }}</td>
          <td data-label="Ösztöndíj">{{ formatOsztondij(row.osztondij) }}</td>
          <td data-label="Átlag" style="justify-content: space-between">
            {{ row.atlag }}
          </td>
        </tr>
      </tbody>
    </table>

    <div class="d-flex justify-content-center my-3">
      <div class="pagination-container d-flex">
        <div
          v-for="page in totalPages"
          :key="page"
          @click="goToPage(page)"
          :class="['page-box', { 'active-page': currentPage === page }]"
        >
          {{ page }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { BASE_URL } from "../helpers/baseUrls";
import axios from "axios";
export default {
  data() {
    return {
      urlApi: BASE_URL,
      rows: [],
      osztalyok: [],
      searchInput: "",
      searchWord: "",
      currentPage: 1,
      itemsPerPage: 10,
      totalItems: 0,
    };
  },
  computed: {
    paginatedRows() {
      const startIndex = (this.currentPage - 1) * this.itemsPerPage;
      const endIndex = startIndex + this.itemsPerPage;
      return this.rows.slice(startIndex, endIndex);
    },

    totalPages() {
      return Math.ceil(this.rows.length / this.itemsPerPage);
    },
  },
  watch: {
    searchInput(newSearchInput) {
      this.searchWord = newSearchInput;
      this.currentPage = 1;
      this.getDiaks();
    },
  },
  mounted() {
    this.getDiaks();
    this.getOsztalyok();
  },
  methods: {
    async getDiaks() {
      let url = `${this.urlApi}/diaks`;
      if (this.searchWord) {
        url = `${this.urlApi}/queryDiakKeres/${this.searchWord}`;
      }
      const headers = {
        Accept: "application/json",
      };
      const response = await axios.get(url, headers);
      this.rows = response.data.data;
    },

    async getOsztalyok() {
      let url = `${this.urlApi}/osztalies`;
      const headers = {
        Accept: "application/json",
      };
      const response = await axios.get(url, headers);
      this.osztalyok = response.data.data;
    },

    changePage(pageNumber) {
      this.currentPage = pageNumber;
      this.getDiaks();
    },

    getClassName(osztalyId) {
      const osztaly = this.osztalyok.find(
        (osztaly) => osztaly.id === osztalyId
      );
      return osztaly ? osztaly.osztalyNev : "Nincs";
    },

    formatOsztondij(ossz) {
      return (
        Math.round(ossz)
          .toString()
          .replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " Ft"
      );
    },

    getEletkor(szuletett) {
      const current = new Date();
      const szulDate = new Date(szuletett);
      const ageInMilliseconds = current - szulDate;
      const ageInYears = ageInMilliseconds / (1000 * 60 * 60 * 24 * 365.25);
      return Math.floor(ageInYears);
    },

    goToPage(page) {
      this.currentPage = page;
    },
  },
};
</script>

<style scoped>
.eletkor {
  color: #58c2ff;
  font-weight: bold;
}
</style>