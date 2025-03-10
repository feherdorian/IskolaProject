import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useAuthStore } from "@/stores/useAuthStore.js";

function checkIfNotLogged() {
  const storeAuth = useAuthStore();
  if (!storeAuth.user) {
    return "/login";
  }
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: { title: (route) => 'Főoldal' }
    },
    {
      path: '/kartyak/:pageNumber/:cardsPerPage',
      name: 'kartyak',
      component: () => import('../views/KartyakView.vue'),
      meta: { title: (route) => `Kártyák/${route.params.pageNumber}/${route.params.cardsPerPage}` }
    },
    {
      path: '/nevsor',
      name: 'nevsor',
      component: () => import('../views/Nevsor.vue'),
      meta: { title: (route) => 'Névsor' }
    },
    {
      path: '/diakok',
      name: 'diakok',
      component: () => import('../views/Diakok.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Diákok' }
    },
    {
      path: '/sportok',
      name: 'sportok',
      component: () => import('../views/Sportok.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Sportok' }
    },
    {
      path: '/osztalyok',
      name: 'osztalyok',
      component: () => import('../views/Osztalyok.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Osztályok' }
    },
    {
      path: '/diakkeres',
      name: 'diakkeres',
      component: () => import('../views/DiakKeres.vue'),
      meta: { title: (route) => 'Diák keres' }
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/components/Auth/Login.vue'),
      meta: { title: (route) => 'Login' }
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('@/components/Auth/Register.vue'),
      meta: { title: (route) => 'Register' }
    },
    {
      path: '/profile',
      name: 'profile',
      component: () => import('@/components/Auth/Profile.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Profile' }
    },
    {
      path: "/:pathMatch(.*)*",
      name: "NotFound",
      component: HomeView,
      meta: { title: (route) => 'FőOldal' }
    },
  ]
})

router.beforeEach((to, from, next) => {
  const title = to.meta.title;
  document.title = 'Iskola - ' + to.meta.title(to);
  next();
})

export default router
