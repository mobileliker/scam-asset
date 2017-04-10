<template>
    <content-component id="content">
        <el-col :lg="24">
            <el-breadcrumb id="breadcrumb" separator="/">
              <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
              <el-breadcrumb-item :to="{ path: '/asset'}">资产管理</el-breadcrumb-item>
              <el-breadcrumb-item>新增</el-breadcrumb-item>
            </el-breadcrumb>
        </el-col>
        <el-col :lg="24">
          <error-component v-if="Object.getOwnPropertyNames(errors).length > 1" :berrors="errors"></error-component>
          <el-tabs v-model="tabName" type="card">
            <el-tab-pane label="基本信息" name="basic">
            <el-form ref="form" :model="asset" label-width="80px">
              <el-col :lg="12">
              <el-form-item label="入账日期">
                <el-date-picker v-model="asset.post_date_obj" type="date" placeholder="选择日期" @change="postDateChange"></el-date-picker>
              </el-form-item>
              <el-form-item label="展厅">
                <el-select v-model="asset.type" placeholder="请选择...">
                    <el-option v-for="item in type.options" :label="item.label" :value="item.value"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="名称">
                <el-input v-model="asset.name" placeholder="名称"></el-input>
              </el-form-item>
              <el-form-item label="编号">
                <el-input v-model="asset.serial" placeholder="编号"></el-input>
              </el-form-item>
              <el-form-item label="经费科目">
                <el-select v-model="asset.course" placeholder="请选择...">
                    <el-option v-for="item in course.options" :label="item.name" :value="item.value"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="型号">
                <el-input v-model="asset.model" placeholder="型号"></el-input>
              </el-form-item>
              <el-form-item label="尺寸">
                <el-input v-model="asset.size" placeholder="尺寸"></el-input>
              </el-form-item>
              <el-form-item label="领用单位">
                <el-input v-model="asset.consumer_company" placeholder="领用单位"></el-input>
              </el-form-item>
              <el-form-item label="厂家">
                <el-input v-model="asset.factory" placeholder="厂家"></el-input>
              </el-form-item>
              </el-col>
              <el-col :lg="12">
              <el-form-item label="供应商">
                <el-input v-model="asset.provider" placeholder="供应商"></el-input>
              </el-form-item>
              <el-form-item label="国别">
                <el-select v-model="asset.country" placeholder="国别">
                    <el-option v-for="item in country.options" :label="item.label" :value="item.value"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="保存地点">
                <el-select v-model="asset.storage_location" placeholder="保存地点">
                    <el-option v-for="item in storage_location.options" :label="item.name" :value="item.value"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="使用方向">
                <el-select v-model="asset.application" placeholer="使用方向">
                    <el-option v-for="item in application.options" :label="item.label" :value="item.value"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="分类">
                <el-select v-model="asset.category_number" placeholder="分类">
                    <el-option v-for="item in category_number.options" :label="item.name" :value="item.value"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="图片">
                  <el-upload
                    class="upload-demo" :headers = "token"
                    action="/admin/image/update"
                    :on-preview="handlePreview"
                    :on-remove="handleRemove"
                    :on-success="handleSuccess"
                    list-type="picture"
                    accept="image/*">
                    <el-button size="small" type="primary">点击上传</el-button>
                    <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
                  </el-upload>
              </el-form-item>
                </el-col>
              </el-form>
            </el-tab-pane>
            <el-tab-pane label="财务信息" name="second">
                <el-form ref="form" :model="asset" label-width="80px">
                    <el-col :lg="12">
                      <el-form-item label="发票号">
                        <el-input v-model="asset.invoice" placeholder="发票号"></el-input>
                      </el-form-item>
                      <el-form-item label="申购单号">
                        <el-input v-model="asset.purchase_number" placeholder="申购单号"></el-input>
                      </el-form-item>
                      <el-form-item label="购置日期">
                        <el-date-picker v-model="asset.purchase_date" type="date" placeholder="购置日期" @change="purchaseDateChange"></el-date-picker>
                      </el-form-item>
                      <el-form-item label="经费卡号">
                        <el-select v-model="asset.card" placeholder="经费卡号">
                            <el-option v-for="item in card.options" :label="item.name" :value="item.value"></el-option>
                        </el-select>
                      </el-form-item>
                      <el-form-item label="价格">
                        <el-input v-model="asset.price" placeholder="价格" type="number"></el-input>
                      </el-form-item>
                      <el-form-item label="数量">
                        <el-input v-model="asset.amount" placeholder="数量" type="number"></el-input>
                      </el-form-item>
                      <el-form-item label="金额">
                        <el-input v-model="asset.sum" placeholder="金额" type="number"></el-input>
                      </el-form-item>
                      </el-col>
                    </el-form>
            </el-tab-pane>
            <el-tab-pane label="操作信息" name="third">
                <el-form ref="form" :model="asset" label-width="80px">
                  <el-col :lg="12">
                      <el-form-item label="录入">
                        <el-input v-model="asset.entry" placeholder="录入"></el-input>
                      </el-form-item>
                      <el-form-item label="领用">
                        <el-select v-model="asset.consumer_id" placeholder="请选择...">
                            <el-option v-for="item in consumer_id.options" :label="item.name" :value="item.id"></el-option>
                        </el-select>
                      </el-form-item>
                      <el-form-item label="经手">
                        <el-select v-model="asset.handler_id" placeholder="请选择...">
                            <el-option v-for="item in handler_id.options" :label="item.name" :value="item.id"></el-option>
                        </el-select>
                      </el-form-item>
                  </el-col>
                </el-form>
            </el-tab-pane>
          </el-tabs>
        </el-col>
        <el-col :lg="24">
            <el-button type="primary" @click="commit">保存</el-button>
        </el-col>
    </content-component>
