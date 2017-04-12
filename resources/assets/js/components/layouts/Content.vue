<template>
    <el-row>
        <el-col :lg="24">
            <header-component id="header"></header-component>
        </el-col>
        <el-col :lg="3">
            <menu-component id="left-nav"></menu-component>
        </el-col>
        <el-col class="content" :lg="21">
            <slot>只有在没有要分发的内容时才会显示。</slot>
        </el-col>
        <!--<el-col :lg="24" class="navbar-fixed-bottom">
            <footer-component id="footer"></footer-component>
        </el-col>-->
    </el-row>
</template>

<style scope>
    .content {
        padding : 10px;
    }
</style>

<script>
    import header from './Header.vue'
    import menu from './Menu.vue'
    import footer from './Footer.vue'
    export default {
        components : {
            'header-component' :  header,
            'menu-component' : menu,
            'footer-component' : footer
        }
    }
</script>