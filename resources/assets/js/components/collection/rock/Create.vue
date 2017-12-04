/**
* @version 2.0
* @author: wuzhihui
* @date: 2017/10/18
* @description:
* （1）基本功能；（2017/10/18）
* (2)注释掉keeper_id的integer的验证；（2017/12/4）
*/

<template>
    <content-component>
        <el-col :lg="24">
            <el-breadcrumb id="breadcrumb" separtor="/">
                <el-breadcrumb-item :to="{path : '/'}">首页</el-breadcrumb-item>
                <!--<el-breadcrumb-item :to="{ path: '/collection' }"></el-breadcrumb-item>-->
                <el-breadcrumb-item :to="{path : '/collection/rock'}">岩石管理</el-breadcrumb-item>
                <el-breadcrumb-item>添加或编辑</el-breadcrumb-item>
            </el-breadcrumb>
        </el-col>
        <el-col :lg="24">
            <error-component v-if="Object.getOwnPropertyNames(errors).length > 1" :berrors="errors"></error-component>
            <el-tabs v-model="view.tabName" type="card" v-loading.body="view.saveLoading">
                <el-tab-pane label="基本信息" name="basic">
                    <el-form ref="rock" :model="rock" label-width="80px" :rules="rule1">
                        <el-col :lg="12">
                            <el-form-item label="入库日期" prop="input_date_obj">
                                <el-date-picker v-model="view.input_date_obj" type="date" placeholder="选择日期" @change="handleInputDateChange"></el-date-picker>
                            </el-form-item>
                            <el-form-item label="名称" prop="name">
                                <el-input v-model="rock.name" placeholder="名称"></el-input>
                            </el-form-item>
                            <el-form-item label="分类" prop="classification">
                                <el-input v-model="rock.classification" placeholder="分类"></el-input>
                            </el-form-item>
                            <el-form-item label="产地" prop="origin">
                                <el-input v-model="rock.origin" placeholder="产地"></el-input>
                            </el-form-item>
                            <el-form-item label="保管人" prop="keeper_id">
                                <el-select v-model="rock.keeper_id" placeholder="保管人">
                                    <el-option v-for="item in view.keeper_id.options" :label="item.label" :value="item.value"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="描述" prop="description">
                                <el-input type="textarea" :rows="10" v-model="rock.description" placeholder="描述"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :lg="12">
                            <el-form-item label="类别" prop="category_id">
                                <el-input v-model="rock.category_id" placeholder="类别"></el-input>
                            </el-form-item>
                            <el-form-item label="英文名称" prop="ename">
                                <el-input v-model="rock.ename" placeholder="英文名称"></el-input>
                            </el-form-item>
                            <el-form-item label="特征" prop="feature">
                                <el-input v-model="rock.feature" placeholder="特征"></el-input>
                            </el-form-item>
                            <el-form-item label="编号" prop="serial">
                                <el-input v-model="rock.serial" placeholder="编号"></el-input>
                            </el-form-item>
                            <el-form-item label="是否固定资产" prop="asset_id">
                                <el-input v-model="rock.asset_id" placeholder="输入固定资产编号"></el-input>
                            </el-form-item>
                            <el-form-item label="备注" prop="memo">
                                <el-input type="textarea" v-model="rock.memo" :rows="10" placeholder="备注"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :lg="24">
                            <el-button type="primary" @click="commit(0)" :loading="view.saveLoading">保存</el-button>
                            <el-button type="primary" @click="commit(1)" :loading="view.saveLoading">保存并继续</el-button>
                        </el-col>
                    </el-form>
                </el-tab-pane>
            </el-tabs>
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
</style>

<script>
    import content from '../../layouts/Content.vue'
    import error from '../../layouts/Error.vue'

    export default {
        components: {
            'content-component': content,
            'error-component': error
        },
        data() {
            return {
                errors: {},
                view : {
                    tabName : 'basic',
                    fullscreenLoading: false,
                    saveLoading : false,
                    input_date_obj : '',
                    keeper_id : {
                        options : []
                    }
                },
                rock : {
                    id : '',
                    category_id : '',
                    name : '',
                    ename : '',
                    input_date : '',
                    input_date_obj : '',
                    serial : '',
                    classification : '',
                    feature : '',
                    description : '',
                    keeper_id : '',
                    asset_id : '',
                    memo : ''
                },
                rule1 : {
                    name : [
                        {required : true, message : '请输入名称', trigger : 'blur'},
                        {max : 255, message : '名称不能超过255个字', trigger : 'blur'}
                    ],
                    ename : [
                        {max : 255, message : '名称不能超过255个字', trigger : 'blur'}
                    ],
                    serial : [
                        {required : true, message : '请输入编号', trigger : 'blur'},
                        {max : 255, message : '编号不能超过255个字', trigger : 'blur'}
                    ],
                    classification : [
                        {max : 255, message : '分类不能超过255个字', trigger : 'blur'}
                    ],
                    feature : [
                        {max : 255, message : '分类不能超过255个字', trigger : 'blur'}
                    ],
                    description : [
                        {max : 2000, message : '描述不能超过255个字', trigger : 'blur'}
                    ],
                    memo : [
                        {max : 2000, message : '描述不能超过255个字', trigger : 'blur'}
                    ],
                    //keeper_id : [
                    //    {required : true, type: 'integer', message : '请选择保管人', trigger : 'blur'}
                    //]
                }
            }
        },
        mounted () {
            axios.get('/api/user/all')
                .then(response => {
                    this.view.keeper_id.options = response.data;

                    if(this.$route.name == 'CollectionRockEdit') {
                        axios.get('/api/collection/rock/' + this.$route.params.id + '/edit')
                            .then(response => {
                                this.rock = response.data;
                                this.view.input_date_obj = response.data.input_date;
                            }).catch(error => {
                                if(error.response.status == 404) {
                                    this.$message.error('欲编辑的岩石不存在');
                                    this.$router.push('/collction/rock');
                                } else {
                                    this.$message.error('获取岩石信息错误');
                                }
                            });
                    }

                }).catch(error => {
                    this.$message.error('获取所有用户失败');
                });
        },
        methods : {

            //处理入库日期选择事件
            handleInputDateChange(value) {
                this.rock.input_date = value;
            },

            //保存
            commit(flag) {
                //console.log('commit');
                this.$refs['rock'].validate((valid) => {
                    if(valid) {
                        //console.log('validate success');
                        this.view.saveLoading = true;
                        if(this.$route.name == 'CollectionRockEdit'){
                            //console.log('rockEdit');
                            axios.put('/api/collection/rock/' + this.$route.params.id, this.rock)
                                .then(response => {
                                    this.$message('保存成功');
                                    this.$router.push('/collection/rock');
                                }).catch(error => {
                                    if(error.response.status == 422) {
                                        this.errors = error.rsponse.data;
                                    }else if(error.response.status == 404){
                                        this.$message.error('欲保存的岩石不存在');
                                    }else{
                                        this.$message.error('保存岩石失败');
                                    }
                                });
                        } else {
                            axios.post('/api/collection/rock', this.rock)
                                .then(response => {
                                    this.$message('保存成功');
                                    if(!flag) {
                                        this.$router.push('/collection/rock');
                                    }
                                }).catch(error => {
                                    if(error.response.status == 422) {
                                        this.errors = error.response.data;
                                    } else {
                                        this.$message.error(error.response);
                                    }
                                });
                        }
                        this.view.saveLoading = false;
                    } else {
                        console.log('validate fail');
                    }
                });
            }
        }
    }
</script>