<template>
  <div>
    <h1 class="text-center my-4">Diákok</h1>
    <ErrorMessage
      :errorMessages="errorMessages"
      @close="onClickCloseErrorMessage"
    />
    <div class="container">
      <div class="row d-flex justify-content-center">
        <div
          class="spinner-border m-0 p-0 text-center"
          role="status"
          v-if="items.length == 0"
        >
          <span class="visually-hidden m-0">Loading...</span>
        </div>

        <div class="col-12 col-lg-12 tabla-container" v-if="items.length > 0">
          <table
            class="table table-bordered table-hover table-striped shadow-sm rounded"
          >
            <thead class="table-dark">
              <tr>
                <th v-if="debug">#</th>
                <th>osztályNév</th>
                <th>Név</th>
                <th>Nem</th>
                <th>Születési dátum</th>
                <th>Helység</th>
                <th>Ösztöndíj</th>
                <th>Átlag</th>
                <th class="text-center">Műveletek</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="item in paginatedCollections"
                :key="item.id"
                @click="onClickTr(item.id)"
                :class="{
                  updating: loading,
                  active: item.id === selectedRowId,
                }"
              >
                <td data-label="ID" v-if="debug">{{ item.id }}</td>
                <td data-label="OsztályNév">
                  <span>{{ getOsztalyNevById(item.osztalyId) }}</span>
                </td>
                <td data-label="Név">
                  <span
                    >{{ item.nev }}
                    <span
                      class="spinner-border text-primary spinner-border-sm m-0 p-0"
                      role="status"
                      v-if="item.id === selectedRowId && loading"
                    >
                      <span class="visually-hidden m-0">Loading...</span>
                    </span>
                  </span>
                </td>
                <td data-label="Nem">
                  <span>{{ item.neme ?? false ? "Férfi" : "Nő" }}</span>
                </td>
                <td data-label="Születési dátum">
                  <span>{{ item.szuletett }}</span>
                </td>
                <td data-label="Helység">
                  <span>{{ item.helyseg }}</span>
                </td>
                <td data-label="Ösztöndíj">
                  <span>{{ formatOsztondij(item.osztondij) }}</span>
                </td>
                <td data-label="Átlag">
                  <span>{{ item.atlag }}</span>
                </td>
                <td class="text-nowrap text-center">
                  <OperationsCrud
                    @onClickDeleteButton="onClickDeleteButton"
                    @onClickUpdate="onClickUpdate"
                    @onClickCreate="onClickCreate"
                    :data="item"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <Modal
          :title="title"
          :yes="yes"
          :no="no"
          :size="size"
          @yesEvent="yesEventHandler"
        >
          <div v-if="state == 'Delete'">
            {{ messageYesNo }}
          </div>

          <DiakForm
            v-if="state == 'Create' || state == 'Update'"
            :itemForm="item"
            :debug="debug"
            @saveItem="saveItemHandler"
          />
        </Modal>
      </div>
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
  </div>
</template>
    
