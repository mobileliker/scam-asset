/**
 * 登录页面
 * @version: 2.0.2
 * @author : wuzhihui
 * @date: 2017/12/14
 * @description:
 * (1) 基本功能；
 * (2) 完善登录页面的提示；(2017/12/14)
 */

<template>
    <el-col :lg="8" :offset="8" id="login">
        <el-card :body-style="{ padding: '10px'}">
            <error-component v-if="Object.getOwnPropertyNames(errors).length > 1" :berrors="errors"></error-component>
            <el-form :model="login" ref="modelForm" :rules="rule1">
                <h3>华南农业博物馆资产管理系统</h3>
                <el-form-item label="邮箱" prop="email">
                    <el-input v-model="login.email" placeholder="email"></el-input>
                </el-form-item>
                <el-form-item label="密码" prop="password">
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
    h3 {
        text-align: center;
    }

    #login {
        margin-top: 200px;
    }
</style>
<script>

    import error from '../layouts/Error.vue'

    export default {
        components: {
            'error-component': error
        },
        data() {
            return {
                errors: {},
                login: {
                    email: '',
                    password: '',
                    remember: '',
                },
                rule1: {
                    email: [
                        {required: true, message: '请输入邮箱', trigger: 'blur'},
                        {type: 'email', message: '请输入正确的邮箱地址', trigger: 'blur,change'},
                    ],
                    password: [
                        {min: 6, max: 20, message: '请输入6至20位的用户密码', trigger: 'blur,change'}
                    ]
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
        methods: {
            submit() {

                this.$refs['modelForm'].validate((valid) => {
                    if (valid) {
                        //console.log(this.login);
                        axios.post('/login', this.login)
                            .then(response => {
                                //console.log(response);
                                //this.$router.push('/');
                                window.location.href = "/";
                            })
                            .catch(error => {
                                //console.log(error);
                                if (error.response.status == 422) {
                                    this.$message.error('邮箱或者密码错误');
                                }
                            });
                    }
                });
            }
        }
    }
</script>