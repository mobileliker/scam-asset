/**
 * 前端路由表
 * @version: 2.0
 * @author: wuzhihui
 * @date: 2017/7/11
 * @description:
 * （1）添加农具管理的路由；（2017/7/11）
 */

import Vue from 'vue'
import Router from 'vue-router'
import VueI18n from 'vue-i18n'

Vue.use(Router)
Vue.use(VueI18n)

import Login from '../components/Auth/Login'
import Index from '../components/Index'
import AssetIndex from '../components/asset/Index'
import AssetCreate from '../components/asset/Create'
import AssetEdit from '../components/asset/Edit'
import UserIndex from '../components/user/Index'
import AlogIndex from '../components/alog/Index'
import AuthIndex from '../components/passport/Index'
import CollectionFarmIndex from '../components/collection/farm/Index'
import CollectionFarmCreate from '../components/collection/farm/Create'
import CollectionFarmShow from '../components/collection/farm/Show'


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
            name: 'AssetEdit',
            component: AssetEdit
        },
        {
            path: '/user',
            name: 'UserIndex',
            component: UserIndex
        },
        {
            path: '/alog',
            name: 'AlogIndex',
            component: AlogIndex
        },
        {
            path: '/auth',
            name: 'AuthIndex',
            component: AuthIndex
        },
        {
            path: '/collection/farm',
            name: 'CollectionFarmIndex',
            component: CollectionFarmIndex
        },
        {
            path: '/collection/farm/create',
            name: 'CollectionFarmCreate',
            component: CollectionFarmCreate
        },
        {
            path: '/collection/farm/:id/edit',
            name: 'CollectionFarmEdit',
            component: CollectionFarmCreate
        },
        {
            path : '/collection/farm/:id',
            name : 'CollectionFarmShow',
            component : CollectionFarmShow
        }
    ]
})
