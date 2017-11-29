<template>
    <content-component>
      <el-col :lg="24">
          <el-breadcrumb id="breadcrumb" separtor="/">
              <el-breadcrumb-item :to="{path : '/'}">首页</el-breadcrumb-item>
              <!--<el-breadcrumb-item :to="{ path: '/collection' }"></el-breadcrumb-item>-->
              <el-breadcrumb-item :to="{path : '/collection/soil'}">土壤管理</el-breadcrumb-item>
              <el-breadcrumb-item>添加或编辑</el-breadcrumb-item>
          </el-breadcrumb>
      </el-col>
      <el-col :lg="24">
          <error-component v-if="Object.getOwnPropertyNames(errors).length > 1" :berrors="errors"></error-component>
          <el-tabs v-model="view.tabName" type="card" v-loading.body="view.saveLoading">
              <el-tab-pane label="基本信息" name="basic">
                  <el-form ref="soil" :model="soil" label-width="80px" :rules="rule1">
                      <el-col :lg="12">
                          <el-form-item label="入库日期" prop="input_date_obj">
                              <el-date-picker v-model="view.input_date_obj" type="date" placeholder="选择日期"
                                              @change="handleInputDateChange"></el-date-picker>
                          </el-form-item>
                          <el-form-item label="名称" prop="name">
                              <el-input v-model="soil.name" placeholder="名称"></el-input>
                          </el-form-item>
                          <el-form-item label="英文名" prop="ename">
                              <el-input v-model="soil.ename" placeholder="英文名"></el-input>
                          </el-form-item>
                          <el-form-item label="地区" prop="region">
                              <el-input v-model="soil.region" placeholder="地区"></el-input>
                          </el-form-item>
                          <el-form-item label="编号" prop="serial">
                              <el-input v-model="soil.serial" placeholder="编号"></el-input>
                          </el-form-item>
                          <el-form-item label="采集地点" prop="origin">
                              <el-input v-model="soil.origin" placeholder="采集地点"></el-input>
                          </el-form-item>
                          <el-form-item label="经纬度" prop="location">
                              <el-input v-model="soil.location" placeholder="经纬度"></el-input>
                          </el-form-item>
                          <el-form-item label="保管人" prop="keeper_id">
                              <el-select v-model="soil.keeper_id" placeholder="保管人">
                                  <el-option v-for="item in view.keeper_id.options" :label="item.label"
                                             :value="item.value"></el-option>
                              </el-select>

                          </el-form-item>
                          <el-form-item label="描述" prop="description">
                              <el-input type="textarea" :rows="10" v-model="soil.description"
                                        placeholder="描述"></el-input>
                          </el-form-item>
                      </el-col>
                      <el-col :lg="12">
                          <el-form-item label="海拔" prop="altitude">
                              <el-input v-model="soil.altitude" placeholder="海拔"></el-input>
                          </el-form-item>
                          <el-form-item label="地形" prop="terrain">
                              <el-input v-model="soil.terrain" placeholder="地形"></el-input>
                          </el-form-item>
                          <el-form-item label="坡度" prop="gradient">
                              <el-input v-model="soil.gradient" placeholder="坡度"></el-input>
                          </el-form-item>
                          <el-form-item label="母质" prop="matrix">
                              <el-input v-model="soil.matrix" placeholder="母质"></el-input>
                          </el-form-item>
                          <el-form-item label="植被" prop="vegetation">
                              <el-input v-model="soil.vegetation" placeholder="植被"></el-input>
                          </el-form-item>
                          <el-form-item label="利用状况" prop="use_status">
                              <el-input v-model="soil.use_status" placeholder="利用状况"></el-input>
                          </el-form-item>
                          <el-form-item label="土层深度" prop="depth">
                              <el-input v-model="soil.depth" placeholder="土层深度"></el-input>
                          </el-form-item>
                          <el-form-item label="采集人" prop="collecter">
                              <el-input v-model="soil.collecter" placeholder="采集人"></el-input>
                          </el-form-item>
                          <el-form-item label="是否固定资产" prop="asset_id">
                              <el-input v-model="soil.asset_id" placeholder="请输入固定资产的编号"></el-input>
                          </el-form-item>
                          <el-form-item label="备注" prop="memo">
                              <el-input type="textarea" v-model="soil.memo" :rows="10" placeholder="备注"></el-input>
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

