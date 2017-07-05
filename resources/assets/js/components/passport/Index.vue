/**
 * @version 2.0 Passport Auth页面
 * @author: wuzhihui
 * @date: 2017/7/4
 * description:
 *
 */

<template>
 <content-component id="content">
  <el-col :lg="24">
   <el-breadcrumb id="breadcrumb" separator="/">
    <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
    <el-breadcrumb-item :to="{ path: '/auth'}">授权管理</el-breadcrumb-item>
   </el-breadcrumb>
  </el-col>
  <el-col :lg="24">
   <passport-client-component></passport-client-component>
   <passport-authorized-client-component></passport-authorized-client-component>
   <passport-personal-access-tokens-component></passport-personal-access-tokens-component>
  </el-col>
 </content-component>
</template>

<style scoped>

</style>

<script>
 import content from '../layouts/Content.vue'
 import passportClient from './Clients.vue'
 import passportAuthorizedClient from './AuthorizedClients.vue'
 import passportPersonalAccessTokens from './PersonalAccessTokens.vue'

 export default {
  components : {
   'content-component' : content,
   'passport-client-component' : passportClient,
   'passport-authorized-client-component' : passportAuthorizedClient,
   'passport-personal-access-tokens-component' : passportPersonalAccessTokens
  },
  mounted() {
   axios.get('/api/user')
           .then( (response) => {
    console.log(response.data);
   });
  }
 }
</script>