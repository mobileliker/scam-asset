<template>
    <el-col :lg="8" :offset="8" id="login">
        <el-card :body-style="{ padding: '10px'}">
        <el-form :model="login">
            <h3>华南农业博物馆资产管理系统</h3>
            <el-form-item label="邮箱">
                <el-input v-model="login.email" placeholder="email"></el-input>
            </el-form-item>
            <el-form-item label="密码">
                <el-input v-model="login.password" type="password" placeholder="password"></el-input>
            </el-form-item>
            <el-form-item label="">
                <el-checkbox v-model="login.remember">记住密码</el-checkbox>
            </el-form-item>
            <el-button type="primary" @click="submit">登录</el-button>
        </el-form>
        </el-card>
    </el-col>

</template>
<style scope>
    h3{
        text-align : center;
    }
    #login {
        margin-top: 200px;
    }
</style>
<script>

export default {
    data() {
        return {
            login : {
                email: '',
                 password: '',
                 remember: '',
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
                    //this.$router.push('/');
                    window.location.href="/";
                })
                .catch(error => {
                    //console.log(error);
                });

        }
    }
}
</script>