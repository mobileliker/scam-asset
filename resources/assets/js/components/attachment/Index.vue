<template>
    <content-component>
        <el-col :lg="24">
            <el-breadcrumb id="breadcrumb" separtor="/">
                <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
                <!--<el-breadcrumb-item :to="{ path: '/collection' }"></el-breadcrumb-item>-->
                <el-breadcrumb-item>附件管理</el-breadcrumb-item>
            </el-breadcrumb>
        </el-col>

        <el-col :lg="3">
            <el-select v-model="search.user_id.value" placeholder="上传人" clearable>
                <el-option v-for="item in search.user_id.options" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-col>
        <el-col :lg="4">
            <el-date-picker v-model="search.created_at.value" type="daterange" align="right" placeholder="上传时间范围"
                            :picker-options="search.created_at.pickerOptions"
                            @change="changeInputDate"></el-date-picker>
        </el-col>
        <el-col :lg="4">
            <el-input placeholder="请输入名称、编号、来源或描述进行搜索" icon="search" v-model="search.query_text"
                      :on-icon-click="handleSearchIconClick"></el-input>
        </el-col>
        <el-col :lg="24" class="list">
            <el-table :data="list.data" border style="width: 100%" v-loading="loading" element-loading-text="拼命加载数据中"
                      @selection-change="handleSelectionChange" @sort-change="handleSortChange">

                <el-table-column type="selection"></el-table-column>
                <el-table-column type="index" label="序号" width="70"></el-table-column>
                <el-table-column prop="created_at" label="创建时间" width="200" sortable></el-table-column>
                <el-table-column prop="name" label="名称" sortable></el-table-column>
                <el-table-column prop="path" label="路径" sortable></el-table-column>
                <el-table-column prop="user" label="创建人" width="150" sortable></el-table-column>
                <el-table-column fixed="right" label="操作" width="100">
                    <template scope="scope">
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
    import content from '../layouts/Content.vue'
    import error from '../layouts/Error.vue'

    export default {
        components: {
            'content-component': content,
            'error-component': error
        },
        computed: {
            options: function () {
                return {
                    params: {
                        paginate: this.list.per_page,
                        page: this.list.current_page,
                        user_id: this.search.user_id.value,
                        created_at_start: this.search.created_at.created_at_start,
                        created_at_end: this.search.created_at.created_at_end,
                        query_text: this.search.query_text,
                        _sort: this.search._sort,
                        _order: this.search._order
                    }
                }
            }
        },
        watch: {
            'search.user_id.value': {
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
        data() {
            return {
                token: {'X-CSRF-TOKEN': window.Laravel.csrfToken},
                loading: true,
                fullscreenLoading: false,
                search: {
                    user_id: {
                        value: '',
                        options: []
                    },
                    created_at: {
                        value: '',
                        created_at_start: '',
                        created_at_end: '',
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
                    'per_page': 10,
                    'prev_page_url': '',
                    'to': '',
                    'total': 0,
                    data: []
                },
                batch: {
                    data: [],
                    params: {
                        ids: []
                    }
                }
            }
        },
        mounted() {
            axios.get('/api/user/all')
                .then(response => {
                    this.search.user_id.options = response.data;
                    this.load();
                }).catch(error => {
                this.$message.error('获取所有用户列表失败');
            });
        },
        methods: {
            load() {
                //console.log('load');
                this.loading = true;
                axios.get('/api/attachment', this.options)
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
                this.search.created_at.created_at_start = val.substring(0, 10);
                this.search.created_at.created_at_end = val.substring(13);
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
                this.batch.data = val;
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
                this.$confirm('此操作将永久删除该附件，是否继续？', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    //axios.delete('/api/attachment' + id)
                    axios.get('/api/attachment/' + id + '/delete')
                        .then(response => {
                            this.$message({
                                type: 'success',
                                message: '删除成功'
                            });
                            data.splice(index, 1);
                        }).catch(error => {
                        if (error.response.status == 404) {
                            this.$message.error('欲删除的附件不存在');
                        } else {
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
                for (var i in this.batch.data) {
                    ids.push(this.batch.data[i].id);
                }
                this.batch.params.ids = ids;
                axios.post('/api/attachment/batch-delete', this.batch.params)
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
            }
        }
    }
</script>
