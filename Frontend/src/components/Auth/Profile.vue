<template>
  <div class="container">
    <div class="row my-5">
      <div class="col-md-6 mx-auto">
        <div class="card">
          <h5 class="card-header text-center">Profil módosítás</h5>
          <div class="card-body">
            <form @submit.prevent="updateProfile">
              <div class="form-group mb-3">
                <label for="name">Name</label>
                <input
                  type="text"
                  id="name"
                  v-model="user.name"
                  placeholder="Name"
                  class="form-control"
                  :disabled="loading"
                />
              </div>
              <div class="form-group mb-3">
                <label for="email">Email</label>
                <input
                  type="email"
                  id="email"
                  v-model="user.email"
                  placeholder="Email"
                  class="form-control"
                  :disabled="loading"
                />
              </div>
              <!-- <div class="form-group mb-3">
                <label for="currentPassword">Jelenlegi jelszó</label>
                <input
                  type="password"
                  id="currentPassword"
                  v-model="user.currentPassword"
                  placeholder="Jelenlegi jelszó"
                  class="form-control"
                  :disabled="loading"
                />
              </div> -->
              <div class="form-group mb-3">
                <label for="newPassword">Új jelszó</label>
                <input
                  type="password"
                  id="newPassword"
                  v-model="user.newPassword"
                  placeholder="Új jelszó"
                  class="form-control"
                  :disabled="loading"
                />
              </div>

              <div class="d-flex align-items-center">
                <button
                  type="submit"
                  class="btn btn-primary me-4"
                  :disabled="loading"
                >
                  Frissítés
                </button>

                <div
                  class="spinner-border m-0 p-0"
                  role="status"
                  v-if="loading"
                >
                  <span class="visually-hidden m-0">Loading...</span>
                </div>

                <span v-if="errorMessage" class="text-danger">{{
                  errorMessage
                }}</span>
                <span v-if="successMessage" class="text-success">{{
                  successMessage
                }}</span>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from "../../stores/useAuthStore.js";
import axios from "axios";
import { BASE_URL } from "../../helpers/baseUrls";

export default {
  data() {
    return {
      urlApi: BASE_URL,
      user: {
        name: "",
        email: "",
        // currentPassword: "",
        newPassword: "",
      },
      id: null,
      loading: false,
      errorMessage: null,
      successMessage: null,
      stateAuth: useAuthStore(),
    };
  },
  mounted() {
    this.id = this.stateAuth.id;
    this.getAccountInfo();
  },
  methods: {
    async getAccountInfo() {
      this.loading = true;
      const url = `${this.urlApi}/users/${this.id}`;
      const token = this.stateAuth.token;

      const headers = {
        Accept: "application/json",
        Authorization: `Bearer ${token}`,
      };
      const response = await axios.get(url, { headers });
      this.user.name = this.stateAuth.user || "";
      this.user.email = response.data.data.email;
      this.loading = false;
    },

    async updateProfile() {
      this.loading = true;
      const url = `${this.urlApi}/users/${this.id}`;
      const token = this.stateAuth.token;

      const headers = {
        Accept: "application/json",
        Authorization: `Bearer ${token}`,
      };

      const data = {};

      if (this.user.name) {
        data.name = this.user.name;
      }
      if (this.user.email) {
        data.email = this.user.email;
      }
      if (this.user.newPassword) {
        data.password = this.user.newPassword;
      }

      try {
        const response = await axios.patch(url, data, { headers });
        if (response.data.message === "ok") {
          this.loading = false;
          this.stateAuth.user = this.user.name;
          this.successMessage = "Profil sikeresen módosítva!";
          this.errorMessage = null;
          setTimeout(() => {
            this.successMessage = null;
          }, 1000);
        } else {
          this.loading = false;
          this.errorMessage = response.data.message;
          this.successMessage = null;
          setTimeout(() => {
            this.errorMessage = null;
          }, 1000);
        }
      } catch (error) {
        this.loading = false;
        this.successMessage = null;
      }
    },
  },
};
</script>

<style scoped>
</style>
