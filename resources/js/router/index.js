import Vue from 'vue';
import VueRouter from 'vue-router';

//member
import DashboardView from '../views/member/Dashboard';
import ChattingView from '../views/member/Chatting';
import MemberListView from '../views/member/MemberList';
import MemberRegisterView from '../views/member/MemberRegister';

import memberContainer from '../components/member/Container';

// Admin
import LoginView from '../views/Login';
import memberJoinView from '../views/Join';

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
            },
            {
              path: '/member/memberList',
              name: 'memberList',
              component: MemberListView
            },
            {
              path: '/member/memberRegister',
              name: 'memberRegister',
              component: MemberRegisterView
            }     
        ]
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView
  },
  {
    path: '/join',
    name: 'memberJoin',
    component: memberJoinView
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
