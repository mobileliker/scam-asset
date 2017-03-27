<template>
    <el-form :model="login">
        <el-form-item label="邮箱">
            <el-input v-model="login.email" placeholder="email"></el-input>
        </el-form-item>
        <el-form-item label="密码">
            <el-input v-model="login.password" type="password" placeholder="password"></el-input>
        </el-form-item>
        <el-button>重置</el-button>
        <el-button type="primary" @click="submit">登录</el-button>
    </el-form>

</template>
<style scope></style>
<script>

export default {
    data() {
        return {
            login : {
                email: '',
                 password: ''
            }
        }
    },
    mounted() {
        axios.get('/auth/info')
            .then(response => {
                console.log(response);
               this.$router.push('/');
            })
            .catch(error => {
               console.log(error);
            });
    },
    methods : {
        submit () {
            //console.log(this.login);
            axios.post('/login', this.login)
                .then(response => {
                    //console.log(response);
                    this.$router.push('/');
                })
                .catch(error => {
                    //console.log(error);
                });

        }
    }
}
</script>