<script>
class Item {
  constructor(
    id = null,
    osztalyId = null,
    nev = null,
    neme = null,
    szuletett = null,
    helyseg = null,
    osztondij = null,
    atlag = null
  ) {
    this.id = id;
    this.osztalyId = osztalyId;
    this.nev = nev;
    this.neme = neme;
    this.szuletett = szuletett;
    this.helyseg = helyseg;
    this.osztondij = osztondij;
    this.atlag = atlag;
  }
}
import { BASE_URL } from "../helpers/baseUrls";
import { DEBUG } from "../helpers/debug";
import ErrorMessage from "@/components/ErrorMessage.vue";
import { useAuthStore } from "@/stores/useAuthStore.js";
import DiakForm from "@/components/DiakForm.vue";
import OperationsCrud from "@/components/OperationsCrud.vue";
import axios from "axios";
import * as bootstrap from "bootstrap";
export default {
  components: { DiakForm, OperationsCrud, ErrorMessage },
  data() {
    return {
      urlApi: `${BASE_URL}/diaks`,
      urlApiSport: `${BASE_URL}`,
      stateAuth: useAuthStore(),
      items: [],
      loading: false,
      modal: null,
      currentPage: 1,
      itemsPerPage: 10,
      item: new Item(),
      selectedRowId: null,
      messageYesNo: null,
      state: "Read", //CRUD: Create, Read, Update, Delete
      title: null,
      yes: null,
      no: null,
      size: null,
      errorMessages: null,
      osztalyok: [],
      debug: DEBUG,
    };
  },
  mounted() {
    this.getCollections();
    this.getOsztalyok();
    this.modal = new bootstrap.Modal("#modal", {
      keyboard: false,
    });
  },
  computed: {
    paginatedCollections() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.items.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.items.length / this.itemsPerPage);
    },
  },
  methods: {
    async getCollections() {
      const url = this.urlApi;
      const headers = {
        Accept: "application/json",
      };
      try {
        const response = await axios.get(url, headers);
        this.items = response.data.data;
        this.loading = false;
      } catch (error) {
        this.errorMessages = "Szerver hiba";
      }
    },

    async deleteItemById() {
      const id = this.selectedRowId;
      const token = this.stateAuth.token;

      const url = `${this.urlApi}/${id}`;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      };

      try {
        const response = await axios.delete(url, { headers });
        // this.items = this.items.filter((sport) => sport.id !== id);
        this.getCollections();
      } catch (error) {
        this.errorMessages =
          "A diák nem törölhető";
      }
    },

    async createItem() {
      const token = this.stateAuth.token;
      const url = this.urlApi;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      };

      const data = {
        osztalyId: this.item.osztalyId,
        nev: this.item.nev,
        neme: this.item.neme,
        szuletett: this.item.szuletett,
        helyseg: this.item.helyseg,
        osztondij: this.item.osztondij,
        atlag: this.item.atlag
      };
      try {
        const response = await axios.post(url, data, { headers });
        // this.items.push(response.data.data);
        this.getCollections();
      } catch (error) {
        this.errorMessages = "A bővítés nem sikerült.";
      }
      this.state = "Read";
    },

    async getOsztalyok() {
      const url = `${this.urlApiSport}/queryOsztalynevIdvel`;
      const headers = {
        Accept: "application/json",
      };
      const response = await axios.get(url, { headers });      
      this.osztalyok = response.data.data;
    },

    async updateItem() {
      this.loading = true;
      const id = this.selectedRowId;
      const url = `${this.urlApi}/${id}`;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${this.stateAuth.token}`,
      };

      const data = {
        osztalyId: this.item.osztalyId,
        nev: this.item.nev,
        neme: this.item.neme,
        szuletett: this.item.szuletett,
        helyseg: this.item.helyseg,
        osztondij: this.item.osztondij,
        atlag: this.item.atlag
      };
      try {
        const response = await axios.patch(url, data, { headers });
        this.getCollections();
      } catch (error) {
        this.errorMessages = "A módosítás nem sikerült.";
      }
      this.state = "Read";
    },

    yesEventHandler() {
      if (this.state == "Delete") {
        this.deleteItemById();
        this.goToPage(1);
      }
    },

    onClickDeleteButton(item) {
      this.state = "Delete";
      this.title = "Törlés";
      this.messageYesNo = `Valóban törölni akarod a(z) ${item.nev} nevű diákot?`;
      this.yes = "Igen";
      this.no = "Nem";
      this.size = null;
    },

    onClickUpdate(item) {
      this.state = "Update";
      this.title = "Diák módosítása";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.item = { ...item };
    },

    onClickCreate() {
      this.title = "Új diák bevitele";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.state = "Create";
      this.item = new Item();
    },

    onClickTr(id) {
      this.selectedRowId = id;
    },

    onClickCloseErrorMessage() {
      this.errorMessages = null;
      this.loading = false;
      this.state = "Read";
    },

    getOsztalyNevById(id) {
      const osztaly = this.osztalyok.find((osztaly) => osztaly.id === id);
      return osztaly ? osztaly.osztalyNev : id;
    },

    formatOsztondij(ossz) {
      return (
        Math.round(ossz)
          .toString()
          .replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " Ft"
      );
    },

    saveItemHandler() {
      if (this.state === "Update") {
        this.updateItem();
      } else if (this.state === "Create") {
        this.createItem();
      }

      this.modal.hide();
    },

    goToPage(page) {
      this.currentPage = page;
    },
  },
};
</script>
    
<style scoped>
</style>