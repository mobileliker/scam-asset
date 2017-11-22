<template>
    <content-component>
        <el-col :lg="24">
            <el-breadcrumb id="breadcrumb" separtor="/">
                <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
                <!--<el-breadcrumb-item :to="{ path: '/collection' }"></el-breadcrumb-item>-->
                <el-breadcrumb-item>植物管理</el-breadcrumb-item>
            </el-breadcrumb>
        </el-col>
        <el-col :lg="2" class="pull-right">
            <router-link to="/collection/plant/create">
                <el-button type="success">添加</el-button>
            </router-link>
            <el-button type="success" @click="handleImport">导入</el-button>
        </el-col>
    </content-component>
</template>

<style scoped>
    #breadcrumb {
        font-size: 16px;
        padding: 8px 15px;
        margin-bottom: 22px;
        list-style: none;
        background-color: #f5f5f5;
        border-radius: 4px;
    }

    .list {
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>

<script>
    import content from '../../layouts/Content.vue'
    import error from '../../layouts/Error.vue'
    import szyImageDialog from '../../Basic/Dialog/SzyImageDialog.vue'

    export default {
        components: {
            'content-component': content,
            'error-component': error,
            'szy-image-dialog': szyImageDialog
        },
        data() {
            return {
                token: {'X-CSRF-TOKEN': window.Laravel.csrfToken},
                loading: true,
                fullscreenLoading: false,
            }
        }
    }
</script>