</template>
<style scope>
    .el-upload__input {
        display : none !important;
    }
</style>
<script>
    import content from '../layouts/Content.vue'
    import error from '../layouts/Error.vue'
    export default {
        components: {
            'content-component' : content,
            'error-component' : error

        },
        data() {
            return {
                token: {'X-CSRF-TOKEN': window.Laravel.csrfToken},
                tabName : 'basic',
                type : {
                    options : [
                        {
                            value : 1,
                            label : '农业文明史展厅A(单据号为1xxxxxxx)'
                        },
                        {
                            value : 2,
                            label : '传统农具展厅B(单据号为2xxxxxxx）'
                        },
                        {
                            value : 3,
                            label : '土壤与岩石展厅C(单据号为3xxxxxxx)'
                        },
                        {
                            value : 4,
                            label : '植物世界展厅D(单据号为4xxxxxxx)'
                        },
                        {
                            value : 5,
                            label : '动物世界展厅E(单据号为5xxxxxxx)'
                        },
                        {
                            value : 6,
                            label : '昆虫世界展厅F(单据号为6xxxxxxx)'
                        },
                        {
                            value : 7,
                            label : '林业资源与生产展厅G(单据号为7xxxxxxx)'
                        },
                        {
                            value : 8,
                            label : '南海海洋生物展厅H(单据号为8xxxxxxx)'
                        },
                        {
                            value : 9,
                            label : '可转让科技成果专题展厅I(单据号为9xxxxxxx)'
                        }
                    ]
                },
                course : {
                    value : '',
                    options : [
                    ]
                },
                country : {
                    value : '',
                    options : [
                        {
                            value : '中国',
                            label : '中国'
                        }
                    ]
                },
                storage_location : {
                    options : []
                },
                application : {
                    options : []
                },
                category_number : {
                    options : []
                },
                card : {
                    options : []
                },
                consumer_id : {
                    options : []
                },
                handler_id : {
                    options : []
                },
                asset : {
                    post_date_obj: '',
                    post_date : '',
                    type : 1,
                    name : '',
                    serial : '',
                    course : '',
                    model : '',
                    size : '',
                    consumer_company : '华南农业博物馆',
                    factory : '',
                    provider : '',
                    country : '中国',
                    storage_location : '',
                    application : '',
                    category_number : '',
                    image : '',
                    invoice : '',
                    purchase_number : '',
                    purchase_date_obj : '',
                    purchase_date : '',
                    card : '',
                    price : '',
                    amount : '1',
                    sum : '',
                    entry : '华南农业博物馆',
                    consumer_id : '',
                    handler_id : ''
                },
                errors : {}
            }
        },
        mounted() {
            axios.get('/admin/category/course')
                .then( response => {
                    //console.log(response.data);
                    this.course.options = response.data;
                });
            axios.get('/admin/category/storage_location')
                .then( response => {
                    //console.log(response.data);
                    this.storage_location.options = response.data;
                });
            axios.get('/admin/category/application')
                .then( response => {
                    //console.log(response.data);
                    this.application.options = response.data;
                });
            axios.get('/admin/category/category')
                .then( response => {
                    //console.log(response.data);
                    this.category_number.options = response.data;
                });
            axios.get('/admin/category/card')
                .then( response => {
                    //console.log(response.data);
                    this.card.options = response.data;
                });
            axios.get('/admin/user/all')
                .then( response => {
                    //console.log(response.data);
                    this.consumer_id.options = response.data;
                    this.handler_id.options = response.data;
                });
        },
        watch : {
            'asset.price' :{
              handler: function (val, oldVal) {
                this.asset.sum = val * this.asset.amount;
              },
              deep: true
            },
            'asset.amount' :{
              handler: function (val, oldVal) {
                this.asset.sum = val * this.asset.price;
              },
              deep: true
            }
        },
        methods : {
            commit() {
                //console.log('commit');
                axios.post('/admin/asset', this.asset)
                    .then(response => {
                        //console.log(response);
                        this.$router.push('/asset');
                    }).catch(error => {
                        //console.log(error.response.data);
                        if(error.response.status == 422) {
                            this.errors = error.response.data;
                        }
                    });
            },

              handleRemove(file, fileList) {
                //console.log(file, fileList);
              },
              handlePreview(file) {
                //console.log(file);
              },
              handleSuccess(response, file, fileList){
                //console.log(response);
                this.asset.image = response.url;
                //console.log(this.asset.image);
              },
              postDateChange(value) {
                this.asset.post_date = value;
              },
              purchaseDateChange(value) {
                this.asset.purchase_date = value;
              }
        }
    }
</script>