<template>
    <content-component>
        <el-col :lg="24">
            <el-breadcrumb id="breadcrumb" separtor="/">
                <el-breadcrumb-item :to="{path : '/'}">首页</el-breadcrumb-item>
                <!--<el-breadcrumb-item :to="{ path: '/collection' }"></el-breadcrumb-item>-->
                <el-breadcrumb-item :to="{path : '/collection/animal'}">动物管理</el-breadcrumb-item>
                <el-breadcrumb-item>添加或编辑</el-breadcrumb-item>
            </el-breadcrumb>
        </el-col>
        <el-col :lg="24">
            <error-component v-if="Object.getOwnPropertyNames(errors).length > 1" :berrors="errors"></error-component>
            <el-tabs v-model="view.tabName" type="card" v-loading.body="view.saveLoading">
                <el-tab-pane label="基本信息" name="basic">
                    <el-form ref="animal" :model="animal" label-width="80px" :rules="rule1">
                        <el-col :lg="12">
                            <el-form-item label="分类" prop="category">
                                <el-select v-model="animal.category" placeholder="分类" prop="category">
                                    <el-option v-for="item in view.category.options" :label="item.label"
                                               :value="item.value"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="入库日期" prop="input_date_obj">
                                <el-date-picker v-model="view.input_date_obj" type="date" placeholder="选择日期"
                                                @change="handleInputDateChange"></el-date-picker>
                            </el-form-item>
                            <el-form-item label="名称" prop="name">
                                <el-input v-model="animal.name" placeholder="名称"></el-input>
                            </el-form-item>
                            <el-form-item label="位置" prop="storage">
                                <el-input v-model="animal.storage" placeholder="位置"></el-input>
                            </el-form-item>
                            <el-form-item label="数量" prop="number">
                                <el-input v-model="animal.number" placeholder="数量" :disabled="true"></el-input>
                            </el-form-item>
                            <el-form-item label="编号" prop="serial">
                                <el-input v-model="animal.serial" placeholder="编号"></el-input>
                            </el-form-item>
                            <el-form-item label="纲" prop="clazz">
                                <el-input v-model="animal.clazz" placeholder="纲"></el-input>
                            </el-form-item>
                            <el-form-item label="目" prop="order">
                                <el-input v-model="animal.order" placeholder="目"></el-input>
                            </el-form-item>
                            <el-form-item label="科" prop="family">
                                <el-input v-model="animal.family" placeholder="科"></el-input>
                            </el-form-item>
                            <el-form-item label="属" prop="genus">
                                <el-input v-model="animal.genus" placeholder="属"></el-input>
                            </el-form-item>
                            <el-form-item label="拉丁名" prop="latin">
                                <el-input v-model="animal.latin" placeholder="拉丁名"></el-input>
                            </el-form-item>
                            <el-form-item label="描述" prop="description">
                                <el-input type="textarea" :rows="10" v-model="animal.description"
                                          placeholder="描述"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :lg="12">
                            <el-form-item label="尺寸" prop="size">
                                <el-input v-model="animal.size" placeholder="尺寸"></el-input>
                            </el-form-item>
                            <el-form-item label="保护等级（1989）" prop="level_1989">
                                <el-input v-model="animal.level_1989" placeholder="保护等级（1989）"></el-input>
                            </el-form-item>
                            <el-form-item label="保护等级（2015）" prop="level_2015">
                                <el-input v-model="animal.level_2015" placeholder="保护等级（2015）"></el-input>
                            </el-form-item>
                            <el-form-item label="保护等级（CITES）" prop="level_1989">
                                <el-input v-model="animal.level_CITES" placeholder="保护等级（CITES）"></el-input>
                            </el-form-item>
                            <el-form-item label="分布范围" prop="range">
                                <el-input v-model="animal.range" placeholder="分布范围"></el-input>
                            </el-form-item>
                            <el-form-item label="生境" prop="habitat">
                                <el-input v-model="animal.habitat" placeholder="生境"></el-input>
                            </el-form-item>
                            <el-form-item label="批次" prop="batch">
                                <el-input v-model="animal.batch" placeholder="批次"></el-input>
                            </el-form-item>
                            <el-form-item label="来源" prop="source">
                                <el-input v-model="animal.source" placeholder="来源"></el-input>
                            </el-form-item>
                            <el-form-item label="保管人" prop="keeper_id">
                                <el-select v-model="animal.keeper_id" placeholder="保管人">
                                    <el-option v-for="item in view.keeper_id.options" :label="item.label"
                                               :value="item.value"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="是否固定资产" prop="asset_id">
                                <el-input v-model="animal.asset_id" placeholder="请输入固定资产的编号"></el-input>
                            </el-form-item>
                            <el-form-item label="备注" prop="memo">
                                <el-input type="textarea" v-model="animal.memo" :rows="10" placeholder="备注"></el-input>
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
                view: {
                    tabName: 'basic',
                    input_date_obj: '',
                    fullscreenLoading: false,
                    saveLoading: false,
                    keeper_id: {
                        options: []
                    },
                    category: {
                        options: [
                            {
                                label: '剥制标本',
                                value: '剥制标本'
                            },
                            {
                                label: '浸制标本',
                                value: '浸制标本'
                            },
                            {
                                label: '骨骼标本',
                                value: '骨骼标本'
                            },
                            {
                                label: '模型标本',
                                value: '模型标本'
                            }
                        ]
                    }
                },
                animal: {
                    input_date: '',
                    input_date_obj: '',
                    category: '',
                    name: '',
                    storage : '',
                    size: '',
                    number: 1,
                    serial : '',
                    clazz : '',
                    order : '',
                    family: '',
                    genus: '',
                    latin: '',

                    level_1989 : '',
                    level_2015 : '',
                    level_CITES : '',

                    description: '',
                    range : '',
                    habitat : '',
                    batch : '',
                    source: '',
                    memo: '',

                    keeper_id: '',
                    //user_id: '',
                    asset_id: ''
                },
                rule1: {
                    name : [
                        {required : true, message : '请输入名称', trigger : 'blur'},
                        {max: 255, message: '名称不能超过255个字', trigger: 'blur'}
                    ],
                    storage : [
                        {max: 255, message: '保存地点不能超过255个字', trigger: 'blur'}
                    ],
                    size : [
                        {max: 255, message: '尺寸不能超过255个字', trigger: 'blur'}
                    ],
                    serial : [
                        {required : true, message : '请输入编号', trigger : 'blur'},
                        {max: 255, message: '编号不能超过255个字', trigger: 'blur'}
                    ],
                    clazz : [
                        {max: 255, message: '纲不能超过255个字', trigger: 'blur'}
                    ],
                    order : [
                        {max: 255, message: '目名不能超过255个字', trigger: 'blur'}
                    ],
                    family : [
                        {max: 255, message: '科名不能超过255个字', trigger: 'blur'}
                    ],
                    genus : [
                        {max: 255, message: '属名不能超过255个字', trigger: 'blur'}
                    ],
                    latin : [
                        {max: 255, message: '拉丁名不能超过255个字', trigger: 'blur'}
                    ],
                    level_1989 : [
                        {max: 255, message: '保护等级（1989）不能超过255个字', trigger: 'blur'}
                    ],
                    level_2015 : [
                        {max: 255, message: '保护等级（2015）不能超过255个字', trigger: 'blur'}
                    ],
                    level_CITES : [
                        {max: 255, message: '保护等级（CITES）不能超过255个字', trigger: 'blur'}
                    ],
                    description : [
                        {max: 2000, message: '描述不能超过2000个字', trigger: 'blur'}
                    ],
                    range : [
                        {max: 255, message: '分布范围不能超过255个字', trigger: 'blur'}
                    ],
                    habitat : [
                        {max: 255, message: '生境不能超过255个字', trigger: 'blur'}
                    ],
                    batch : [
                        {max: 255, message: '批次不能超过255个字', trigger: 'blur'}
                    ],
                    source : [
                        {max: 255, message: '来源不能超过255个字', trigger: 'blur'}
                    ],
                    memo : [
                        {max: 2000, message: '备注不能超过2000个字', trigger: 'blur'}
                    ],
                }
            }
        },
        mounted() {
            axios.get('/api/user/all')
                .then((response) => {
                    this.view.keeper_id.options = response.data;
                    if(this.$route.name == 'CollectionAnimalEdit') {
                        axios.get('/api/collection/animal/' + this.$route.params.id + '/edit')
                            .then( (response) => {
                                this.animal = response.data;
                                this.view.input_date_obj = response.data.input_date;
                            }).catch(error => {
                                if(error.response.status == 404) {
                                    this.$message.error('欲编辑的动物不存在');
                                    this.$router.push('/collection/animal');
                                } else {
                                    this.$message.error('获取动物信息错误');
                                }
                        });
                    }
                }).catch((error) => {
                this.$message.error('获取所有用户信息失败');
            });
        },
        methods: {

            //处理入库日期选择事件
            handleInputDateChange(value) {
              console.log('change');
                this.animal.input_date = value;
            },

            commit(flag) {
                //console.log('save');
                this.$refs['animal'].validate((valid) => {
                    if (valid) {
                        this.view.saveLoading = true;
                        if (this.$route.name == 'CollectionAnimalEdit') {
                            //axios.put('/api/collection/animal/' + this.$route.params.id, this.animal)
                            axios.post('/api/collection/animal/' + this.$route.params.id + '/update', this.animal)
                                .then(response => {
                                    this.$message('保存成功');
                                    this.$router.push('/collection/animal');
                                }).catch((error) => {

                                if(error.response.status == 422) {
                                    this.errors = error.rsponse.data;
                                }else if(error.response.status == 404){
                                    this.$message.error('欲保存的动物不存在');
                                }else{
                                    this.$message.error('保存动物失败');
                                }
                            });
                        } else {
                            axios.post('/api/collection/animal', this.animal)
                                .then((response) => {
                                    this.$message('保存成功');
                                    if (!flag) {
                                        this.$router.push('/collection/animal');
                                    }
                                }).catch((error) => {
                                if (error.response.status == 422) {
                                    this.errors = error.response.data;
                                } else {
                                    this.$message.error(error.response);
                                }
                            });
                        }
                        this.view.saveLoading = false;
                    }
                });
            }
        }
    }
</script>
