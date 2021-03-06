/**
 * 前端路由表
 * @version: 2.0
 * @author: wuzhihui
 * @date: 2017/7/11
 * @description:
 * （1）添加农具管理的路由；（2017/7/11）
 * （2）添加岩石管理的路由；（2017/10/18）
 * （3）添加动物管理的路由；（2017/11/30）
 *
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/3/27
 * @description :
 * (1)添加林业资源管理的路由；（2018/3/27）
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
import CollectionRockIndex from '../components/collection/rock/Index'
import CollectionRockCreate from '../components/collection/rock/Create'
import CollectionRockShow from '../components/collection/rock/Show'
import CollectionPlantIndex from '../components/collection/plant/Index'
import CollectionPlantCreate from '../components/collection/plant/Create'
import CollectionPlantShow from '../components/collection/plant/Show'
import CollectionSoilIndex from '../components/collection/soil/Index'
import CollectionSoilCreate from '../components/collection/soil/Create'
import CollectionSoilShow from '../components/collection/soil/Show'
import CollectionAnimalIndex from '../components/collection/animal/Index'
import CollectionAnimalCreate from '../components/collection/animal/Create'
import CollectionAnimalShow from '../components/collection/animal/Show'
import AttachmentIndex from '../components/attachment/Index'
import CollectionForestryIndex from '../components/collection/forestry/Index'
import CollectionForestryCreate from '../components/collection/forestry/Create'
import CollectionForestryShow from '../components/collection/forestry/Show'


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
        },
        {
            path : '/collection/rock',
            name : 'CollectionRockIndex',
            component : CollectionRockIndex
        },
        {
            path : '/collection/rock/create',
            name : 'CollectionRockCreate',
            component : CollectionRockCreate
        },
        {
            path : '/collection/rock/:id/edit',
            name : 'CollectionRockEdit',
            component : CollectionRockCreate
        },
        {
            path : '/collection/rock/:id',
            name : 'CollectionRockShow',
            component : CollectionRockShow
        },
        {
            path : '/collection/plant',
            name : 'CollectionPlantIndex',
            component : CollectionPlantIndex
        },
        {
            path : '/collection/plant/create',
            name : 'CollectionPlantCreate',
            component : CollectionPlantCreate
        },
        {
            path : '/collection/plant/:id/edit',
            name : 'CollectionPlantEdit',
            component : CollectionPlantCreate
        },
        {
            path : '/collection/plant/:id',
            name : 'CollectionPlantIndex',
            component : CollectionPlantShow
        },
        {
          path : '/collection/soil',
          name : 'CollectionSoilIndex',
          component : CollectionSoilIndex
        },
        {
          path : '/collection/soil/create',
          name : 'CollectionSoilCreate',
          component : CollectionSoilCreate
        },
        {
          path : '/collection/soil/:id/edit',
          name : 'CollectionSoilEdit',
          component : CollectionSoilCreate
        },
        {
          path : '/collection/soil/:id',
          name : 'CollectionSoilShow',
          component : CollectionSoilShow
        },
        {
          path : '/collection/animal',
          name : 'CollectionAnimalIndex',
          component : CollectionAnimalIndex
        },
        {
          path : '/collection/animal/create',
          name : 'CollectionAnimalCreate',
          component : CollectionAnimalCreate
        },
        {
          path : '/collection/animal/:id/edit',
          name : 'CollectionAnimalEdit',
          component : CollectionAnimalCreate
        },
        {
          path : '/collection/animal/:id',
          name : 'CollectionAnimalShow',
          component : CollectionAnimalShow
        },
        {
            path : '/system/attachment',
            name : 'AttachmentIndex',
            component : AttachmentIndex
        },
        {
            path : '/collection/forestry',
            name : 'CollectionForestryIndex',
            component : CollectionForestryIndex
        },
        {
            path : '/collection/forestry/create',
            name : 'CollectionForestryCreate',
            component : CollectionForestryCreate
        },
        {
            path : '/collection/forestry/:id/edit',
            name : 'CollectionForestryEdit',
            component : CollectionForestryCreate
        },
        {
            path : '/collection/forestry/:id',
            name : 'CollectionForestryShow',
            component : CollectionForestryShow
        }
    ]
})
