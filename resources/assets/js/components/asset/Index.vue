/**
* @version: 2.0
* @author: wuzhihui
* @date: 2017/7/10
* @description:
* （1）修复搜索框中日期选择的错误，优化类型选择可清除；（2017/7/10）
* （2）修复搜索框中日期选择的清除后无法重新加载数据的错误；（2017/7/14）
*/
<template>
    <content-component id="content">
        <el-col :lg="24">
            <el-breadcrumb id="breadcrumb" separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
                <el-breadcrumb-item>资产管理</el-breadcrumb-item>
            </el-breadcrumb>
        </el-col>
        <el-col :lg="4">
            <el-select v-model="search.type.value" placeholder="类型" clearable>
                <el-option v-for="item in search.type.options" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-col>
        <el-col :lg="4">
            <el-date-picker v-model="search.post_date.value" type="daterange" align="right" placeholder="选择日期范围"
                            :picker-options="search.post_date.pickerOptions" @change="changePostDate"></el-date-picker>
        </el-col>
        <el-col :lg="4">
            <el-input placeholder="请输入名称进行查询" icon="search" v-model="search.query_text"
                      :on-icon-click="handleIconClick"></el-input>
        </el-col>
        <el-col :lg="5" class="pull-right">
            <el-button type="success" @click="exportAsset">导出</el-button>
            <el-button type="success" @click="handleImport">导入</el-button>
            <el-button type="success" @click="exportPrint">报增单</el-button>
            <el-button type="success">
                <router-link to="/asset/create">添加</router-link>
            </el-button>
        </el-col>
        <el-col :lg="24" class="list">
            <el-table :data="list.data" border style="width: 100%" v-loading="loading" element-loading-text="拼命加载数据中"
                      @selection-change="handleSelectionChange" @sort-change="sortChange">
                <el-table-column type="selection"></el-table-column>
                <el-table-column type="index" label="序号" width="70"></el-table-column>
                <el-table-column prop="post_date" label="入账日期" sortable></el-table-column>
                <el-table-column prop="type" label="类型" sortable></el-table-column>
                <el-table-column prop="category_name" label="分类" sortable></el-table-column>
                <el-table-column prop="name" label="名称" sortable></el-table-column>
                <el-table-column prop="serial" label="编号" sortable></el-table-column>
                <el-table-column prop="sum" label="金额" sortable></el-table-column>
                <el-table-column prop="consumer_name" label="领用" sortable></el-table-column>
                <el-table-column prop="handler_name" label="经手" sortable></el-table-column>
                <el-table-column fixed="right" label="操作" width="150">
                    <template scope="scope">
                        <el-button type="text" size="small" @click="exportInvoice(scope.row.id)">导出</el-button>
                        <el-button type="text" size="small">
                            <router-link :to="'/asset/' + scope.row.id + '/edit'">编辑</router-link>
                        </el-button>
                        <el-button type="text" size="small" @click="deleteRow(scope.$index, scope.row.id, list.data)">
                            删除
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-col>
        <el-col :lg="24">
            <el-button type="success" @click="batchDelete">删除</el-button>
            <el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page="list.current_page"
                    :page-sizes="['10', '15', '20', '50']"
                    :page-size="list.per_page"
                    layout="total, sizes, prev, pager, next, jumper"
                    :total="list.total" class="pull-right">
            </el-pagination>
        </el-col>


        <el-dialog title="导入数据" v-model="dialog.import.visible" size="small">
            <error-component v-if="Object.getOwnPropertyNames(dialog.import.errors).length > 1"
                             :berrors="dialog.import.errors"></error-component>
            <el-form v-model="dialog.import.model" ref="ImportModel" :rules="dialog.import.rules">
                <span>导入方式：</span>
                <el-radio class="radio" v-model="dialog.import.model.type" label="cover">覆盖</el-radio>
                <el-radio class="radio" v-model="dialog.import.model.type" label="ignore">忽略</el-radio>
                <el-upload
                        class="upload-demo"
                        :headers="token"
                        drag
                        accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        action="/api/file/update"
                        :on-success="handleSuccess">
                    <i class="el-icon-upload"></i>
                    <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                    <div class="el-upload__tip" slot="tip">只能上传xls/xlsx文件，且不超过500kb</div>
                </el-upload>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialog.import.visible=false">取消</el-button>
                <el-button type="primary" @click="handleImportSave" element-loading-text="导入中..."
                           v-loading.fullscreen.lock="fullscreenLoading">导入
                </el-button>
            </div>
        </el-dialog>
    </content-component>
