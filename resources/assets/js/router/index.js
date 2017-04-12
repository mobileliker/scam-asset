import Vue from 'vue'
import Router from 'vue-router'
import VueI18n from 'vue-i18n'

Vue.use(Router)
Vue.use(VueI18n)

import Index from '../components/Index'
import AssetIndex from '../components/asset/Index'
import AssetCreate from '../components/asset/Create'
import AssetEdit from '../components/asset/Edit'
import Login from '../components/Auth/Login'


export default new Router({
  routes: [
    {
      path: '/',
      name: 'Index',
      component: Index
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/asset',
      name: 'AssetIndex',
      component: AssetIndex
    },
    {
      path: '/asset/create',
      name: 'AssetCreate',
      component: AssetCreate
    },
    {
      path: '/asset/:id/edit',
      name : 'AssetEdit',
      component : AssetEdit
    }
  ]
})
