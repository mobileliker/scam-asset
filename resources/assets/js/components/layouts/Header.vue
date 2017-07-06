<!--
@version: v2.0 完成后台菜单的功能
@author : wuzhihui
@date: 2017/6/23
@description:
* 添加了用户设置窗口
-->
<template>
    <div>
        <el-menu mode="horizontal"  @select="handleSelect">
            <el-menu-item index="1">{{$t('app.name')}}</el-menu-item>
            <el-menu-item index="2">{{$t('common.index')}}</el-menu-item>
            <el-submenu index="3" class="pull-right">
                <template slot="title">{{name}}</template>
                <el-menu-item index="3-1">设置</el-menu-item>
                <el-menu-item index="3-2">登出</el-menu-item>
            </el-submenu>
        </el-menu>
        <el-dialog title="用户设置" v-model="dialog.visible" size="small">
            <error-component v-if="Object.getOwnPropertyNames(dialog.errors).length > 1" :berrors="dialog.errors"></error-component>
            <el-form :model="dialog.model" ref="setting-dialog" :rules="dialog.rules">
                <el-form-item label="用户名" prop="sname">
                    <el-input v-model="dialog.model.sname" placeholder="用户名"></el-input>
                </el-form-item>
                <el-form-item label="密码" prop="password">
                    <el-input type="password" v-model="dialog.model.password" placeholder="密码"></el-input>
                </el-form-item>
                <el-form-item label="新密码" prop="spassword">
                    <el-input type="password" v-model="dialog.model.spassword" placeholder="新密码"></el-input>
                </el-form-item>
                <el-form-item label="确认密码" prop="spassword2">
                    <el-input type="password" v-model="dialog.model.spassword2" placeholder="确认密码"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer">
                <el-button @click="dialog.visible=false">取 消</el-button>
                <el-button type="primary" @click="settingSave">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>
<style>
</style>
<script>
    import error from './Error.vue'

    export default {
        components : {
            'error-component' : error
        },
        data() {
          var validatePass2 = (rule, value, callback) => {
            if (value !== this.dialog.model.spassword) {
              callback(new Error('两次输入密码不一致!'));
            } else {
              callback();
            }
          };
            return {
                name : '',
                dialog : {
                    visible : false,
                    model: {
                        sname : '',
                        password : '',
                        spassword : '',
                        spassword2 : ''
                    },
                    rules : {
                        sname : [
                            {required : true, message : '请输入用户名', trigger : 'blur'},
                            {max : 255, message : '用户名不能操作20个字', trigger : 'blur'}
                        ],
                        password : [
                            {required : true, message : '密码不能为空', 'trigger' : 'blur'},
                        ],
                        spassword : [
                            { min: 8, max: 20, message: '请输入8至20位的用户密码', trigger: 'blur,change' }
                        ],
                        spassword2 : [
                            {validator : validatePass2, trigger : 'blur'}
                        ]
                    },
                    errors : {},
                }
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
                //console.log(key, keyPath);
                //if(key == "1") this.$router.push('/');
                if(key == "2") this.$router.push('/');
                else if(key == "3-1") {
                    //console.log("3-1");
                    this.dialog.model.sname = this.name;
                    this.dialog.visible = true;
                }else if(key == "3-2"){
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
            },

            settingSave() {
                //console.log('settingSave');
                this.$refs['setting-dialog'].validate((valid) => {
                    if(valid) {
                        axios.put('/api/user/settings', this.dialog.model)
                            .then( response => {
                                //console.log('setting');
                                //console.log(response.data);
                                this.name = response.data.name;
                                this.dialog.visible = false;
                                this.$message({
                                    type: 'success',
                                    message: '修改成功'
                                });
                            })
                            .catch(error => {
                                //console.log(error);
                                if(error.response.status == 422){
                                    this.dialog.errors = error.response.data;
                                }
                        });
                    }//else{
                    //    console.log('valid error');
                    //}
                });
            }
        }
    }
</script>