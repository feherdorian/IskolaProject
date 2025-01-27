<template>
  <div>
    <h1 class="text-center my-4">Sportok</h1>
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

        <div class="col-12 col-lg-10 tabla-container" v-if="items.length > 0">
          <table
            class="table table-bordered table-hover table-striped shadow-sm rounded"
          >
            <thead class="table-dark">
              <tr>
                <th v-if="debug">#</th>
                <th>Sport Név</th>
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
                <td data-label="Név">
                  {{ item.sportNev }}
                  <span
                    class="spinner-border text-primary spinner-border-sm m-0 p-0"
                    role="status"
                    v-if="item.id === selectedRowId && loading"
                  >
                    <span class="visually-hidden m-0">Loading...</span>
                  </span>
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

          <SportForm
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
  constructor(id = null, sportNev = null) {
    this.id = id;
    this.sportNev = sportNev;
  }
}
import { BASE_URL } from "../helpers/baseUrls";
import { DEBUG } from "../helpers/debug";
import ErrorMessage from "@/components/ErrorMessage.vue";
import { useAuthStore } from "@/stores/useAuthStore.js";
import SportForm from "@/components/SportForm.vue";
import OperationsCrud from "@/components/OperationsCrud.vue";
import axios from "axios";
import * as bootstrap from "bootstrap";
export default {
  components: { SportForm, OperationsCrud, ErrorMessage },
  data() {
    return {
      urlApi: `${BASE_URL}/sports`,
      stateAuth: useAuthStore(),
      items: [],
      loading: false,
      modal: null,
      currentPage: 1,
      itemsPerPage: 5,
      item: new Item(),
      selectedRowId: null,
      messageYesNo: null,
      state: "Read", //CRUD: Create, Read, Update, Delete
      title: null,
      yes: null,
      no: null,
      size: null,
      errorMessages: null,
      debug: DEBUG
    };
  },
  mounted() {
    this.getCollections();
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
          "A sport nem törölhető, mert már ilyet sportolnak a diákok.";
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
        sportNev: this.item.sportNev,
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
        sportNev: this.item.sportNev,
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
      this.messageYesNo = `Valóban törölni akarod a(z) ${item.sportNev} nevű sportot?`;
      this.yes = "Igen";
      this.no = "Nem";
      this.size = null;
    },

    onClickUpdate(item) {
      this.state = "Update";
      this.title = "Sport módosítása";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.item = { ...item };
    },

    onClickCreate() {
      this.title = "Új adat bevitele";
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