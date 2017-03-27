<template>
    <el-menu :router="true" :default-active="$route.path" :unique-opened="true">
        <el-menu-item index="/">首页</el-menu-item>
            <el-submenu index="2">
                <template slot="title">固定资产管理</template>
                <el-menu-item index="/asset">资产管理</el-menu-item>
                <el-menu-item index="/invoice">单据管理</el-menu-item>
            </el-submenu>
            <el-submenu index="3">
                <template slot="title">系统管理</template>
                <el-menu-item index="/info">配置管理</el-menu-item>
                <el-menu-item index="/category">分类管理</el-menu-item>
                <el-menu-item index="/user">用户管理</el-menu-item>
                <el-menu-item index="/alog">操作日志</el-menu-item>
            </el-submenu>
    </el-menu>
</template>
<style></style>
<script>
    export default {
        components : {

        }
    }
</script>