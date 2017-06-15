<template>
    <el-menu mode="horizontal"  @select="handleSelect">
        <el-menu-item index="1">{{$t('app.name')}}</el-menu-item>
        <el-menu-item index="2">{{$t('common.index')}}</el-menu-item>
        <el-submenu index="3" class="pull-right">
            <template slot="title">{{name}}</template>
            <el-menu-item index="3-1">设置</el-menu-item>
            <el-menu-item index="3-2">登出</el-menu-item>
        </el-submenu>
    </el-menu>
</template>
<style>
</style>
<script>
    export default {
        components : {
        },
        data() {
            return {
                name : ''
            }
        },
        mounted() {
            //console.log('good');
            axios.get('/auth/info')
                .then(response => {
                    //console.log(response);
                    this.name = response.data.name;
                })
                .catch(error => {
                    //console.log(error);
                    this.$router.push('/login');
            });
        },
        methods : {
            handleSelect(key, keyPath) {
                console.log(key, keyPath);
                //if(key == "1") this.$router.push('/');
                if(key == "2") this.$router.push('/');
                else if(key == "3-2"){
                    //console.log('good');
                    axios.post('/logout')
                        .then(response => {
                            console.log(response);
                            //this.$router.push('/login');
                            window.location.href="/";
                        })
                        .catch(error => {
                            console.log(error);
                        });
                }
            }
        }
    }
</script>