<template>
  <div class="container">
    <div class="row my-5">
      <div class="col-md-6 mx-auto">
        <div class="card">
          <h5 class="card-header text-center">Regisztráció</h5>
          <div class="card-body">
            <form @submit.prevent="userRegister">
              <div class="form-group mb-3">
                <input
                  type="text"
                  v-model="user.name"
                  placeholder="Name*"
                  class="form-control"
                  required
                />
              </div>
              <div class="form-group mb-3">
                <input
                  type="email"
                  v-model="user.email"
                  placeholder="Email*"
                  class="form-control"
                  required
                />
              </div>
              <div class="form-group mb-3">
                <input
                  type="password"
                  v-model="user.password"
                  placeholder="Jelszó*"
                  class="form-control"
                  required
                />
              </div>
              <div class="form-group mb-3">
                <input
                  type="password"
                  v-model="user.passwordConfirmation"
                  placeholder="Jelszó újra*"
                  class="form-control"
                  required
                />
              </div>

              <div class="form-group mb-3">
                <div class="d-flex align-items-center">
                  <button
                    type="submit"
                    class="btn btn-primary me-4"
                    :disabled="
                      isPasswordsMismatch || user.password.length === 0
                    "
                  >
                    Register
                  </button>
                  <button
                    type="button"
                    class="btn btn-secondary me-4"
                    @click="onClickMegsem()"
                  >
                    Mégsem
                  </button>

                  <div
                    class="spinner-border m-0 p-0"
                    role="status"
                    v-if="errorMessage === '...'"
                  >
                    <span class="visually-hidden m-0">Loading...</span>
                  </div>
                  <span v-if="errorMessage !== '...'">{{ errorMessage }}</span>
                </div>
              </div>
            </form>
            <div
              v-if="isPasswordsMismatch && user.passwordConfirmation.length > 0"
              class="alert alert-danger mt-3"
            >
              A jelszó nem egyezik!
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from "../../stores/useAuthStore.js";
import { BASE_URL } from "../../helpers/baseUrls";
import axios from "axios";

export default {
  data() {
    return {
      urlApi: BASE_URL,
      user: {
        name: "Iskola",
        email: "iskolateszt@gmail.com",
        password: "Iskola24",
        passwordConfirmation: "Iskola24",
      },
      store: useAuthStore(),
      errorMessage: null,
    };
  },
  computed: {
    isPasswordsMismatch() {
      return this.user.password !== this.user.passwordConfirmation;
    },
  },
  methods: {
    async userRegister() {
      this.errorMessage = "...";
      const url = `${this.urlApi}/users`;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
      };

      try {
        const response = await axios.post(url, this.user, { headers });
        if (response.data.message === "ok") {
          this.errorMessage = "Sikeres regisztráció!";
          setTimeout(() => {
            this.$router.push("/login");
          }, 1000);
        } else {
          this.errorMessage = `${response.data.message}`;
        }
      } catch (error) {
        if (error.response && error.response.data) {
          this.errorMessage = `${response.data.message}`;
        } else {
          this.errorMessage = "Sikertelen regisztráció! Kapcsolódási hiba.";
        }
        console.log(error);

        this.store.clearStoredData();
      }
    },
    onClickMegsem() {
      this.$router.push("/");
    },
  },
};
</script>

<style scoped>
</style>
