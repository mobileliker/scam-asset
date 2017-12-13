/**
* 日志功能首页
* @version : 2.0.2
* @author : wuzhihui
* @date : 2017/11/30
* @description :
* （1）基本功能；
* （2）修改列表的版式；（2017/11/30）
* （3）新增IP地址项；（2017/12/6）
*  (4) 查询工具栏添加用户选择；（2017/12/13）
*/

<template>
    <content-component id="content">
        <!--alog-index-->
        <el-col :lg="24">
            <el-breadcrumb id="breadcrumb" separator="/">
                <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
                <el-breadcrumb-item>操作日志</el-breadcrumb-item>
            </el-breadcrumb>
        </el-col>
        <el-col :lg="4">
            <el-select v-model="search.module.value" clearable placeholder="模块" @change="moduleChange">
                <el-option v-for="item in search.module.options" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-col>
        <el-col :lg="4">
            <el-select v-model="search.operate.value" clearable placeholder="操作" @change="operateChange">
                <el-option v-for="item in search.operate.options" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-col>
        <el-col :lg="4">
            <el-select v-model="search.user_id.value" clearable placeholder="用户" @change="user_idChange">
                <el-option v-for="item in search.user_id.options" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-col>
        <el-col :lg="4">
            <el-input placeholder="请输入内容进行查询" icon="search" v-model="search.query_text"
                      :on-icon-click="textQuery"></el-input>
        </el-col>
        <el-col :lg="24" class="list">
            <el-table :data="list.data" border style="width: 100%" v-loading="view.table.loading"
                      element-loading-text="拼命加载数据中" @sort-change="sortChange">
                <el-table-column type="index" label="序号" width="70"></el-table-column>
                <el-table-column label="时间" prop="log_time" sortable width="200"></el-table-column>
                <el-table-column label="用户名" prop="user_name" sortable width="120"></el-table-column>
                <el-table-column label="模块" prop="module" sortable width="100"></el-table-column>
                <el-table-column label="操作" prop="operate" sortable width="100"></el-table-column>
                <el-table-column label="IP" prop="ip" sortable width="120"></el-table-column>
                <el-table-column label="内容" prop="content" sortable></el-table-column>
            </el-table>
        </el-col>
        <el-col :lg="24">
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange"
                           :current-page="list.current_page" :page-sizes="['10', '15', '20', '50']"
                           :page-size="list.per_page" layout="total, sizes, prev, pager, next, jumper"
                           :total="list.total" class="pull-right">
            </el-pagination>
        </el-col>
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
    import content from "../layouts/Content.vue"

    export default {
        components: {
            'content-component': content
        },
        data() {
            return {
                search: {
                    module: {
                        value: '',
                        options: []
                    },
                    operate: {
                        value: '',
                        options: [
                            {
                                label: '新增',
                                value: 0
                            },
                            {
                                label: '删除',
                                value: 1
                            },
                            {
                                label: '修改',
                                value: 2
                            },
                            {
                                label: '查询',
                                value: 3
                            },
                            {
                                label: '查看',
                                value: 4
                            },
                            {
                                label: '登录',
                                value: 5
                            },
                            {
                                label: '退出',
                                value: 6
                            },
                            {
                                label: '批量删除',
                                value: 7
                            },
                            {
                                label: '导入',
                                value: 8
                            },
                            {
                                label: '保存图片',
                                value: 9
                            },
                            {
                                label: '删除图片',
                                value: 10
                            }
                        ]
                    },
                    user_id: {
                        value: '',
                        options: []
                    },
                    query_text: ''
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
                    data: []
                },
                view: {
                    table: {
                        loading: false,
                    }
                }
            }
        },
        computed: {
            params: function () {
                return {
                    index: {
                        params: {
                            paginate: this.list.per_page,
                            page: this.list.current_page,
                            module: this.search.module.value,
                            operate: this.search.operate.value,
                            user_id: this.search.user_id.value,
                            'query_text': this.search.query_text,
                            '_sort': this.search._sort,
                            '_order': this.search._order
                        }
                    }
                }
            }
        },
        mounted() {
            axios.get('/api/alog/all-module')
                .then((response) => {
                    this.search.module.options = response.data;
                });

            this.load();
        },
        methods: {
            load() {
                this.view.table.loading = true;
                //console.log('load');
                axios.get('/api/alog', this.params.index)
                    .then(response => {
                        //console.log(response);
                        this.list = response.data;
                        this.view.table.loading = false;
                    }).catch(error => {
                    console.log(error);
                    this.view.table.loading = false;
                });

                axios.get('/api/user/all')
                    .then(response => {
                        this.search.user_id.options = response.data;
                    }).catch(error => {
                    this.$message.error('获取所有用户列表失败');
                });
            },

            moduleChange() {
                console.log('moduleChange');
                this.list.current_page = 1;
                this.load();
            },
            operateChange() {
                this.list.current_page = 1;
                this.load();
            },
            //文本查询
            textQuery() {
                //console.log('testQuery');
                this.list.current_page = 1;
                this.load();
            },

            //排序选择
            sortChange(val) {
                //console.log(val);
                this.search._sort = val.prop;
                if (val.order == 'descending') this.search._order = 'desc';
                else this.search._order = 'asc';
                this.load();
            },

            //用户选择
            user_idChange() {
                this.list.current_page = 1;
                this.load();
            },

            //每页条数更改
            handleSizeChange(val) {
                this.list.per_page = val;
                //console.log(`每页 ${val} 条`);
                this.load();
            },

            //跳转页码
            handleCurrentChange(val) {
                this.list.current_page = val;
                //console.log(`当前页: ${val}`);
                this.load();
            },
        }
    }
</script>
