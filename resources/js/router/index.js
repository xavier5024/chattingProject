import Vue from 'vue';
import VueRouter from 'vue-router';

//member
import DashboardView from '../views/member/Dashboard';
import ChattingView from '../views/member/Chatting';

import memberContainer from '../components/member/Container';

// Admin
import LoginView from '../views/Login';

Vue.use(VueRouter);

const requireAuth = () => (to, from, next) => {
  if (userAuth.auth) {
    next();
  } else {
    next('/login');
  }
}

const loginPageCustomAuth = () => (to, from, next) => {
  if (userAuth.auth) {
    next();
  } else {
    next('/login');
  }
}

const routes = [
  {
    path:'/',
    redirect: '/member/dashboard'
  },
  {
    path: '/member',
    redirect: '/member/dashboard',
    name: 'memberHome',
    component: memberContainer,
    beforeEnter: requireAuth(),
    children: [
            {
                path: '/member/dashboard',
                name: 'Dashboard',
                component: DashboardView
            },
            {
                path: '/member/chatting',
                name: 'Chatting',
                component: ChattingView
            }            
        ]
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView
  },
  { /* 404 error 방지 */
    path: '*',
    redirect: '/'
  }
];

export const router = new VueRouter({
  mode: 'hash', // https://router.vuejs.org/api/#mode
  linkActiveClass: 'active',
  base: process.env.BASE_URL,
  routes
});