</template>

<style scope>
    #breadcrumb {
        font-size: 16px;
        padding: 8px 15px;
        margin-bottom: 22px;
        list-style: none;
        background-color: #f5f5f5;
        border-radius: 4px;
    }

    .el-button--success a {
        color: #fff;
    }

    .list {
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>

<script>
    import content from '../layouts/Content.vue'

    export default {
        data() {
            return {
                token: {'X-CSRF-TOKEN': window.Laravel.csrfToken},
                loading: true,
                fullscreenLoading: false,
                search: {
                    type: {
                        value: '',
                        options: [
                            {
                                value: '',
                                label: '全部'
                            },
                            {
                                value: 1,
                                label: '农业文明史展厅A(单据号为1xxxxxxx)'
                            },
                            {
                                value: 2,
                                label: '传统农具展厅B(单据号为2xxxxxxx）'
                            },
                            {
                                value: 3,
                                label: '土壤与岩石展厅C(单据号为3xxxxxxx)'
                            },
                            {
                                value: 4,
                                label: '植物世界展厅D(单据号为4xxxxxxx)'
                            },
                            {
                                value: 5,
                                label: '动物世界展厅E(单据号为5xxxxxxx)'
                            },
                            {
                                value: 6,
                                label: '昆虫世界展厅F(单据号为6xxxxxxx)'
                            },
                            {
                                value: 7,
                                label: '林业资源与生产展厅G(单据号为7xxxxxxx)'
                            },
                            {
                                value: 8,
                                label: '南海海洋生物展厅H(单据号为8xxxxxxx)'
                            },
                            {
                                value: 9,
                                label: '可转让科技成果专题展厅I(单据号为9xxxxxxx)'
                            }
                        ]
                    },
                    post_date: {
                        value: '',
                        post_date_start: '',
                        post_date_end: '',
                        pickerOptions: {
                            shortcuts: [{
                                text: '最近一周',
                                onClick(picker) {
                                    const end = new Date();
                                    const start = new Date();
                                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                                    picker.$emit('pick', [start, end]);
                                }
                            }, {
                                text: '最近一个月',
                                onClick(picker) {
                                    const end = new Date();
                                    const start = new Date();
                                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                                    picker.$emit('pick', [start, end]);
                                }
                            }, {
                                text: '最近三个月',
                                onClick(picker) {
                                    const end = new Date();
                                    const start = new Date();
                                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                                    picker.$emit('pick', [start, end]);
                                }
                            }]
                        }
                    },
                    'query_text': '',
                    '_sort': 'id',
                    '_order': 'desc'
                },
                list: {
                    current_page: 1,
                    from: '',
                    'last_page': '',
                    'next_page_url': '',
                    'per_page': 10,
                    'prev_page_url': '',
                    'to': '',
                    'total': 0,
                    data: []
                },
                batch: {
                    asset: [],
                    params: {
                        ids: []
                    }
                },
                dialog: {
                    import: {
                        visible: false,
                        errors: {},
                        model: {
                            file: [],
                            type: 'cover',
                            version: '',
                        }
                    }
                }
            }
        },
        computed: {
            options: function () {
                return {
                    params: {
                        paginate: this.list.per_page,
                        page: this.list.current_page,
                        type: this.search.type.value,
                        'post_date_start': this.search.post_date.post_date_start,
                        'post_date_end': this.search.post_date.post_date_end,
                        'query_text': this.search.query_text,
                        '_sort': this.search._sort,
                        '_order': this.search._order
                    }
                };
            },
        },
        watch: {
            'search.type.value': {
                handler: function (val, oldVal) {
                    this.list.current_page = 1;
                    this.load();
                },
                deep: true
            },
//            'search.post_date.value' : {
//                handler : function(val, oldVal) {
//                    this.list.current_page = 1;
//                    this.load();
//                },
//                deep: true
//            },
            /*'search.query_text' : {
                handler : function(val, oldVal) {
                    this.list.current_page = 1;
                    this.load();
                },
                deep : true
            },*/
        },
        components: {
            'content-component': content
        },
        mounted() {
            this.load();
        },
        methods: {
            load() {
                this.loading = true;

                axios.get('/api/asset', this.options)
                    .then(response => {
                        console.log(response);
                        this.list = response.data;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.loading = false;
                    });
            },
            deleteRow(index, id, data) {
                this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.delete('/api/asset/' + id)
                        .then(response => {
                            this.$message({
                                type: 'success',
                                message: '删除成功!'
                            });
                            data.splice(index, 1);
                        }).catch(error => {
                        if (error.response.status == 404) {
                            this.$message.error('欲删除的资产不存在');
                        } else if (error.response.status == 500) {
                            this.$message.error('删除失败');
                        }
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            handleSizeChange(val) {
                this.list.per_page = val;
                //console.log(`每页 ${val} 条`);
                this.load();
            },
            handleCurrentChange(val) {
                this.list.current_page = val;
                //console.log(`当前页: ${val}`);
                this.load();
            },
            handleIconClick() {
                //console.log('handlerIconClick');
                this.load();
            },
            changePostDate(val) {
                //console.log(val);
                //console.log(this.search.post_date.value[0]);
                this.search.post_date.post_date_start = val.substring(0, 10);
                this.search.post_date.post_date_end = val.substring(13);
                this.list.current_page = 1;
                this.load();
            },
            handleSelectionChange(val) {
                //console.log(val);
                this.batch.asset = val;
            },
            batchDelete() {
                //console.log('batchDelete');
                var ids = new Array();
                for (var i in this.batch.asset) {
                    //console.log(this.batch.asset[i]);
                    ids.push(this.batch.asset[i].id);
                }
                //console.log(ids);
                this.batch.params.ids = ids;
                //console.log(this.batch.params);
                axios.post('/api/asset/batch-delete', this.batch.params)
                    .then(response => {
                        //console.log(response);
                        this.load();
                    }).catch(error => {
                    console.log(error);
                });
            },
            sortChange(val) {
                //console.log(val);
                this.search._sort = val.prop;
                if (val.order == 'descending') this.search._order = 'desc';
                else this.search._order = 'asc';
                this.load();
            },
            exportAsset() {
                window.open("/api/asset/export", "_blank");

//              axios({
//                  method:'get',
//                  url:'/api/asset/export',
//                  responseType:'stream'
//              })
//                      .then(function(response) {
//                          response.data.pipe(fs.createWriteStream('1.xls'))
//                      });
            },

            exportPrint() {
                window.open("/api/asset/print", "_blank");
            },

            exportInvoice(id) {
                window.open("/api/asset/" + id + "/export", "_blank");
            },

            //处理附件上传结果
            handleSuccess(response, file, fileList) {
                //console.log(response);
                this.dialog.import.model.file = response.url;
            },

            //导入数据
            handleImport() {
                //console.log('handleImport');
                this.dialog.import.visible = true;
            },

            handleImportSave() {
                //console.log('handleImportSave');
                this.fullscreenLoading = true;
                axios.post('/api/asset/import', this.dialog.import.model)
                    .then(response => {
                        //console.log(response.data);
                        this.$message('导入成功');
                        this.fullscreenLoading = false;
                        this.dialog.import.visible = false;
                        this.load();
                    }).catch(error => {
                    if (error.response.status == 422) {
                        this.dialog.import.errors = error.response.data;
                    } else {
                        this.$message.error('导入失败');
                    }
                    this.fullscreenLoading = false;
                });
            }
        }
    }
</script>