<style>
    #breadcrumb {
        font-size: 16px;
        padding: 8px 15px;
        margin-bottom: 22px;
        list-style: none;
        background-color: #f5f5f5;
        border-radius: 4px;
    }
</style>

<script scoped>
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
                    }
                },
                soil: {
                    input_date: '',
                    input_date_obj: '',
                    name: '',
                    ename: '',
                    region: '',
                    serial: '',
                    origin: '',
                    location: '',
                    altitude: '',
                    terrain: '',
                    gradient: '',
                    matrix: '',
                    vegetation : '',
                    user_status : '',
                    depth : '',
                    collecter : '',
                    description : '',
                    memo : '',
                    keeper_id: '',
                    //user_id: '',
                    asset_id: ''
                },
                rule1: {
                    name : [
                        {required : true, message : '请输入名称', trigger : 'blur'},
                        {max: 255, message: '名称不能超过255个字', trigger: 'blur'}
                    ],
                    ename : [
                        {max: 255, message: '英文名不能超过255个字', trigger: 'blur'}
                    ],
                    region : [
                        {max: 255, message: '区域不能超过255个字', trigger: 'blur'}
                    ],
                    serial : [
                        {required : true, message : '请输入编号', trigger : 'blur'},
                        {max: 255, message: '编号不能超过255个字', trigger: 'blur'}
                    ],
                    location : [
                        {max: 255, message: '经纬度不能超过255个字', trigger: 'blur'}
                    ],
                    //altitude : [
                    //    {required : false, type: 'float', message: '海拔必须为为数值', trigger: 'blur'}
                    //],
                    terrain : [
                      {max : 255, message : '地形不能超过255个字', trigger : 'blue'}
                    ],
                    gradient : [
                        {max: 255, message: '坡度不能超过255个字', trigger: 'blur'}
                    ],
                    matrix : [
                        {max: 255, message: '母质不能超过255个字', trigger: 'blur'}
                    ],
                    vegetation : [
                        {max: 255, message: '植被不能超过255个字', trigger: 'blur'}
                    ],
                    use_status : [
                        {max: 255, message: '利用状况不能超过255个字', trigger: 'blur'}
                    ],
                    depth : [
                        {max: 255, message: '土层深度不能超过255个字', trigger: 'blur'}
                    ],
                    collecter : [
                        {max: 255, message: '采集人不能超过255个字', trigger: 'blur'}
                    ],
                    description : [
                        {max: 2000, message: '描述不能超过2000个字', trigger: 'blur'}
                    ],
                    memo : [
                        {max: 2000, message: '备注不能超过2000个字', trigger: 'blur'}
                    ]
                }
            }
        },
        mounted() {
            axios.get('/api/user/all')
                .then((response) => {
                    this.view.keeper_id.options = response.data;
                    if(this.$route.name == 'CollectionSoilEdit') {
                        axios.get('/api/collection/soil/' + this.$route.params.id + '/edit')
                            .then( (response) => {
                                this.soil = response.data;
                                this.view.input_date_obj = response.data.input_date;
                            }).catch(error => {
                                if(error.response.status == 404) {
                                    this.$message.error('欲编辑的土壤不存在');
                                    this.$router.push('/collection/soil');
                                } else {
                                    this.$message.error('获取土壤信息错误');
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
                this.soil.input_date = value;
            },

            commit(flag) {
                //console.log('save');
                this.$refs['soil'].validate((valid) => {
                    if (valid) {
                        this.view.saveLoading = true;
                        if (this.$route.name == 'CollectionSoilEdit') {
                            axios.put('/api/collection/soil/' + this.$route.params.id, this.soil)
                                .then(response => {
                                    this.$message('保存成功');
                                    this.$router.push('/collection/soil');
                                }).catch((error) => {

                                if(error.response.status == 422) {
                                    this.errors = error.rsponse.data;
                                }else if(error.response.status == 404){
                                    this.$message.error('欲保存的土壤不存在');
                                }else{
                                    this.$message.error('保存土壤失败');
                                }
                            });
                        } else {
                            axios.post('/api/collection/soil', this.soil)
                                .then((response) => {
                                    this.$message('保存成功');
                                    if (!flag) {
                                        this.$router.push('/collection/soil');
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
