/**
* @version 2.0
* @author: wuzhihui
* @date: 2017/7/10
* @description:
* （1）初步完成了农具管理的首页功能；（2017/7/10）
* （2）完成了农具数据的导入功能；（2017/7/14）
* （3）添加拍摄清单功能；（2017/12/5）
*
* @version : 2.0.3
* @author : wuzhihui
* @date :2018/1/12
* @description :
* （1）更改EXCEL的导入格式；（2018/1/12）
* （2）新增storage、origin字段，去除display、category字段；（2018/1/12）
* （3）新增cateogory和p_category字段；（2018/1/22）
*/

<template>
    <content-component>
        <el-col :lg="24">
            <el-breadcrumb id="breadcrumb" separtor="/">
                <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
                <!--<el-breadcrumb-item :to="{ path: '/collection' }"></el-breadcrumb-item>-->
                <el-breadcrumb-item>农具管理</el-breadcrumb-item>
            </el-breadcrumb>
        </el-col>
        <!--<el-col :lg="3">-->
            <!--<el-select v-model="search.category.value" placeholder="分类" clearable>-->
                <!--<el-option v-for="item in search.category.options" :label="item.label" :value="item.value"></el-option>-->
            <!--</el-select>-->
        <!--</el-col>-->
        <el-col :lg="3">
            <el-select v-model="search.keeper_id.value" placeholder="保管人" clearable>
                <el-option v-for="item in search.keeper_id.options" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-col>
        <el-col :lg="3">
            <el-select v-model="search.user_id.value" placeholder="编辑人" clearable>
                <el-option v-for="item in search.user_id.options" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-col>
        <el-col :lg="3">
            <el-select v-model="search.is_asset.value" placeholder="固定资产" clearable>
                <el-option v-for="item in search.is_asset.options" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-col>
        <el-col :lg="4">
            <el-date-picker v-model="search.input_date.value" type="daterange" align="right" placeholder="入库时间范围"
                            :picker-options="search.input_date.pickerOptions"
                            @change="changeInputDate"></el-date-picker>
        </el-col>
        <el-col :lg="4">
            <el-input placeholder="请输入名称、编号、来源或描述进行搜索" icon="search" v-model="search.query_text"
                      :on-icon-click="handleSearchIconClick"></el-input>
        </el-col>
        <el-col :lg="4" class="pull-right">
            <router-link to="/collection/farm/create">
                <el-button type="success">添加</el-button>
            </router-link>
            <el-button type="success" @click="handleImport">导入</el-button>
            <el-button type="success" @click="handleCaremaList">拍摄单</el-button>
        </el-col>
        <el-col :lg="24" class="list">
            <el-table :data="list.data" border style="width: 100%" v-loading="loading" element-loading-text="拼命加载数据中"
                      @selection-change="handleSelectionChange" @sort-change="handleSortChange">
                <el-table-column type="selection"></el-table-column>
                <el-table-column type="index" label="序号" width="70"></el-table-column>
                <el-table-column prop="input_date" label="入库时间" width="130" sortable></el-table-column>
                <el-table-column prop="p_category" label="父分类" width="110" sortable></el-table-column>
                <el-table-column prop="category" label="分类" sortable></el-table-column>
                <el-table-column prop="name" label="名称" sortable>
                    <template scope="scope">
                        <router-link :to="'/collection/farm/' + scope.row.id">{{scope.row.name}}</router-link>
                    </template>
                </el-table-column>
                <el-table-column prop="serial" label="编号" width="130" sortable></el-table-column>
                <el-table-column prop="origin" label="产地" sortable></el-table-column>
                <el-table-column prop="source" label="来源" width="90" sortable></el-table-column>
                <!--<el-table-column prop="keeper" label="保管人" sortable></el-table-column>-->
                <el-table-column prop="user" label="编辑人" width="110" sortable></el-table-column>
                <el-table-column fixed="right" label="操作" width="200">
                    <template scope="scope">
                        <el-button type="text" size="small" @click="handleImageClick(scope.row.id)">图片管理</el-button>
                        <el-button type="text" size="small">
                            <router-link :to="'/collection/farm/' + scope.row.id + '/edit'">编辑</router-link>
                        </el-button>
                        <el-button type="text" size="small"
                                   @click="handleDeleteRow(scope.$index, scope.row.id, list.data)">删除
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-col>
        <el-col :lg="24">
            <el-button type="success" @click="handleBatchDelete">删除</el-button>
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange"
                           :current-page="list.current_page" :page-sizes="['10', '15', '20', '50']"
                           :page-size="list.per_page" layout="total, sizes, prev, pager, next, jumper"
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
                <!--<el-button type="primary" @click="handleImportSave('')" element-loading-text="导入中..."-->
                           <!--v-loading.fullscreen.lock="fullscreenLoading">导入-->
                <!--</el-button>-->
                <el-button type="primary" @click="handleImportSave('V2')" element-loading-text="导入中..."
                           v-loading.fullscreen.lock="fullscreenLoading">导入(新）
                </el-button>
            </div>
        </el-dialog>
        <szy-image-dialog :prefix="dialog.image.prefix" :data="dialog.image.data"
                          v-model="dialog.image.visible"></szy-image-dialog>
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

    .list {
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>

<script>
    import content from '../../layouts/Content.vue'
    import error from '../../layouts/Error.vue'
    import szyImageDialog from '../../Basic/Dialog/SzyImageDialog.vue'

    export default {
        components: {
            'content-component': content,
            'error-component': error,
            'szy-image-dialog': szyImageDialog
        },
        data() {
            return {
                token: {'X-CSRF-TOKEN': window.Laravel.csrfToken},
                loading: true,
                fullscreenLoading: false,
                search: {
//                    category: {
//                        value: '',
//                        options: [ //大田农作生产农具分类：1 耕地整地工具、2 播种工具、3 中耕工具、4 施肥植保工具、5 排灌工具、6 收割工具、 7 运输工具、8 脱晒工具、9 加工工具、10 计量工具、11劳保工具、12 农用器具；专门类：制酒用具，茶叶种植、采集加工用具，蚕桑丝织用器，棉纺织用器，甘蔗种植、收获、加工用具，渔业生产用具，岭南热带水果种植、收获、加工用具。
//                            {
//                                label: '耕地整地工具',
//                                value: '耕地整地工具'
//                            },
//                            {
//                                label: '播种工具',
//                                value: '播种工具'
//                            },
//                            {
//                                label: '中耕工具',
//                                value: '中耕工具'
//                            },
//                            {
//                                label: '施肥植保工具',
//                                value: '施肥植保工具'
//                            },
//                            {
//                                label: '排灌工具',
//                                value: '排灌工具'
//                            },
//                            {
//                                label: '收割工具',
//                                value: '收割工具'
//                            },
//                            {
//                                label: '运输工具',
//                                value: '运输工具'
//                            },
//                            {
//                                label: '脱晒工具',
//                                value: '脱晒工具'
//                            },
//                            {
//                                label: '加工工具',
//                                value: '加工工具'
//                            },
//                            {
//                                label: '计量工具',
//                                value: '计量工具'
//                            },
//                            {
//                                label: '劳保工具',
//                                value: '劳保工具'
//                            },
//                            {
//                                label: '农用器具',
//                                value: '农用器具'
//                            },
//                            {
//                                label: '制酒用具',
//                                value: '制酒用具'
//                            },
//                            {
//                                label: '茶叶种植、采集加工用具',
//                                value: '茶叶种植、采集加工用具'
//                            },
//                            {
//                                label: '蚕桑丝织用器',
//                                value: '蚕桑丝织用器'
//                            },
//                            {
//                                label: '棉纺织用器',
//                                value: '棉纺织用器'
//                            },
//                            {
//                                label: '甘蔗种植、收获、加工用具',
//                                value: '甘蔗种植、收获、加工用具'
//                            },
//                            {
//                                label: '渔业生产用具',
//                                value: '渔业生产用具'
//                            },
//                            {
//                                label: '岭南热带水果种植、收获、加工用具',
//                                value: '岭南热带水果种植、收获、加工用具'
//                            }
//                        ]
//                    },
                    keeper_id: {
                        value: '',
                        options: []
                    },
                    user_id: {
                        value: '',
                        options: []
                    },
                    is_asset: {
                        value: '',
                        options: [
                            {
                                label: '是',
                                value: '1'
                            },
                            {
                                label: '否',
                                value: '0'
                            }
                        ]
                    },
                    input_date: {
                        value: '',
                        input_date_start: '',
                        input_date_end: '',
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
                    query_text: '',
                    _sort: 'id',
                    _order: 'desc'
                },
                list: {
                    current_page: 1,
                    from: '',
                    'last_page': '',
                    'next_page_url': '',
                    'per_page': 15,
                    'prev_page_url': '',
                    'to': '',
                    'total': 0,
                    data: [
//                        {
//                            id : '3',
//                            input_date : '2015-07-24',
//                            category : '耕地整地工具',
//                            name : '牛轭',
//                            serial : 'A0300003',
//                            source : '湖南省娄底市双丰县',
//                            keeper : 'admin',
//                            user : 'admin'
//                        }
                    ]
                },
                batch: {
                    farm: [],
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
                            version : '',
                        }
                    },
                    image: {
                        visible: false,
                        prefix: '',
                        data: [],
                        modal: false
                    },
                    imageView: {
                        visible: false,
                        url: ''
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
                        //category: this.search.category.value,
                        keeper_id: this.search.keeper_id.value,
                        user_id: this.search.user_id.value,
                        is_asset: this.search.is_asset.value,
                        input_date_start: this.search.input_date.input_date_start,
                        input_date_end: this.search.input_date.input_date_end,
                        query_text: this.search.query_text,
                        _sort: this.search._sort,
                        _order: this.search._order
                    }
                }
            }
        },
        watch: {
//            'search.category.value': {
//                handler: function (val, oldVal) {
//                    this.list.current_page = 1;
//                    this.load();
//                },
//                deep: true
//            },
            'search.keeper_id.value': {
                handler: function (val, oldVal) {
                    this.list.current_page = 1;
                    this.load();
                },
                deep: true
            },
            'search.user_id.value': {
                handler: function (val, oldVal) {
                    this.list.current_page = 1;
                    this.load();
                },
                deep: true
            },
            'search.is_asset.value': {
                handler: function (val, oldVal) {
                    this.list.current_page = 1;
                    this.load();
                },
                deep: true
            },
//            'search.input_date.value' : {
//                handler : function(val, oldVal) {
//                    this.list.current_page = 1;
//                    this.load();
//                },
//                deep : true
//            }
        },
        mounted() {
            //获取所有用户
            axios.get('/api/user/all')
                .then(response => {
                    this.search.keeper_id.options = response.data;
                    this.search.user_id.options = response.data;
                    this.load();
                }).catch(error => {
                this.$message.error('获取所有用户列表失败');
            });
        },
        methods: {
            //加载数据
            load() {
                this.loading = true;
                axios.get('/api/collection/farm', this.options)
                    .then(response => {
                        this.list = response.data;
                        this.loading = false;
                    }).catch(error => {
                    this.$message.error('加载数据错误');
                    this.list.data = '';
                    this.loading = false;
                });
            },
            //搜索框的日期选择改变事件
            changeInputDate(val) {
                this.search.input_date.input_date_start = val.substring(0, 10);
                this.search.input_date.input_date_end = val.substring(13);
                this.list.current_page = 1;
                this.load();
            },

            //搜索按钮点击事件
            handleSearchIconClick() {
                //console.log('handlerSearchIconClick');
                this.list.current_page = 1;
                this.load();
            },

            //多选选择事件
            handleSelectionChange(val) {
                //console.log('handleSelectionChange');
                this.batch.farm = val;
            },

            //排序选择事件
            handleSortChange(val) {
                //console.log('handleSortChange');
                this.search._sort = val.prop;
                if (val.order == 'descending') this.search._order = 'desc';
                else this.search._order = 'asc';
                this.load();
            },

            //单个删除事件
            handleDeleteRow(index, id, data) {
                this.$confirm('此操作将永久删除该农具，是否继续？', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    //axios.delete('/api/collection/farm/' + id)
                    axios.get('/api/collection/farm/' + id + '/delete')
                        .then(response => {
                            this.$message({
                                type : 'success',
                                message : '删除成功'
                            });
                            data.splice(index, 1);
                        }).catch(error => {
                            if(error.response.status == 404){
                                this.$message.error('欲删除的农具不存在');
                            }else{
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

            //批量删除
            handleBatchDelete() {
                var ids = new Array();
                for (var i in this.batch.farm) {
                    ids.push(this.batch.farm[i].id);
                }
                this.batch.params.ids = ids;
                axios.post('/api/collection/farm/batch-delete', this.batch.params)
                    .then(response => {
                        this.load();
                    }).catch(error => {
                    this.$message.error('批量删除失败');
                });
            },

            //每页大小修改事件
            handleSizeChange(val) {
                this.list.per_page = val;
                this.load();
            },

            //页码切换
            handleCurrentChange(val) {
                this.list.current_page = val;
                this.load();
            },

            //导入数据
            handleImport() {
                //console.log('handleImport');
                this.dialog.import.visible = true;
            },

            //导入数据按钮
            handleImportSave(version) {
                //console.log('handleImportSave');
                this.fullscreenLoading = true;
                this.dialog.import.model.version = version;
                axios.post('/api/collection/farm/import', this.dialog.import.model)
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
            },

            //处理附件上传结果
            handleSuccess(response, file, fileList) {
                //console.log(response);
                this.dialog.import.model.file = response.url;
            },

            handleImageClick(id) {
                this.dialog.image.prefix = 'api/collection/farm/' + id;

                this.dialog.image.visible = true;
            },

            //拍摄清单
            handleCaremaList() {
              window.open('/api/farm/camera-list', '_blank');
            }
        }
    }
</script>
