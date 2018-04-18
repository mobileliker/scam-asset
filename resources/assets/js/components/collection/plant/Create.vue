<template>
    <content-component>
        <el-col :lg="24">
            <el-breadcrumb id="breadcrumb" separtor="/">
                <el-breadcrumb-item :to="{path : '/'}">首页</el-breadcrumb-item>
                <!--<el-breadcrumb-item :to="{ path: '/collection' }"></el-breadcrumb-item>-->
                <el-breadcrumb-item :to="{path : '/collection/plant'}">植物管理</el-breadcrumb-item>
                <el-breadcrumb-item>添加或编辑</el-breadcrumb-item>
            </el-breadcrumb>
        </el-col>
        <el-col :lg="24">
            <error-component v-if="Object.getOwnPropertyNames(errors).length > 1" :berrors="errors"></error-component>
            <el-tabs v-model="view.tabName" type="card" v-loading.body="view.saveLoading">
                <el-tab-pane label="基本信息" name="basic">
                    <el-form ref="plant" :model="plant" label-width="80px" :rules="rule1">
                        <el-col :lg="12">
                            <el-form-item label="入库日期" prop="input_date_obj">
                                <el-date-picker v-model="view.input_date_obj" type="date" placeholder="选择日期"
                                                @change="handleInputDateChange"></el-date-picker>
                            </el-form-item>
                            <el-form-item label="名称" prop="name">
                                <el-input v-model="plant.name" placeholder="名称"></el-input>
                            </el-form-item>
                            <el-form-item label="拉丁名" prop="latin">
                                <el-input v-model="plant.latin" placeholder="拉丁名"></el-input>
                            </el-form-item>
                            <el-form-item label="科" prop="family">
                                <el-input v-model="plant.family" placeholder="科"></el-input>
                            </el-form-item>
                            <el-form-item label="类型" prop="type">
                                <el-input v-model="plant.type" placeholder="类型"></el-input>
                            </el-form-item>
                            <el-form-item label="尺寸" prop="size">
                                <el-input v-model="plant.size" placeholder="尺寸"></el-input>
                            </el-form-item>
                            <el-form-item label="来源" prop="source">
                                <el-input v-model="plant.source" placeholder="来源"></el-input>
                            </el-form-item>
                            <el-form-item label="保管人" prop="keeper_id">
                                <el-select v-model="plant.keeper_id" placeholder="保管人">
                                    <el-option v-for="item in view.keeper_id.options" :label="item.label"
                                               :value="item.value"></el-option>
                                </el-select>

                            </el-form-item>
                            <el-form-item label="描述" prop="description">
                                <el-input type="textarea" :rows="10" v-model="plant.description"
                                          placeholder="描述"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :lg="12">
                            <el-form-item label="分类" prop="category">
                                <el-select v-model="plant.category" placeholder="分类" prop="category">
                                    <el-option v-for="item in view.category.options" :label="item.label"
                                               :value="item.value"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="编号" prop="serial">
                                <el-input v-model="plant.serial" placeholder="编号"></el-input>
                            </el-form-item>
                            <el-form-item label="属">
                                <el-input v-model="plant.genus" placeholder="属"></el-input>
                            </el-form-item>
                            <el-form-item label="数量" prop="number">
                                <el-input v-model="plant.number" placeholder="数量" :disabled="true"></el-input>
                            </el-form-item>
                            <el-form-item label="产地" prop="origin">
                                <el-input v-model="plant.origin" placeholder="产地"></el-input>
                            </el-form-item>
                            <el-form-item label="存放地点" prop="storage">
                                <el-input v-model="plant.storage" placeholder="存放地点"></el-input>
                            </el-form-item>
                            <el-form-item label="是否固定资产" prop="asset_id">
                                <el-input v-model="plant.asset_id" placeholder="请输入固定资产的编号"></el-input>
                            </el-form-item>
                            <el-form-item label="备注" prop="memo">
                                <el-input type="textarea" v-model="plant.memo" :rows="10" placeholder="备注"></el-input>
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
                                label: 'D01-种质资源',
                                value: 'D01-种质资源'
                            },
                            {
                                label: 'D02-植物腊叶标本',
                                value: 'D02-植物腊叶标本'
                            },
                            {
                                label: 'D03-植物其他类型标本',
                                value: 'D03-植物其他类型标本'
                            },
                            {
                                label: 'D04-植物相关物品',
                                value: 'D04-植物相关物品'
                            },
                            {
                                label: 'D05-植物病虫害标本',
                                value: 'D05-植物病虫害标本'
                            }
                        ]
                    }
                },
                plant: {
                    input_date: '',
                    input_date_obj: '',
                    category: '',
                    family: '',
                    genus: '',
                    name: '',
                    latin: '',
                    number: 1,
                    type: '',
                    origin: '',
                    source: '',
                    description: '',
                    size: '',
                    serial: '',
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
                    latin : [
                        {max: 255, message: '拉丁名不能超过255个字', trigger: 'blur'}
                    ],
                    family : [
                        {max: 255, message: '种名不能超过255个字', trigger: 'blur'}
                    ],
                    genus : [
                        {max: 255, message: '属名不能超过255个字', trigger: 'blur'}
                    ],
                    orgin : [
                        {max: 255, message: '产地不能超过255个字', trigger: 'blur'}
                    ],
                    source : [
                        {max: 255, message: '来源不能超过255个字', trigger: 'blur'}
                    ],
                    description : [
                        {max: 2000, message: '描述不能超过2000个字', trigger: 'blur'}
                    ],
                    size : [
                        {max: 255, message: '尺寸不能超过255个字', trigger: 'blur'}
                    ],
                    type : [
                        {max: 255, message: '类型不能超过255个字', trigger: 'blur'}
                    ],
                    storage : [
                        {max: 255, message: '保存地点不能超过255个字', trigger: 'blur'}
                    ],
                    serial : [
                        {required : true, message : '请输入编号', trigger : 'blur'},
                        {max: 255, message: '编号不能超过255个字', trigger: 'blur'}
                    ]
                }
            }
        },
        mounted() {
            axios.get('/api/user/all')
                .then((response) => {
                    this.view.keeper_id.options = response.data;
                    if(this.$route.name == 'CollectionPlantEdit') {
                        axios.get('/api/collection/plant/' + this.$route.params.id + '/edit')
                            .then( (response) => {
                                this.plant = response.data;
                                this.view.input_date_obj = response.data.input_date;
                            }).catch(error => {
                                if(error.response.status == 404) {
                                    this.$message.error('欲编辑的植物不存在');
                                    this.$router.push('/collection/plant');
                                } else {
                                    this.$message.error('获取植物信息错误');
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
                this.plant.input_date = value;
            },

            commit(flag) {
                //console.log('save');
                this.$refs['plant'].validate((valid) => {
                    if (valid) {
                        this.view.saveLoading = true;
                        if (this.$route.name == 'CollectionPlantEdit') {
                            //axios.put('/api/collection/plant/' + this.$route.params.id, this.plant)
                            axios.post('/api/collection/plant/' + this.$route.params.id + '/update', this.plant)
                                .then(response => {
                                    this.$message('保存成功');
                                    this.$router.push('/collection/plant');
                                }).catch((error) => {

                                if(error.response.status == 422) {
                                    this.errors = error.rsponse.data;
                                }else if(error.response.status == 404){
                                    this.$message.error('欲保存的植物不存在');
                                }else{
                                    this.$message.error('保存植物失败');
                                }
                            });
                        } else {
                            axios.post('/api/collection/plant', this.plant)
                                .then((response) => {
                                    this.$message('保存成功');
                                    if (!flag) {
                                        this.$router.push('/collection/plant');
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
