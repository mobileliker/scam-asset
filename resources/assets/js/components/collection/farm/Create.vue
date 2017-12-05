/**
 * 农具管理的新增或者编辑页面
 * @version 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/4
 * @description :
 * (1)基本功能；
 * (2)注释掉keeper_id的integer的验证；（2017/12/4）
 * （3）去除对数量的验证；（2017/12/5）
 */

<template>
    <content-component>
        <el-col :lg="24">
            <el-breadcrumb id="breadcrumb" separtor="/">
                <el-breadcrumb-item :to="{path : '/'}">首页</el-breadcrumb-item>
                <!--<el-breadcrumb-item :to="{ path: '/collection' }"></el-breadcrumb-item>-->
                <el-breadcrumb-item :to="{path : '/collection/farm'}">农具管理</el-breadcrumb-item>
                <el-breadcrumb-item>添加或编辑</el-breadcrumb-item>
            </el-breadcrumb>
        </el-col>
        <el-col :lg="24">
            <error-component v-if="Object.getOwnPropertyNames(errors).length > 1" :berrors="errors"></error-component>
            <el-tabs v-model="view.tabName" type="card"   v-loading.body="view.saveLoading">
                <el-tab-pane label="基本信息" name="basic">
                    <el-form ref="farm" :model="farm" label-width="80px" :rules="rule1">
                        <el-col :lg="12">
                            <el-form-item label="入库日期" prop="input_date_obj">
                                <el-date-picker v-model="view.input_date_obj" type="date" placeholder="选择日期"
                                                @change="handleInputDateChange"></el-date-picker>
                            </el-form-item>
                            <el-form-item label="名称" prop="name">
                                <el-input v-model="farm.name" placeholder="名称"></el-input>
                            </el-form-item>
                            <el-form-item label="来源" prop="source">
                                <el-input v-model="farm.source" placeholder="来源"></el-input>
                            </el-form-item>
                            <el-form-item label="尺寸" prop="size">
                                <el-input v-model="farm.size" placeholder="尺寸"></el-input>
                            </el-form-item>
                            <el-form-item label="保管人" prop="keeper_id">
                                <el-select v-model="farm.keeper_id" placeholder="保管人">
                                    <el-option v-for="item in view.keeper_id.options" :label="item.label"
                                               :value="item.value"></el-option>
                                </el-select>

                            </el-form-item>
                            <el-form-item label="描述" prop="description">
                                <el-input type="textarea" :rows="10" v-model="farm.description" placeholder="描述"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :lg="12">
                            <el-form-item label="分类" prop="category">
                                <el-select v-model="farm.category" placeholder="分类" prop="category">
                                    <el-option v-for="item in view.category.options" :label="item.label"
                                               :value="item.value"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="编号" prop="serial">
                                <el-input v-model="farm.serial" placeholder="编号"></el-input>
                            </el-form-item>
                            <el-form-item label="数量" prop="number">
                                <el-input v-model="farm.number" placeholder="数量" :disabled="true"></el-input>
                            </el-form-item>
                            <el-form-item label="展示区域" prop="display">
                                <el-select v-model="farm.display" clearable placeholder="展示区域" prop="display">
                                    <el-option v-for="item in view.display.options" :label="item.label"
                                               :value="item.value"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="是否固定资产" prop="asset_id">
                                <el-input v-model="farm.asset_id" placeholder="请输入固定资产的编号"></el-input>
                            </el-form-item>
                            <el-form-item label="备注" prop="memo">
                                <el-input type="textarea" v-model="farm.memo" :rows="10" placeholder="备注"></el-input>
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
        data () {
            return {
                errors: {},
                view: {
                    tabName: 'basic',
                    category: {
                        options: [ //大田农作生产农具分类：1 耕地整地工具、2 播种工具、3 中耕工具、4 施肥植保工具、5 排灌工具、6 收割工具、 7 运输工具、8 脱晒工具、9 加工工具、10 计量工具、11劳保工具、12 农用器具；专门类：制酒用具，茶叶种植、采集加工用具，蚕桑丝织用器，棉纺织用器，甘蔗种植、收获、加工用具，渔业生产用具，岭南热带水果种植、收获、加工用具。
                            {
                                label: '耕地整地工具',
                                value: '耕地整地工具'
                            },
                            {
                                label: '播种工具',
                                value: '播种工具'
                            },
                            {
                                label: '中耕工具',
                                value: '中耕工具'
                            },
                            {
                                label: '施肥植保工具',
                                value: '施肥植保工具'
                            },
                            {
                                label: '排灌工具',
                                value: '排灌工具'
                            },
                            {
                                label: '收割工具',
                                value: '收割工具'
                            },
                            {
                                label: '运输工具',
                                value: '运输工具'
                            },
                            {
                                label: '脱晒工具',
                                value: '脱晒工具'
                            },
                            {
                                label: '加工工具',
                                value: '加工工具'
                            },
                            {
                                label: '计量工具',
                                value: '计量工具'
                            },
                            {
                                label: '劳保工具',
                                value: '劳保工具'
                            },
                            {
                                label: '农用器具',
                                value: '农用器具'
                            },
                            {
                                label: '制酒用具',
                                value: '制酒用具'
                            },
                            {
                                label: '茶叶种植、采集加工用具',
                                value: '茶叶种植、采集加工用具'
                            },
                            {
                                label: '蚕桑丝织用器',
                                value: '蚕桑丝织用器'
                            },
                            {
                                label: '棉纺织用器',
                                value: '棉纺织用器'
                            },
                            {
                                label: '甘蔗种植、收获、加工用具',
                                value: '甘蔗种植、收获、加工用具'
                            },
                            {
                                label: '渔业生产用具',
                                value: '渔业生产用具'
                            },
                            {
                                label: '岭南热带水果种植、收获、加工用具',
                                value: '岭南热带水果种植、收获、加工用具'
                            }
                        ]
                    },
                    display: {
                        options: [
                            {
                                'label': '传统农具展厅（大田常规农具）',
                                'value': '传统农具展厅（大田常规农具）'
                            },
                            {
                                'label': '传统农具展厅（专门类农具）',
                                'value': '传统农具展厅（专门类农具）'
                            },
                            {
                                'label': '广东农业文明展厅（先秦时期）',
                                'value': '广东农业文明展厅（先秦时期）'
                            }
                        ]
                    },
                    keeper_id: {
                        options: [
//                            {
//                                label : 'admin',
//                                value : 'admin'
//                            }
                        ]
                    },
                    input_date_obj : '',
                    fullscreenLoading: false,
                    saveLoading : false
                },
                farm: {
                    input_date: '',
                    input_date_obj : '',
                    category: '',
                    name: '',
                    number: 1,
                    source: '',
                    description: '',
                    size: '',
                    serial: '',
                    memo: '',
                    display: '',
                    keeper_id: '',
                    //user_id: '',
                    asset_id: ''
                },
                rule1: {
                    //input_date_obj: [
                    //    {type: 'date', required: true, message: '请输入入库日期', trigger: 'blur'}
                    //],
                    name: [
                        {required: true, message: '请输入名称', trigger: 'blur'},
                        {max: 255, message: '名称不能超过255个字', trigger: 'blur'}
                    ],
                    source: [
                        {max: 255, message: '来源不能超过255个字', trigger: 'blur'}
                    ],
                    size: [
                        {max: 255, message: '尺寸不能超过255个字', trigger: 'blur'}
                    ],
                    //keeper_id : [
                        //{type : 'integer', required : true, message : '请选择保管人', trigger : 'change'}
                    //],
                    description: [
                        {max: 2000, message: '描述不能超过2000个字', trigger: 'blur'}
                    ],
                    category : [
                        {required : true, message : '请选择分类', trigger : 'change'}
                    ],
                    serial: [
                        {required: true, message: '请输入编号', trigger: 'blur'},
                        {max: 255, message: '编号不能超过255个字', trigger: 'blur'}
                    ],
                    //number: [
                        //{type : 'integer', required: true, message: '请输入数量', trigger: 'blur'},
                        //{type: 'integer', message: '请输入正确的整数', trigger: 'blur'},
                        //{type: 'integer', min: 0, message: '数量必须为正数', trigger: 'blur'}
                    //],
                    display : [
                        {required : true, message : '请选择展示区域', trigger : 'change'}
                    ],
                    asset_id : [
                        {max : '255', message : '固定资产编号不能超过255', trigger : 'blur'}
                    ],
                    memo: [
                        {max: 2000, message: '备注不能超过2000个字', trigger: 'blur'}
                    ]
                }
            }
        },
        mounted () {
            //获取所有用户
            axios.get('/api/user/all')
                    .then((response) => {
                this.view.keeper_id.options = response.data;
                if(this.$route.name == 'CollectionFarmEdit'){
                    axios.get('/api/collection/farm/' + this.$route.params.id + '/edit')
                            .then(response => {
                        this.farm = response.data;
                        this.view.input_date_obj = response.data.input_date;
                    }).catch(error => {
                        if(error.response.status == 404){
                            this.$message.error('欲编辑的农具不存在');
                            this.$router.push('/collection/farm');
                        }else{
                            this.$message.error('获取农具信息错误');
                        }
                    });
                }
        }).
            catch((error) => {
                this.$message.error('获取所有用户信息失败');
        });
        },
        methods: {

            //处理入库日期选择事件
            handleInputDateChange(value){
                this.farm.input_date = value;
            },


            //保存
            commit(flag) {
                this.$refs['farm'].validate((valid) => {
                    if (valid) { //表单验证通过
                        //console.log('validate success');
                        this.view.saveLoading = true;
                        if(this.$route.name == 'CollectionFarmEdit'){
                            //axios.put('/api/collection/farm/' + this.$route.params.id, this.farm)
                            axios.post('/api/collection/farm/' + this.$route.params.id + '/update', this.farm)
                                    .then(response => {
                                //console.log(response.data);
                                this.$message('保存成功');
                                this.$router.push('/collection/farm');
                            }).catch(error => {
                                if(error.response.status == 422) {
                                    this.errors = error.rsponse.data;
                                }else if(error.response.status == 404){
                                    this.$message.error('欲保存的农具不存在');
                                }else{
                                    this.$message.error('保存农具失败');
                                }
                            });
                        }else{
                            axios.post('/api/collection/farm', this.farm)
                                    .then( (response) => {
                                //console.log(response.data);
                                this.$message('保存成功');
                                if(!flag){
                                    this.$router.push('/collection/farm');
                                }
                            }).catch(error => {
                                    if(error.response.status == 422){
                                    this.errors = error.response.data;
                                }else{
                                    this.$message.error(error.response);
                                }
                            });
                        }
                        this.view.saveLoading = false;
                    }
//                    else{
//                        console.log('validate fail');
//                    }
                });
            }
        }
    }
</script>
