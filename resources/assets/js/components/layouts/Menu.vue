/**
 * 菜单页面
 * @version : 2.0
 * @author : wuzhihui
 * @date : 2017/7/14
 * @description:
 * （1）注释掉多余的测试语句；（2017/7/14）
 */
<template>
    <el-menu :router="true" :default-active="$route.path" :unique-opened="true">
        <el-menu-item index="/">首页</el-menu-item>
        <el-submenu v-for="(item, index) in menu" :index="index">
            <template slot="title">{{item.display_name}}</template>
            <el-menu-item v-for="item2 in item.data" :index="item2.resource">{{item2.display_name}}</el-menu-item>
        </el-submenu>
    </el-menu>
</template>
<style></style>
<script>
    export default {
        components : {
        },
        data()
        {
            return {
                'menu' : [
                    {
                        'display_name' : '',
                        data : [
                            {
                                'display_name' : '',
                                'resource' : ''
                            }
                        ]
                    }
                ]
            }
        },
        mounted() {
            axios.get('/api/user/menu')
                .then(response =>{
                    //console.log(response.data);
                    this.menu = response.data;
                });
        }
    }
</script>