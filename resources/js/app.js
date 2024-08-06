import './bootstrap';
import Vue from 'vue';
import VueRouter from 'vue-router';
import BookListing from './components/BookListing.vue';
import EditBook from './components/EditBook.vue';

Vue.use(VueRouter);

const routes = [
  { path: '/', name: 'bookListing', component: BookListing },
  { path: '/edit', name: 'editBook', component: EditBook, props: true }
];

const router = new VueRouter({
  mode: 'history',
  routes
});

// Create and mount the root instance
const app = new Vue({
  router,
  render: h => h('router-view')
}).$mount('#app');
